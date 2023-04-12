<?php

require __DIR__ . '/../vendor/autoload.php';


use App\Router;
use App\Request;
use App\Models\User;

require('../src/routes.php');

User::__init();
$request = new Request();
$response = Router::resolveRoute($request);
echo $response->send();
