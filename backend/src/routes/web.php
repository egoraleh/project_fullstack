<?php

use app\controllers\AdController;
use app\controllers\AuthenticationController;
use app\controllers\CategoryController;
use app\controllers\RegistrationController;
use app\controllers\AboutController;
use app\controllers\UserController;
use app\core\Application;

$router = Application::$app->router;

$router->post('/api/register',   [RegistrationController::class, 'register']);
$router->get('/api/about-page',  [AboutController::class, 'getView']);
$router->post('/api/login',      [AuthenticationController::class, 'login']);
$router->post('/api/logout',     [AuthenticationController::class, 'logout']);
$router->get( '/api/me',         [AuthenticationController::class, 'me']);
$router->post('/api/ads/new',        [AdController::class, 'createAd']);
$router->get('/api/ads',         [AdController::class, 'getAllAds']);
$router->get('/api/ads/{id:\d+}',    [AdController::class, 'getAd']);
$router->post('/api/ads/{id:\d+}', [AdController::class, 'deleteAd']);
$router->post('/api/ads/upload-image', [AdController::class, 'uploadImage']);
$router->get('/api/user/avatar/{id:\d+}', [UserController::class, 'getAvatar']);
$router->post('/api/user/{id:\d+}', [UserController::class, 'updateUser']);
$router->get('/api/user/ads/{id:\d+}', [AdController::class, 'getAdsByUser']);
$router->get('/api/ad/{id:\d+}/image', [AdController::class, 'getAdImage']);
$router->get('/api/categories/{id:\d+}', [CategoryController::class, 'getCategoryById']);