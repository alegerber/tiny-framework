<?php declare(strict_types=1);

namespace Tiny\Router;

class Router
{
    /**
     * @var self
     */
    private static self $instance;

    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): self
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @var array $routes
     */
    private array $routes = [];

    /**
     * @param $route
     * @param $params
     */
    public function add(string $route, array $params): void
    {
        $this->routes[$route] = $params;
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @param string $requestUri
     * @return null|array
     */
    public function match(string $requestUri): ?array
    {
        foreach ($this->routes as $route => $params) {
            if ($route === $requestUri) {
                return $params;
            }
        }
        return null;
    }

    /**
     * @param string $requestUri
     */
    public function callController(string $requestUri): void
    {
        if (null !== $params = $this->match($requestUri)) {
            $controllerName = $params['controller'];

            $controllerClass = sprintf('\\App\\Controller\\%s', $controllerName);

            if(isset($params['controller_args'])){

                $controllerArgs = [];

                foreach ($params['controller_args'] as $controllerArg) {
                    $controllerArgs[] = new $controllerArg();
                }

                $controller = new $controllerClass(...$controllerArgs);
            } else {
                $controller = new $controllerClass();
            }

            $method = $params['action'];

            if(isset($params['action_args'])){

                $actionArgs = [];

                foreach ($params['action_args'] as $actionArg) {
                    $actionArgs[] = new $actionArg();
                }

                echo (string) $controller->$method(...$actionArgs);
            } else {
                echo (string) $controller->$method();
            }

        } else {
            print_r($requestUri);
            echo 'route not found';
            //throw new RouteException('route not found');
        }
    }
}