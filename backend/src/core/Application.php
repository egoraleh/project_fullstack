<?php

declare(strict_types=1);

namespace app\core;

class Application
{
    public static Application $app;
    private Request $request;
    private Response $response;
    public Router $router;

    public function __construct()
    {
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
    }

    public function run(): void
    {
        $this->router->resolve();
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

}