<?php

declare(strict_types=1);

namespace app\core;

use app\enums\HttpStatusCodeEnum;
use Exception;
use RuntimeException;
use Throwable;

class Application
{
    public static Application $app;
    private Request $request;
    private Response $response;
    public Router $router;
    private Logger $logger;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        self::$app = $this;

        $this->loadConfiguration();

        $this->initLogger();

        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router(
            $this->request,
            $this->response,
            $this->logger
        );

        $this->logger->info('Application initialized');
    }

    private function loadConfiguration(): void
    {
        try {
            $configPath = __DIR__.'/../../config.json';
            if (file_exists($configPath)) {
                ConfigParser::load($configPath);
            }
        } catch (Exception $e) {
            throw new RuntimeException('Configuration loading failed: '.$e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    private function initLogger(): void
    {
        try {
            $logPath = getenv('LOG_PATH');
            $this->logger = new Logger($logPath);
        } catch (Exception $e) {
            error_log('Failed to initialize logger: ' . $e->getMessage());
            throw $e;
        }
    }

    public function run(): void
    {
        try {
            $this->logger->debug('Application started processing request', [
                'method' => $this->request->getMethod()->value,
                'path' => $this->request->getPath()
            ]);

            $this->router->resolve();
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }

    private function handleException(Throwable $e): void
    {
        $this->logger->error($e->getMessage(), [
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        $this->response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
        $this->response->json([
            'error' => 'Internal Server Error',
            'status' => 'error'
        ]);
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

    public function getLogger(): Logger
    {
        return $this->logger;
    }
}