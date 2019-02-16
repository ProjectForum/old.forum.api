<?php
namespace forum\library;

class Router
{
    protected static $_routes = [];

    /**
     * 对本次请求进行响应
     *
     * @return void
     */
    public static function respond()
    {
        $routes = self::$_routes;
        $dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) use ($routes) {
            foreach ($routes as $key => $route) {
                $r->addRoute(
                    $route['method'],
                    $route['pattern'],
                    $route['handler']
                );
            }
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                // 404 Not Found
                GraphQL::jsonResponse([
                    'code' => 404,
                    'message' => 'Resource Not Found',
                ], 404);
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // 405 Method Not Allowed
                GraphQL::jsonResponse([
                    'code' => 405,
                    'message' => 'Method Not Allowed',
                ], 405);
                break;
            case \FastRoute\Dispatcher::FOUND:
                list($__, $handler, $vars) = $routeInfo;
                // call $handler with $vars
                if (is_callable($handler)) {
                    $response = $handler($vars);
                    if (!empty($response)) {
                        if (is_array($response)) {
                            GraphQL::jsonResponse($response);
                        } else {
                            echo $response;
                        }
                    }
                }
                break;
        }
    }

    /**
     * 增加一条路由规则
     *
     * @param string|string[] $method
     * @param string $pattern
     * @param callable $handler
     * @return void
     */
    public static function addRoute($method, string $pattern, callable $handler)
    {
        array_push(self::$_routes, [
            'method' => $method,
            'pattern' => $pattern,
            'handler' => $handler,
        ]);
    }
}
