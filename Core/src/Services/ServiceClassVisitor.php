<?php

namespace Core\Services;

use Core\ServiceLoading;
use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;


class ServiceClassVisitor extends NodeVisitorAbstract
{
    private array $services = [];
    private string|null $curr_namespace = null;
    private string|null $curr_class = null;
    private string|null $curr_filepath = null;

    public function __construct(string $filepath = null)
    {
        $this->curr_filepath = $filepath;
    }

    public function setFilepath(string $filepath)
    {
        $this->curr_filepath = $filepath;
    }

    public function clear()
    {
        $this->curr_filepath = null;
        $this->curr_class = null;
        $this->curr_namespace = null;
    }

    public function enterNode(Node $node)
    {

        if ($node instanceof Node\Stmt\Namespace_) {
            $this->curr_namespace = implode('\\', $node->name->getParts());
        }

        if ($node instanceof Node\Stmt\Class_) {
            if ($node->implements == null) {
                $this->curr_filepath = null;
            }
            $isImplementedService = false;
            for ($i = 0; $i < count($node->implements); $i++) {
                if ($node->implements[$i]->toString() == 'ServiceInterface') {
                    $isImplementedService = true;
                    break;
                }
            }

            if (!$isImplementedService) {
                $this->curr_filepath = null;
            }

            $this->curr_class = $node->name->toString();
        }

        if ($this->curr_class != null && $this->curr_namespace != null && $this->curr_filepath != null) {
            $service = new ServiceLoading($this->curr_class, $this->curr_namespace, $this->curr_filepath);

            $this->services[] = $service;

            $this->curr_class = null;
            $this->curr_namespace = null;
            $this->curr_filepath = null;
        }
    }

    public function getServices(): array
    {
        return $this->services;
    }
}
