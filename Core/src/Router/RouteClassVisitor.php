<?php

namespace Core\Router;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use Exception;

class RouteClassVisitor extends NodeVisitorAbstract
{

    private $class;
    private $namespace;
    // @var array<Route>
    private $routes = array();
    private const attributeName = "Route";

    public function __construct(public string $filepath)
    {
    }

    private function ParseRoute(mixed $attribute_string, Node $controller): mixed
    {
        $attribute = new Route($attribute_string->args[0]->value->value);
        foreach ($attribute_string->args[1]->value->items as $method) {
            $attribute->methods[] = $method->value->value;
        }

        if (key_exists('2', $attribute_string->args) && !empty($attribute_string->args[2]->value->value)) {
            $attribute->name = $attribute_string->args[2]->value->value;
        }
        
        if ($attribute->path === null) {
            throw new Exception('[ROUTE PARSE]: Path cannot be null');
        }
        if ($attribute->methods === null) {
            $attribute->methods = array('GET');
        }
        if ($attribute->name === null) {
            $attribute->name = $this->filepath . '_' . $controller->name;
        }
        return array(
            $attribute->path,
            $attribute->methods,
            $attribute->name,
        );
    }

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\Class_) {
            $this->class = $node;
        }
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->namespace = implode('\\', $node->name->getParts());
        }
        if ($node instanceof Node\Stmt\ClassMethod) {
            foreach ($node->attrGroups as $attrGroup) {
                foreach ($attrGroup->attrs as $attr) {
                    if ($attr->name->toString() === self::attributeName) {
                        $route = $this->ParseRoute($attr, $this->class);
                        $route['namespace'] = $this->namespace;
                        $route['controller'] = $this->filepath;
                        $route['class_method'] = $node->name->toString();
                        $this->routes[] = $route;
                    }
                }
            }
        }
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
