<?php
namespace Core;

class Router
{
    private array $routes = [];

    public function get(string $route, string $action)
    {
        $this->addRoute('GET', $route, $action);
    }

    public function post(string $route, string $action)
    {
        $this->addRoute('POST', $route, $action);
    }

    private function addRoute(string $method, string $route, string $action)
    {
        // Simple routing for now. Can be enhanced with regex for params later.
        $this->routes[$method][$route] = $action;
    }

    public function dispatch(string $method, string $url)
    {
        $action = null;
        $params = [];

        // 1. Direct exact match
        if (isset($this->routes[$method][$url])) {
            $action = $this->routes[$method][$url];
        } else {
            // 2. Pattern match (e.g. /admin/support/view/{id})
            if (isset($this->routes[$method])) {
                foreach ($this->routes[$method] as $routePattern => $routeAction) {
                    if (strpos($routePattern, '{') !== false) {
                        $pattern = preg_replace('#\{[a-zA-Z0-9_]+\}#', '([^/]+)', $routePattern);
                        $pattern = '#^' . $pattern . '$#';

                        if (preg_match($pattern, $url, $matches)) {
                            array_shift($matches); // Remove full match
                            $action = $routeAction;
                            $params = $matches;
                            break;
                        }
                    }
                }
            }
        }

        if ($action !== null) {
            // Expected format: 'Namespace\ControllerName@methodName'
            list($controllerClass, $methodName) = explode('@', $action);

            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                
                if (method_exists($controller, $methodName)) {
                    // Call the controller method with extracted parameters
                    return call_user_func_array([$controller, $methodName], $params);
                } else {
                    throw new \Exception("Method {$methodName} not found in controller {$controllerClass}", 500);
                }
            } else {
                throw new \Exception("Controller class {$controllerClass} not found", 500);
            }
        } else {
            throw new \Exception("No route found for URL: {$url}", 404);
        }
    }
}
