<?php

namespace Core\Router;

use Core\Route;
use Core\AbstractController;
use Core\Services\ServiceInterface;
use Core\Config;
use Core\ConfigYAML;
use Core\Http\Request;

use Exception;
use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Parser;

class Router implements ServiceInterface
{
    // @var array<string, string>
    public $routes = array();
    private static $instance;
    private const attributeName = 'Route';

    private function __construct()
    {
    }

    public static function getInstance(): Router
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function run()
    {
        echo "Router is running\n";
    }

    /**
     * scan namespaces in config and load routes
     * for example:
     * ```yml
     * controllers:
     *     resource:
     *         path: ../Some/
     *         namespace: FirstNamespace
     * 
     * other_controllers:
     *     resource:
     *         path: ../Other/
     *         namespace: SecondNamespace
     * ```
     * ```php
     * // Some/Namespace/FirstController.php
     * <?php
     * namespace FirstNamespace;
     * class FirstController extends AbstractController{
     *      #[Route('/first', 'first')]
     *      public function index(){}
     * }
     * ```
     * etc
     * 
     * for this case we will scan FirstNamespace and SecondNamespace directories (../Some and ../Other) and load routes recursively
     */


    private function insertPath(array $raw_route): void
    {
        // /a/{}/b
        $route = $raw_route[0];
        // $controller = $raw_route['controller'];

        $route = trim($route, '/');
        $levels = explode('/', $route);

        $curr_level = &$this->routes;
        foreach ($levels as &$level) {
            if (!array_key_exists($level, $curr_level)) {
                $curr_level[$level] = array();
            }

            $curr_level = &$curr_level[$level];
        }

        $curr_level['/'] = $raw_route;
    }

    public function warmup(Config $config): void
    {
        $namespaces = $config::$config['routes']['controllers']; // list of namespaces names to scan

        $parser = (new ParserFactory())->createForNewestSupportedVersion();
        $routeClassVisitor = new RouteClassVisitor("");
        foreach ($namespaces as $namespace => $controllers) {
            $namespace = '\\' . $namespace; // add \
            $dir_name = __DIR__ . '/../' . $controllers['path'];
            if (!file_exists($dir_name) || !is_dir($dir_name)) {
                continue;
            }
            $dir = new \DirectoryIterator($dir_name); // root directory of current namespace
            // scan current directory for files
            self::scanDirectory($dir, $routeClassVisitor, $parser);
        }

        for ($i = 0; $i < count($routeClassVisitor->getRoutes()); $i++) {

            $route = $routeClassVisitor->getRoutes()[$i];

            $controller = $route['controller'];

            $this->insertPath($route, $controller);
        }
    }

    private static function scanDirectory(\DirectoryIterator $dir, RouteClassVisitor $routeClassVisitor, Parser $parser): void
    {
        $traverser = new NodeTraverser();
        $traverser->addVisitor($routeClassVisitor);


        $visitDir = function (\DirectoryIterator $dir) use (&$parser, &$routeClassVisitor, &$traverser, &$visitDir) {
            while ($dir->valid()) {
                if ($dir->isFile() && $dir->getExtension() === 'php') {
                    // read file and parse
                    $code = file_get_contents($dir->getPath() . '/' . $dir->getFilename());
                    $routeClassVisitor->filepath = $dir->getPath() . '/' . $dir->getFilename();
                    $ast = $parser->parse($code);
                    $traverser->traverse($ast);
                } else if ($dir->isDir() && $dir->getFilename() !== '.' && $dir->getFilename() !== '..') {
                    $visitDir(new \DirectoryIterator($dir->getPath() . '/' . $dir->getFilename()));
                }
                $dir->next();
            }
        };


        $visitDir($dir);
    }

    public function match(Request &$request): array | null
    {
        try {
            $path = $request->path;
            $path = trim($path, '/');

            $levels = explode('/', $path);

            $curr_level = &$this->routes;
            foreach ($levels as &$level) {
                if (!array_key_exists($level, $curr_level)) {
                    return null;
                }

                $curr_level = &$curr_level[$level];
            }

            return $curr_level['/'];
        } catch (Exception $e) {
            trigger_error("Error: " . $e->getMessage(), E_USER_ERROR);
        }
    }
}
