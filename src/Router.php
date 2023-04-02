<?php

namespace App;

use App\Interfaces\ResponseInterface;
use Exception;

class Router
{
    private static array $routes = [];
    private static array $validMethods = ['GET', 'POST']; # 'PUT', 'DELETE'

    public static function addRoute($method, $url, $callback): void
    {
        try {
            if (!in_array($method, self::$validMethods)) {
                throw new Exception('Invalid HTTP method: $method');
            }

            // Parse the URL and extract parameter names (from Chat GPT)
            preg_match_all('/\{(\w+)\}/', $url, $matches);
            $params = $matches[1];
            $urlPattern = preg_replace('/\{(\w+)\}/', '(\w+)', $url);

            if (is_string($callback)) {
                // If the callback is a string, assume it's a controller method and refactor it
                // ex. 'IndexController@indexJsonAction'
                if (str_contains($callback, '@')) {
                    [$controllerName, $controllerMethod] = explode('@', $callback);
                    $controller = 'App\\Controllers\\'.$controllerName; # temporary, bad solution, use dependency injection container?
                    $callback = [$controller, $controllerMethod];
                }
            }

            if (is_array($callback)) {
                // Check if the callback is an array in the form ['ControllerName', 'methodName']
                // ex. [IndexController::class, 'indexAction']
                // ex. 'IndexController@indexJsonAction' also ends up here because of previous processing
                if (count($callback) !== 2 || !is_string($callback[0]) || !is_string($callback[1])) {
                    throw new Exception('Invalid callback: ' . print_r($callback, true));
                }

                // Create a callable from the array, assuming the first element is the controller class name and the second is the method name
                $controllerName = $callback[0];
                $controllerMethod = $callback[1];
                $callback = function ($request) use ($controllerName, $controllerMethod) {
                    $controller = new $controllerName();
                    return $controller->$controllerMethod($request);
                };
            }

            $route = [
                'method' => $method,
                'url' => $url,
                'callback' => $callback,
                'params' => $params,
                'urlPattern' => $urlPattern,
            ];
            self::$routes[] = $route;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public static function resolveRoute(Request $request): ?ResponseInterface
    {
        $method = $request->getMethod();
        $uri = $request->getUri();

        foreach (self::$routes as $route) {
            if ($route['method'] === $method) {
                $pattern = '/^' . str_replace('/', '\/', $route['urlPattern']) . '$/';
                if (preg_match($pattern, $uri, $matches)) {
                    $params = array_combine($route['params'], array_slice($matches, 1));
                    $request->setParams($params);

                    $callback = $route['callback'];
                    return $callback($request);
                }
            }
        }
        return null;
    }
}
