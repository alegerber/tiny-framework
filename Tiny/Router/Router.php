<?php declare(strict_types=1);

namespace Tiny\Router;

use Tiny\Http\Response;
use Tiny\Router\Exception\InvalidRouteException;

class Router
{
    /**
     * @var self
     */
    private static $instance;

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
    private $routes = [];

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

            $controller = $this->getController(
                $params['controller'],
                $params['controller_args'] ?? null
            );

            echo (string) $this->getResponse(
                $controller,
                $params['method'],
                $params['method_args'] ?? null
            );

        } else {
            throw new InvalidRouteException($requestUri);
        }
    }


    /**
     * @param string $controllerName
     * @param array|null $paramControllerArgs
     * @return object
     */
    private function getController(string $controllerName, ?array $paramControllerArgs): object
    {
        $controllerClass = sprintf('\\App\\Controller\\%s', $controllerName);

        if (null !== $paramControllerArgs) {

            return new $controllerClass(...$this->instantiation($paramControllerArgs));
        }

        return new $controllerClass();
    }

    /**
     * @param object $controller
     * @param string $methodName
     * @param array|null $paramMethodArgs
     * @return Response
     */
    private function getResponse(object $controller, string $methodName, ?array $paramMethodArgs): Response
    {
        if(null !== $paramMethodArgs){

            return $controller->$methodName(...$this->instantiation($paramMethodArgs));
        }

        return $controller->$methodName();
    }


    /**
     * @param array $paramArgs
     * @return array
     */
    private function instantiation(array $paramArgs): array
    {
        $args = [];

        foreach ($paramArgs as $arg) {
            $args[] = new $arg();
        }

        return $args;
    }
}