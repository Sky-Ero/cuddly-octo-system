<?php

namespace Core;

use Exception;
use Core\ServiceContainer;
use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;
use PhpParser\NodeFinder;
use PhpParser\Node\Stmt\Class_;

class ServiceLoader
{

    /**
     * Load service class from file and register it in service container if not already registered and all dependencies are loaded.
     * If not all dependencies are loaded, stores service in not_loaded array and will be loaded later when all dependencies are loaded.
     * @param string $filename
     * @return void
     * @throws Exception
     */

    public static function load(string $filename): void
    {
        $parser = (new ParserFactory())->createForNewestSupportedVersion();
        $ast = $parser->parse(file_get_contents($filename));
        
        $nodeFinder = new NodeFinder();
        $classNodes = $nodeFinder->findInstanceOf($ast, Class_::class);

        foreach ($classNodes as $classNode) {
            $class = $classNode->namespacedName->toString();
            echo "Loading $class\n";
            echo "Dependencies: " . count($classNode->stmts) . "\n";
            echo "Constructor: " . count($classNode->stmts[0]->stmts) . "\n";
            echo "Class Node: " . (new NodeDumper())->dump($classNode) . "\n";
        }
        

    }
}

