<?php

use App\Controllers\IndexController;
use App\JsonResponse;
use App\Router;
use App\Response;
use App\Interfaces\RequestInterface;
use App\Interfaces\ResponseInterface;

Router::addRoute('GET', '/index', [IndexController::class, 'indexAction']);
Router::addRoute('GET', '/index/json', 'IndexController@indexJsonAction');

# Normal callbacks also work
/*
require('books.php');
Router::addRoute('GET', '/index', function (RequestInterface $request): ResponseInterface {
    return new Response(var_dump(getBooks()));
});

Router::addRoute('GET', '/index/{productId}', function (RequestInterface $request): ResponseInterface {
    $books = getBooks();
    foreach ($books as $book) {
        if ($book['id'] == $request->getParams()['productId']) {
            return new Response(var_dump($book));
        }
    }
    return new Response("Not found");
});
Router::addRoute('GET', '/index/{brand}/{model}/{id}', function (RequestInterface $request): JsonResponse {
    return new JsonResponse($request->getParams());
});
*/
