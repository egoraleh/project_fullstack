<?php

declare(strict_types=1);

namespace app\core;

use app\enums\MethodEnum;
use app\enums\HttpStatusCodeEnum;
use Throwable;

class Router
{
    private array $routes = [];

    public function __construct(
        private readonly Request $request,
        private readonly Response $response,
        private readonly Logger $logger
    ) {}

    public function get(string $path, array $callback): void
    {
        $this->addRoute(MethodEnum::GET->value, $path, $callback);
    }

    public function post(string $path, array $callback): void
    {
        $this->addRoute(MethodEnum::POST->value, $path, $callback);
    }

    public function put(string $path, array $callback): void
    {
        $this->addRoute(MethodEnum::PUT->value, $path, $callback);
    }

    public function delete(string $path, array $callback): void
    {
        $this->addRoute(MethodEnum::DELETE->value, $path, $callback);
    }

    private function addRoute(string $method, string $path, array $callback): void
    {
        $regex = $this->convertPathToRegex($path);
        $this->routes[$method][] = [
            'regex' => $regex,
            'callback' => $callback,
            'originalPath' => $path,
        ];
        $this->logger->debug("Route registered: {$method} {$path}");
    }

    private function convertPathToRegex(string $path): string
    {
        $regex = preg_replace_callback('/\{(\w+)(?::([^}]+))?\}/', function ($matches) {
            $paramName = $matches[1];
            $paramPattern = $matches[2] ?? '[^/]+';
            return '(?P<' . $paramName . '>' . $paramPattern . ')';
        }, $path);

        return '#^' . $regex . '$#';
    }

    /**
     * @throws Throwable
     */
    public function resolve(): void
    {
        $method = $this->request->getMethod()->value;
        $path = $this->request->getPath();

        $this->logger->debug("Route resolution attempt", [
            'method' => $method,
            'path' => $path
        ]);

        if (!isset($this->routes[$method])) {
            $this->logger->warning("No routes for method", ['method' => $method]);
            $this->response->setStatusCode(HttpStatusCodeEnum::HTTP_NOT_FOUND);
            $this->response->json(['message' => 'Not Found']);
            return;
        }

        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['regex'], $path, $matches)) {
                $params = [];
                foreach ($matches as $key => $value) {
                    if (!is_int($key)) {
                        $params[$key] = $value;
                    }
                }

                try {
                    [$class, $action] = $route['callback'];
                    $this->logger->info("Executing controller action", [
                        'controller' => $class,
                        'action' => $action,
                        'params' => $params,
                    ]);

                    $controller = new $class();
                    call_user_func_array([$controller, $action], [$this->request, $this->response, $params]);
                    return;
                } catch (Throwable $e) {
                    $this->logger->error("Controller execution failed", [
                        'error' => $e->getMessage(),
                        'controller' => $class ?? null,
                        'action' => $action ?? null
                    ]);
                    throw $e;
                }
            }
        }

        $this->logger->warning("Route not found", [
            'method' => $method,
            'path' => $path
        ]);
        $this->response->setStatusCode(HttpStatusCodeEnum::HTTP_NOT_FOUND);
        $this->response->json(['message' => 'Not Found']);
    }

    public function renderTemplate(string $name, array $data=[]): void
    {
        Template::View($name.'.html', $data);
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}