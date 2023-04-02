<?php

namespace App\Controllers;

use App\Interfaces\ResponseInterface;
use App\JsonResponse;
use App\Response;
use App\Request;

require __DIR__.'/../books.php';

class IndexController
{
    public function indexAction(Request $request): ResponseInterface
    {
        return new Response("Normal response, type string");
    }

    public function indexJsonAction(Request $request): ResponseInterface
    {
        $books = getBooks();
        return new JsonResponse($books);
    }
}
