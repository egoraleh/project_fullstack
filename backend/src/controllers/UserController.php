<?php
declare(strict_types=1);

namespace app\controllers;

use app\core\Application;
use app\core\Logger;
use app\core\Request;
use app\core\Response;
use app\models\User;
use app\database\dao\UserDAO;

class UserController
{
    private Logger $logger;

    public function __construct()
    {
        $this->logger = Application::$app->getLogger();
    }

    public function register(Request $request, Response $response): void
    {
        $data = $request->getBody();

        $this->logger->info('User registration attempt', [
            'email' => $data['email'] ?? null
        ]);

        if (empty($data['email'])) {
            $this->logger->warning('Registration validation failed: empty email');
            $response->json(['error' => 'Email is required']);
            return;
        }

        if (empty($data['password'])) {
            $this->logger->warning('Registration validation failed: empty password');
            $response->json(['error' => 'Password is required']);
            return;
        }

        $userDao = new UserDAO();
        $existingUser = $userDao->getByEmail($data['email']);

        if ($existingUser) {
            $this->logger->notice('Duplicate registration attempt', [
                'email' => $data['email']
            ]);
            $response->json(['error' => 'Email already exists']);
            return;
        }

        $user = new User(
            0,
            $data['first_name'] ?? '',
            $data['last_name'] ?? '',
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            'basic',
            $data['phone'] ?? '',
            '/uploads/avatars/default.png'
        );

        $userDao->save($user);

        $this->logger->info('User successfully registered', [
            'email' => $data['email'],
        ]);

        $response->json([
            'message' => 'Registration successful',
            'user_id' => $user->getId()
        ]);
    }
}