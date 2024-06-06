<?php

namespace Core;

use Exception;
use Core\Services\ServiceContainer;
use Core\Services\ServiceClassVisitor;
use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;
use PhpParser\NodeTraverser;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Class_;
use DirectoryIterator;

class ServiceLoader
{

    // @var array<ServiceLoading>
    private array $service_classes = [];
    private ServiceClassVisitor $service_class_visitor;

    public function __construct()
    {
        $this->service_class_visitor = new ServiceClassVisitor();
    }

    public function loadDirectory(string $directory): void
    {
        $dir = new DirectoryIterator($directory);
        foreach ($dir as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $this->load($file->getPathname());
            }

            if ($file->isDir() && $file->getFilename() !== '.' && $file->getFilename() !== '..') {
                $this->loadDirectory($file->getPathname());
            }
        }
    }

    /**
     * Load service class from file and register it in service container if not already registered and all dependencies are loaded.
     * If not all dependencies are loaded, stores service in not_loaded array and will be loaded later when all dependencies are loaded.
     * @param string $filename
     * @return void
     * @throws Exception
     */

    public function load(string $filename): void
    {
        $parser = (new ParserFactory())->createForNewestSupportedVersion();
        $ast = $parser->parse(file_get_contents($filename));
        $this->service_class_visitor->clear();
        $this->service_class_visitor->setFilepath($filename);
        $traverser = new NodeTraverser();
        $traverser->addVisitor($this->service_class_visitor);
        $traverser->traverse($ast);
    }

    public function register(ServiceContainer $container): void
    {
        foreach ($this->service_class_visitor->getServices() as $service) {
            $class_name = $service->class;
            $class_namespace = $service->namespace . '\\';
            

            $container->register((($class_namespace.$class_name.'::').'getInstance')());
        }
    }
}
