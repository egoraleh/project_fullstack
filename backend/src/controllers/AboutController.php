<?php

declare(strict_types=1);

namespace app\controllers;

use app\core\Application;

class AboutController
{
    public function getView(): void
    {
        echo json_encode([
            'title' => 'О проекте',
            'description' => 'Наш сервис позволяет пользователям размещать объявления для покупки и продажи товаров и услуг.'
        ]);
    }
}
