<?php
declare(strict_types=1);

namespace app\controllers;

use app\core\Application;
use app\core\Request;
use app\core\Response;
use app\core\Logger;
use app\enums\HttpStatusCodeEnum;
use app\services\RegistrationService;
use app\exceptions\ValidationException;

class RegistrationController
{
    private RegistrationService $service;
    private Logger $logger;

    public function __construct()
    {
        $this->service = new RegistrationService();
        $this->logger = Application::$app->getLogger();
    }

    public function register(Request $request, Response $response): void
    {
        $data = $request->getBody();
        $this->logger->info('User registration attempt', ['email' => $data['email'] ?? null]);

        try {
            $user = $this->service->register($data);

            $this->logger->info('User successfully registered', ['user_email' => $user->getEmail()]);

            $response->setStatusCode(HttpStatusCodeEnum::HTTP_CREATED);
            $response->json([
                'message' => 'Registration successful',
                'user_email' => $user->getEmail()
            ]);
        } catch (ValidationException $e) {
            $this->logger->warning('Registration validation failed', ['error' => $e->getMessage()]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_BAD_REQUEST);
            $response->json(['error' => $e->getMessage()]);
        } catch (\Throwable $e) {
            $this->logger->error('Registration error', ['exception' => get_class($e), 'message' => $e->getMessage()]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Internal Server Error']);
        }
    }
}