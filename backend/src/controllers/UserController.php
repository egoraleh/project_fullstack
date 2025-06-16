<?php
declare(strict_types=1);

namespace app\controllers;

use app\core\Application;
use app\core\Logger;
use app\core\Request;
use app\core\Response;
use app\database\dao\UserDAO;
use app\enums\HttpStatusCodeEnum;

class UserController
{
    private Logger $logger;
    private UserDAO $userDAO;

    public function __construct()
    {
        $this->logger = Application::$app->getLogger();
        $this->userDAO = new UserDAO();
    }

    public function getAvatar(Request $request, Response $response, array $params): void
    {
        $userId = (int)($params['id'] ?? 0);

        if ($userId === 0) {
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_BAD_REQUEST);
            $response->json(['error' => 'Некорректный ID пользователя']);
            return;
        }

        $filePath = __DIR__ . "/../../public/" . $this->userDAO->getAvatarPath($userId);

        if ($filePath === null || !file_exists($filePath)) {
            $filePath = __DIR__ . "/../../public/uploads/default.jpg";
            if (!file_exists($filePath)) {
                $response->setStatusCode(HttpStatusCodeEnum::HTTP_NOT_FOUND);
                $response->json(['error' => 'Аватар не найден']);
                return;
            }
        }

        header('Content-Type: image/jpeg');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
}