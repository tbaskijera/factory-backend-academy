<?php

require __DIR__ . '/../vendor/autoload.php';


use App\Router;
use App\Request;

require('../src/routes.php');

$request = new Request();
$response = Router::resolveRoute($request);
echo $response->send();
