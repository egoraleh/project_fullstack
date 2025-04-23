<?php

declare(strict_types=1);

use app\controllers\UserController;
use app\core\Application;

$app = new Application();

$router = $app->getRouter();

$router->get('/api/about', [UserController::class, 'about']);
$router->post('/api/register', [UserController::class, 'register']);

return $app;
