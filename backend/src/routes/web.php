<?php

use app\controllers\AuthenticationController;
use app\controllers\RegistrationController;
use app\controllers\AboutController;
use app\core\Application;

$router = Application::$app->router;

$router->post('/api/register', [RegistrationController::class, 'register']);
$router->get('/api/about-page', [AboutController::class, 'getView']);
$router->post('/api/login',    [AuthenticationController::class, 'login']);
$router->post('/api/logout',   [AuthenticationController::class, 'logout']);
$router->get( '/api/me',       [AuthenticationController::class, 'me']);