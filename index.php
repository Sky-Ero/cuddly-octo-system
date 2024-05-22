<?php

namespace TestApp;
require './vendor/autoload.php';
use Core\Main;

class Index
{
    public function index()
    {
        $kernel = new Main();
        $kernel->run();
        // var_dump(get_declared_classes());
    }
}

(new Index())->index();