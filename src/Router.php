<?php

namespace App;

use App\Interfaces\ResponseInterface;
use Exception;

class Router
{
    private static array $routes;
    private static array $validMethods = ['GET', 'POST']; # 'PUT', 'DELETE'

    public static function addRoute($method, $url, $callback): void
    {
        try {
            if (!in_array($method, self::$validMethods)) {
                throw new Exception('Invalid HTTP method: $method');
            }

            $route = [
                'method' => $method,
                'url' => $url,
                'callback' => $callback
            ];

            self::$routes[] = $route;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public static function resolveRoute(Request $request): ?ResponseInterface
    {
        $method = $request->getMethod();
        $uri = $request->getPath();

        foreach (self::$routes as $route) {
            if ($route['method'] === $method && $route['url'] === $uri) {
                $callback = $route['callback'];
                return $callback($request);
            }
        }

        return null;
    }
}
