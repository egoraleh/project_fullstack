<?php

declare(strict_types=1);

namespace app\controllers;

use app\core\Request;
use app\core\Response;
use app\models\User;
use app\database\dao\UserDAO;

class UserController
{
    public function register(Request $request, Response $response): void
    {
        $data = $request->getBody();
        var_dump($data); // Чтобы увидеть, что приходит в запросе

        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        $userDao = new UserDAO();
        $existingUser = $userDao->getByEmail($email);

        if ($existingUser) {
            $response->json(['error' => 'Пользователь с таким email уже существует.']);
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $user = new User(
            0,
            '',
            '',
            $email,
            $hashedPassword,
            'basic',
            '',
            '/uploads/avatars/default.png'
        );

        $userDao->save($user);

        $response->json([
            'message' => 'Пользователь успешно зарегистрирован'
        ]);
    }
}
