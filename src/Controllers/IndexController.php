<?php

namespace App\Controllers;

use App\Interfaces\ResponseInterface;
use App\JsonResponse;
use App\Response;
use App\Request;
use App\TwigResponse;

require __DIR__.'/../books.php';

class IndexController
{
    public function indexAction(Request $request): ResponseInterface
    {
        $books = getBooks();
        return new TwigResponse('index.html.twig', ['books' => $books]);
    }

    public function indexJsonAction(Request $request): ResponseInterface
    {
        $books = getBooks();
        return new JsonResponse($books);
    }
}
