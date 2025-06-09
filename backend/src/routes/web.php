<?php

use app\controllers\RegistrationController;
use app\controllers\UserController;
use app\controllers\AboutController;
use app\core\Application;

$router = Application::$app->router;

$router->post('/api/register', [RegistrationController::class, 'register']);
$router->get('/api/about-page', [AboutController::class, 'getView']);