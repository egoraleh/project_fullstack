<?php
declare(strict_types=1);

namespace app\controllers;

use app\core\Application;
use app\core\Logger;
use app\core\Request;
use app\core\Response;
use app\database\dao\UserDAO;
use app\enums\HttpStatusCodeEnum;
use app\models\User;

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
            $filePath = __DIR__ . "/../../public/uploads/avatars/default.jpg";
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

    public function getUserById(Request $request, Response $response, array $params): void
    {
        $userId = (int)($params['id'] ?? 0);

        if ($userId === 0) {
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_BAD_REQUEST);
            $response->json(['error' => 'Некорректный ID пользователя']);
            return;
        }

        try {
            $user = $this->userDAO->get($userId);

            if (!$user) {
                $response->setStatusCode(HttpStatusCodeEnum::HTTP_NOT_FOUND);
                $response->json(['error' => 'Пользователь не найден']);
                return;
            }

            unset($user['password'], $user['role']);

            $response->json($user);

        } catch (\Throwable $e) {
            $this->logger->error('Ошибка при получении пользователя по ID', [
                'exception' => get_class($e),
                'message'   => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Не удалось получить пользователя']);
        }
    }

    public function updateUser(Request $request, Response $response, array $params): void
    {
        $userId = (int)($params['id'] ?? 0);

        if ($userId === 0) {
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_BAD_REQUEST);
            $response->json(['error' => 'Некорректный ID пользователя']);
            return;
        }

        $userData = $_POST;
        $existingUserData = $this->userDAO->get($userId);

        if (!$existingUserData) {
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_NOT_FOUND);
            $response->json(['error' => 'Пользователь не найден']);
            return;
        }

        if (isset($userData['email'])) {
            $existingEmailUser = $this->userDAO->getByEmail($userData['email']);
            if ($existingEmailUser && $existingEmailUser['id'] != $userId) {
                $response->setStatusCode(HttpStatusCodeEnum::HTTP_CONFLICT);
                $response->json(['error' => 'Email уже используется']);
                return;
            }
        }

        if (isset($userData['phone_number'])) {
            $existingPhoneUser = $this->userDAO->getByPhoneNumber($userData['phone_number']);
            if ($existingPhoneUser && $existingPhoneUser['id'] != $userId) {
                $response->setStatusCode(HttpStatusCodeEnum::HTTP_CONFLICT);
                $response->json(['error' => 'Номер телефона уже используется']);
                return;
            }
        }

        $avatarPath = $existingUserData['avatar_path'];

        $user = new User(
            $userId,
            $userData['name'] ?? $existingUserData['name'],
            $userData['surname'] ?? $existingUserData['surname'],
            $userData['email'] ?? $existingUserData['email'],
            $existingUserData['password'],
            $existingUserData['role'],
            $userData['phone_number'] ?? $existingUserData['phone_number'],
            $avatarPath
        );

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if ($avatarPath && $avatarPath !== 'uploads/avatars/default.jpg') {
                $oldAvatarFile = __DIR__ . '/../../public/' . $avatarPath;
                if (file_exists($oldAvatarFile)) {
                    unlink($oldAvatarFile);
                }
            }

            $fileExtension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $fileName = $userId . '.' . $fileExtension;
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
                $avatarPath = 'uploads/avatars/' . $fileName;
            } else {
                $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
                $response->json(['error' => 'Ошибка при загрузке аватара']);
                return;
            }
        }

        $user->setAvatarUrl($avatarPath);

        try {
            $this->userDAO->update($user);
            $response->json(['success' => true]);
        } catch (\Throwable $e) {
            $this->logger->error('Ошибка при обновлении пользователя', [
                'exception' => get_class($e),
                'message'   => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Не удалось обновить пользователя']);
        }
    }
}