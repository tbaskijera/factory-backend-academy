<?php

use App\Router;
use App\Request;
use App\Controllers\IndexController;

# GENERIC
Router::addRoute(Request::METHOD_GET, '/index', [IndexController::class, 'indexAction']);
Router::addRoute(Request::METHOD_GET, '/index/json', 'IndexController@indexJsonAction');

# HTML
Router::addRoute(Request::METHOD_GET, '/users/index', 'UserController@index');
Router::addRoute(Request::METHOD_GET, '/users/show/{id}', 'UserController@show');
Router::addRoute(Request::METHOD_GET, '/users/create', 'UserController@create');
Router::addRoute(Request::METHOD_GET, '/users/edit/{id}', 'UserController@edit');

# UTILITY
Router::addRoute(Request::METHOD_POST, '/users/store', 'UserController@store');
Router::addRoute(Request::METHOD_POST, '/users/update/{id}', 'UserController@update');
