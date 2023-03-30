<?php

use App\Router;
use App\Response;
use App\src\interfaces\RequestInterface;
use App\src\interfaces\ResponseInterface;

Router::addRoute("GET", "/index.php", function (RequestInterface $request): ResponseInterface {
    return new Response("GET method");
});

Router::addRoute("POST", "index.php", function (RequestInterface $request): ResponseInterface {
    return new Response("POST method");
});
