<?php

declare(strict_types=1);

namespace app\core;

use app\enums\MethodEnum;
use app\enums\HttpStatusCodeEnum;

class Router
{
    private array $routes = [];

    public function __construct(
        private readonly Request $request,
        private readonly Response $response
    ) {}

    public function get(string $path, array $callback): void
    {
        $this->routes[MethodEnum::GET->value][$path] = $callback;
    }

    public function post(string $path, array $callback): void
    {
        $this->routes[MethodEnum::POST->value][$path] = $callback;
    }

    public function put(string $path, array $callback): void
    {
        $this->routes[MethodEnum::PUT->value][$path] = $callback;
    }

    public function delete(string $path, array $callback): void
    {
        $this->routes[MethodEnum::DELETE->value][$path] = $callback;
    }

    public function resolve(): void
    {
        $method = $this->request->getMethod()->value;
        $path = $this->request->getPath();
        error_log("METHOD: $method, PATH: $path");
        error_log("ROUTES POST: " . json_encode(array_keys($this->routes[MethodEnum::POST->value])));

        $callback = $this->routes[$method][$path] ?? null;

        if (!$callback) {
            $this->response->setStatusCode(HttpStatusCodeEnum::HTTP_NOT_FOUND);
            $this->response->json(['message' => 'Not Found']);
            return;
        }

        [$class, $action] = $callback;
        $controller = new $class();
        call_user_func([$controller, $action], $this->request, $this->response);
    }
}
