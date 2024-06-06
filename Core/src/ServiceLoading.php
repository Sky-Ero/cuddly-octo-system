<?php 

namespace Core;


class ServiceLoading
{
    public string $filename = '';
    public string $namespace = '';
    public string $class = '';

    public function __construct(string $class, string $namespace, string $filename)
    {
        $this->filename = $filename;
        $this->namespace = $namespace;
        $this->class = $class;
    }
}