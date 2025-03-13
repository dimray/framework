<?php

$router = new Framework\Router;

$router->group(["middleware" => "auth"], function ($router) {

    $router->add("/products/{action}", ["controller" => "products"]);
    $router->add("/products/{id}/{action}", ["controller" => "products"]);
    $router->add("/products", ["controller" => "products", "action" => "index"]);
    $router->add("/logout", ["controller" => "session", "action" => "destroy"]);

    // $router->add("/{controller}/{id:\d+}/{action}");
    $router->add("/{controller}/{id:\d+}/show", ["action" => "show"]);
    $router->add("/{controller}/{id:\d+}/edit", ["action" => "edit"]);
    $router->add("/{controller}/{id:\d+}/update", ["action" => "update"]);
    $router->add("/{controller}/{id:\d+}/delete", ["action" => "delete"]);
    $router->add("/{controller}/{id:\d+}/destroy", ["action" => "destroy", "method" => "post"]);
});

$router->group(["middleware" => "guest"], function ($router) {

    $router->add("/register/{action}", ["controller" => "register"]);
    $router->add("/session/{action}", ["controller" => "session"]);
    $router->add("/register", ["controller" => "register", "action" => "new"]);
    $router->add("/login", ["controller" => "session", "action" => "new"]);
});

$router->add("/", ["controller" => "home", "action" => "index"]);

$router->add("/Admin/{controller}/{action}", ["namespace" => "Admin"]);

$router->add("/{controller}/{action}");

return $router;