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
        $this->routes[MethodEnum::GET->value][$path] = $callback;
        $this->logger->debug("Route registered: GET {$path}");
    }

    public function post(string $path, array $callback): void
    {
        $this->routes[MethodEnum::POST->value][$path] = $callback;
        $this->logger->debug("Route registered: POST {$path}");
    }

    public function put(string $path, array $callback): void
    {
        $this->routes[MethodEnum::PUT->value][$path] = $callback;
        $this->logger->debug("Route registered: PUT {$path}");
    }

    public function delete(string $path, array $callback): void
    {
        $this->routes[MethodEnum::DELETE->value][$path] = $callback;
        $this->logger->debug("Route registered: DELETE {$path}");
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

        $callback = $this->routes[$method][$path] ?? null;

        if (!$callback) {
            $this->logger->warning("Route not found", [
                'method' => $method,
                'path' => $path
            ]);

            $this->response->setStatusCode(HttpStatusCodeEnum::HTTP_NOT_FOUND);
            $this->response->json(['message' => 'Not Found']);
            return;
        }

        try {
            [$class, $action] = $callback;
            $this->logger->info("Executing controller action", [
                'controller' => $class,
                'action' => $action
            ]);

            $controller = new $class();
            call_user_func([$controller, $action], $this->request, $this->response);
        } catch (Throwable $e) {
            $this->logger->error("Controller execution failed", [
                'error' => $e->getMessage(),
                'controller' => $class ?? null,
                'action' => $action ?? null
            ]);

            throw $e;
        }
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