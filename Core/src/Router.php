<?php

namespace Core;

use Core\Route;
use Core\AbstractController;

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

    public function run(){
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

    public static function warmup(Config $config): void
    {
        $namespaces = $config::$config; // list of namespaces names to scan

        $parser = (new ParserFactory())->createForNewestSupportedVersion();
        $routeClassVisitor = new RouteClassVisitor("");
        foreach ($namespaces as $namespace=>$controllers) {
            $namespace = '\\' . $namespace; // add \
            $dir_name = __DIR__ . '/../' . $controllers['resource']['path'];
            if (!file_exists($dir_name) || !is_dir($dir_name)) {
                continue;
            }
            $dir = new \DirectoryIterator($dir_name); // root directory of current namespace
            // scan current directory for files
            self::scanDirectory($dir, $routeClassVisitor, $parser);
        }

        self::$instance->routes = $routeClassVisitor->getRoutes();
    }

    private static function scanDirectory(\DirectoryIterator $dir, RouteClassVisitor $routeClassVisitor, Parser $parser): void{
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
        $routeControllerName = $this->routes[$request->path] ?? '';
        if ($routeControllerName === '') {
            return null;
        }

        $routeClass = explode('::', $routeControllerName)[0];
        $object = new $routeClass();
        $routeMethod = explode('::', $routeControllerName)[1];
        return [$object, $routeMethod];
    }
}
