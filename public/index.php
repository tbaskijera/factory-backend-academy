<?php

require_once 'vendor/autoload.php';

use App\Router;
use App\Request;

include("./src/routes.php");

$request = new Request($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
$response = Router::resolveRoute($request);
echo $response->send();
