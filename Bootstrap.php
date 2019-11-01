<?php

spl_autoload_register(function ($className) {
    include preg_replace('\\', '/', substr($className, 1)) . '.php';
});

$router = Tiny\Router\Router::getInstance();

$router->add('/', [
    'controller' => 'TestController',
    'action'     => 'testAction',
    'method'     => 'GET'
]);

$router->callController($_SERVER['REQUEST_URI']);