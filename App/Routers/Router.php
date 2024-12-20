<?php

namespace App\Routers;

use App\Middlewares\CheckAccessMiddleware;
use App\Traits\ResponseTrait;

class Router
{
    use ResponseTrait;

    private $routes = [];
    private $postData;
    private $access;
    public function __construct()
    {
        $this->postData = getPostDataInput();
        $this->access = new CheckAccessMiddleware();
    }
    public function get($version, $path, $controller, $method, $access = false, $inaccessabilty = false)
    {

        $path = '/' . $version . $path;
        $this->routes[$version]['GET'][$path] = ['controller' => $controller, 'method' => $method, 'request' => $this->postData, "requestMethod" => "get", "access" => $access, "inaccessabilty" => $inaccessabilty];
    }

    public function post($version, $path, $controller, $method, $access = false, $inaccessabilty = false)
    {

        $path = '/' . $version . $path;

        $this->routes[$version]['POST'][$path] = ['controller' => $controller, 'method' => $method, 'request' => $this->postData, "requestMethod" => "post", "access" => $access, "inaccessabilty" => $inaccessabilty];
    }

    public function put($version, $path, $controller, $method, $access = false, $inaccessabilty = false)
    {

        $path = '/' . $version . $path;

        $this->routes[$version]['PUT'][$path] = ['controller' => $controller, 'method' => $method, 'request' => $this->postData, "requestMethod" => "put", "access" => $access, "inaccessabilty" => $inaccessabilty];
    }

    public function delete($version, $path, $controller, $method, $access = false, $inaccessabilty = false)
    {

        $path = '/' . $version . $path;
        $this->routes[$version]['DELETE'][$path] = ['controller' => $controller, 'method' => $method, 'request' => '', "requestMethod" => "delete", "access" => $access, "inaccessabilty" => $inaccessabilty];
    }

    public function resolve($version, $requestMethod, $path)
    {
        $path = '/' . $version . '/' . $path;
        $matchedRoute = null;
        // dd($this->routes);
        // Match routes with variable patterns
        foreach ($this->routes[$version][$requestMethod] as $routePath => $route) {
            if ($this->isVariablePattern($routePath)) {
                $pattern = $this->getPatternFromRoute($routePath);
                if (preg_match($pattern, $path, $matches)) {
                    $matchedRoute = $route;
                    break;
                }
            } elseif ($routePath === $path) {
                $matchedRoute = $route;
                break;
            }
        }

        if ($matchedRoute) {
            $controller = $matchedRoute['controller'];
            $method = $matchedRoute['method'];
            $requestMethod = $matchedRoute['requestMethod'];
            $request = $matchedRoute['request'];
            $access = $matchedRoute['access'];
            $accessed_role = [];
            $inaccessabilty = $matchedRoute['inaccessabilty'];
            $not_accessed_role = [];
            if (is_array($access)) {
                $accessed_role = $access;
            } else {
                array_push($accessed_role, $access);
            }

            if (is_array($inaccessabilty)) {
                $not_accessed_role = $inaccessabilty;
            } else {
                array_push($not_accessed_role, $inaccessabilty);
            }

            if ($access) {
                if ($access == "owners") {
                    $this->access->checkAccess(['support', 'admin']);
                } else {
                    $this->access->checkAccess($accessed_role);
                }

            } else if ($inaccessabilty) {
                $this->access->checkAccess($not_accessed_role, false);
            }

            $controllerInstance = new $controller();
            if (isset($matches) && count($matches) && $requestMethod != "put") {
             
                if ($requestMethod =="get" )   $controllerInstance->$method($matches["id"], $request);
                else   $controllerInstance->$method($matches["id"]) ;
            } else {
                if ($requestMethod == 'post') {
                    $controllerInstance->$method($request);
                } else if ($requestMethod == 'put' && isset($matches)) {
                    $controllerInstance->$method($matches["id"], $request);
                } else {
                    // dd($request);
                    $controllerInstance->$method($request);
                }

            }
            exit();
        } else {
            return $this->sendResponse(null, "Not Found", true, HTTP_NotFOUND);
        }
    }

    private function isVariablePattern($path)
    {
        return strpos($path, '{') !== false && strpos($path, '}') !== false;
    }

    private function getPatternFromRoute($routePath)
    {
        $pattern = preg_replace('/\{([^\/]+)\}/', '(?<$1>[^\/]+)', $routePath);
        return '#^' . $pattern . '$#';
    }
}
