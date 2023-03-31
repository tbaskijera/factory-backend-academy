<?php

use App\Router;
use App\Response;
use App\Interfaces\RequestInterface;
use App\Interfaces\ResponseInterface;

Router::addRoute('GET', '/index', function (RequestInterface $request): ResponseInterface {
    return new Response('GET method');
});

Router::addRoute('POST', 'index', function (RequestInterface $request): ResponseInterface {
    return new Response('POST method');
});
