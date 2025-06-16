<?php
declare(strict_types=1);

namespace app\controllers;

use app\core\Application;
use app\core\Request;
use app\core\Response;
use app\core\Logger;
use app\enums\HttpStatusCodeEnum;
use app\exceptions\ValidationException;
use app\exceptions\AuthenticationException;
use app\models\User;
use app\services\AuthenticationService;

class AuthenticationController
{
    private AuthenticationService $authService;
    private Logger $logger;

    public function __construct(
    ) {
        $this->authService = new AuthenticationService();
        $this->logger      = Application::$app->getLogger();
    }

    public function login(Request $request, Response $response): void
    {
        $data = $request->getBody();
        $this->logger->info('Login attempt', ['email' => $data['email'] ?? null]);

        try {
            $user = $this->authService->login($data);

            $this->logger->info('User logged in', ['user_id' => $user->getId()]);

            $response->json([
                'message' => 'Login successful',
                'user'    => $this->formatUserResponse($user)
            ]);
        } catch (ValidationException $e) {
            $this->logger->warning('Login validation failed', ['error' => $e->getMessage()]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_BAD_REQUEST);
            $response->json(['error' => $e->getMessage()]);
        } catch (AuthenticationException $e) {
            $this->logger->warning('Authentication failed', ['error' => $e->getMessage()]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_UNAUTHORIZED);
            $response->json(['error' => $e->getMessage()]);
        } catch (\Throwable $e) {
            $this->logger->error('Login error', ['exception' => get_class($e), 'message' => $e->getMessage()]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Internal Server Error']);
        }
    }

    public function logout(Request $request, Response $response): void
    {
        $this->authService->logout();
        $response->json(['message' => 'Logged out']);
    }

    public function me(Request $request, Response $response): void
    {
        $user = $this->authService->getCurrentUser();
        if (!$user) {
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_UNAUTHORIZED);
            $response->json(['error' => 'Not authenticated']);
            return;
        }

        $response->json($this->formatUserResponse($user));
    }

    private function formatUserResponse(User $user): array
    {
        return [
            'id'          => $user->getId(),
            'name'        => $user->getName(),
            'surname'     => $user->getSurname(),
            'email'       => $user->getEmail(),
            'phoneNumber' => $user->getPhoneNumber(),
            'avatarUrl'   => $user->getAvatarUrl(),
            'role'        => $user->getRole()
        ];
    }
}