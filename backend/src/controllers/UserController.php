<?php

declare(strict_types=1);

namespace app\controllers;

use app\core\Request;
use app\core\Response;

class UserController
{
    public function about(Request $request, Response $response): void
    {
        $response->json([
            'title' => 'О проекте',
            'description' => 'Это онлайн-доска объявлений, где пользователи могут размещать и искать объявления о продаже товаров и услуг.',
        ]);
    }

    public function register(Request $request, Response $response): void
    {
        echo "Пользователь зарегистрирован";
        $data = $request->getBody();
        $response->json([
            'message' => 'Пользователь успешно зарегистрирован',
            'data' => $data,
        ]);
    }
}
