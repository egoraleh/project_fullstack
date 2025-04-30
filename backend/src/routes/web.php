<?php

use app\controllers\UserController;
use app\controllers\PresentationController;
use app\controllers\AboutController;
use app\core\Application;

$router = Application::$app->router;

$router->post('/api/register', [UserController::class, 'register']);
$router->get('/api/presentation', [PresentationController::class, 'getView']);
$router->post('/api/presentation', [PresentationController::class, 'handleView']);
$router->get('/api/about-page', [AboutController::class, 'getView']);