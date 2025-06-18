<?php
declare(strict_types=1);

namespace app\controllers;

use app\core\Application;
use app\core\Request;
use app\core\Response;
use app\core\Logger;
use app\exceptions\ForbiddenException;
use app\services\AdService;
use app\exceptions\ValidationException;
use app\enums\HttpStatusCodeEnum;
use app\services\AuthenticationService;
use Throwable;

class AdController
{
    private AdService $adService;
    private AuthenticationService $authenticationService;
    private Logger $logger;

    public function __construct()
    {
        $this->logger = Application::$app->getLogger();
        $this->authenticationService = new AuthenticationService();
        $this->adService = new AdService($this->logger);
    }

    public function createAd(Request $request, Response $response): void
    {
        $data = $request->getBody();
        $this->logger->info("Попытка создания объявления", ['title' => $data['title'] ?? null]);

        try {
            $this->adService->createAd($data);
            $this->logger->info("Объявление создано", ['title' => $data['title']]);

            $response->setStatusCode(HttpStatusCodeEnum::HTTP_CREATED);
            $response->json(['message' => 'Объявление успешно добавлено']);
        } catch (ValidationException $e) {
            $this->logger->warning("Ошибка валидации при создании объявления", ['error' => $e->getMessage()]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_BAD_REQUEST);
            $response->json(['error' => $e->getMessage()]);
        } catch (Throwable $e) {
            $this->logger->error("Ошибка при создании объявления", [
                'exception' => get_class($e),
                'message'   => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Internal Server Error']);
        }
    }

    public function getAllAds(Request $request, Response $response): void
    {
        $this->logger->info("Получение списка всех объявлений");

        try {
            $ads = $this->adService->getAllAds();
            $response->json($ads);
        } catch (Throwable $e) {
            $this->logger->error("Ошибка при получении объявлений", [
                'exception' => get_class($e),
                'message'   => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Internal Server Error']);
        }
    }

    public function getAdsByUser(Request $request, Response $response, array $params): void
    {
        $userId = (int)($params['id'] ?? 0);
        $this->logger->info("Получение объявлений пользователя", ['user_id' => $userId]);

        try {
            $ads = $this->adService->getAdsByUserId($userId);

            $response->json($ads);
        } catch (Throwable $e) {
            $this->logger->error("Ошибка при получении объявлений пользователя", [
                'exception' => get_class($e),
                'message'   => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Internal Server Error']);
        }
    }

    public function getAd(Request $request, Response $response, array $params): void
    {
        $id = (int)($params['id'] ?? 0);
        $this->logger->info("Получение объявления", ['id' => $id]);

        try {
            $ad = $this->adService->getAdById($id);

            if ($ad) {
                $response->json($ad);
            } else {
                $this->logger->warning("Объявление не найдено", ['id' => $id]);
                $response->setStatusCode(HttpStatusCodeEnum::HTTP_NOT_FOUND);
                $response->json(['error' => 'Объявление не найдено']);
            }
        } catch (Throwable $e) {
            $this->logger->error("Ошибка при получении объявления", [
                'exception' => get_class($e),
                'message'   => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Internal Server Error']);
        }
    }

    public function updateAd(Request $request, Response $response, array $params): void
    {
        $id = (int)($params['id'] ?? 0);
        $currentUser = $this->authenticationService->getCurrentUser();

        if (!$currentUser) {
            $this->logger->warning("Попытка обновления без авторизации", ['ad_id' => $id]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_UNAUTHORIZED);
            $response->json(['error' => 'Вы не авторизованы']);
            return;
        }

        $this->logger->info("Попытка обновления объявления", [
            'ad_id'   => $id,
            'user_id' => $currentUser->getId()
        ]);

        $data = $request->getBody();

        try {
            $this->adService->updateAd($id, $currentUser->getId(), $data);

            $this->logger->info("Объявление обновлено", ['ad_id' => $id]);
            $response->json(['message' => 'Объявление успешно обновлено']);
        } catch (ForbiddenException $e) {
            $this->logger->warning("Обновление отклонено — не владелец", [
                'ad_id'   => $id,
                'user_id' => $currentUser->getId()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_FORBIDDEN);
            $response->json(['error' => $e->getMessage()]);
        } catch (ValidationException $e) {
            $this->logger->warning("Ошибка валидации при обновлении объявления", ['error' => $e->getMessage()]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_BAD_REQUEST);
            $response->json(['error' => $e->getMessage()]);
        } catch (Throwable $e) {
            $this->logger->error("Ошибка при обновлении объявления", [
                'exception' => get_class($e),
                'message'   => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Ошибка сервера']);
        }
    }

    public function deleteAd(Request $request, Response $response, array $params): void
    {
        $id = (int)($params['id'] ?? 0);
        $currentUser = $this->authenticationService->getCurrentUser();

        if (!$currentUser) {
            $this->logger->warning("Попытка удаления без авторизации", ['ad_id' => $id]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_UNAUTHORIZED);
            $response->json(['error' => 'Вы не авторизованы']);
            return;
        }

        $this->logger->info("Попытка удаления объявления", [
            'ad_id'   => $id,
            'user_id' => $currentUser->getId()
        ]);

        try {
            $this->adService->deleteAd($id, $currentUser->getId());

            $this->logger->info("Объявление удалено", ['ad_id' => $id, 'user_id' => $currentUser->getId()]);
            $response->json(['message' => 'Объявление удалено']);
        } catch (ForbiddenException $e) {
            $this->logger->warning("Удаление отклонено — не владелец", [
                'ad_id'   => $id,
                'user_id' => $currentUser->getId()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_FORBIDDEN);
            $response->json(['error' => $e->getMessage()]);
        } catch (Throwable $e) {
            $this->logger->error("Ошибка при удалении объявления", [
                'exception' => get_class($e),
                'message'   => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Ошибка при удалении']);
        }
    }

    public function uploadImage(Request $request, Response $response): void
    {
        $this->logger->info("Попытка загрузки изображения");

        if (!isset($_FILES['image'])) {
            $this->logger->warning("Файл не передан");
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_BAD_REQUEST);
            $response->json(['error' => 'Файл не передан']);
            return;
        }

        $uploadDir = __DIR__.'/../../public/uploads/ads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imageUrl = '/uploads/ads/' . $fileName;
            $this->logger->info("Файл загружен", ['image_url' => $imageUrl]);
            $response->json(['imageUrl' => $imageUrl]);
        } else {
            $this->logger->error("Ошибка при загрузке файла");
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Ошибка при загрузке файла']);
        }
    }

    public function getAdImage(Request $request, Response $response, array $params): void
    {
        $id = (int)($params['id'] ?? 0);
        $this->logger->info("Получение изображения объявления", ['ad_id' => $id]);

        try {
            $ad = $this->adService->getAdById($id);

            if (!$ad) {
                $this->logger->warning("Объявление не найдено для изображения", ['ad_id' => $id]);
                $response->setStatusCode(HttpStatusCodeEnum::HTTP_NOT_FOUND);
                $response->json(['error' => 'Объявление не найдено']);
                return;
            }

            if (empty($ad['image_url'])) {
                $this->logger->warning("Изображение не прикреплено к объявлению", ['ad_id' => $id]);
                $response->setStatusCode(HttpStatusCodeEnum::HTTP_NOT_FOUND);
                $response->json(['error' => 'Изображение не найдено']);
                return;
            }

            $filePath = __DIR__ . '/../../public' . $ad['image_url'];

            if (!file_exists($filePath)) {
                $this->logger->error("Файл изображения не найден на сервере", ['path' => $filePath]);
                $response->setStatusCode(HttpStatusCodeEnum::HTTP_NOT_FOUND);
                $response->json(['error' => 'Файл изображения не найден']);
                return;
            }

            header('Content-Type: image/jpeg');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
        } catch (Throwable $e) {
            $this->logger->error("Ошибка при получении изображения объявления", [
                'exception' => get_class($e),
                'message'   => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Ошибка сервера при получении изображения']);
        }
    }

    public function getFavoriteStatus(Request $request, Response $response, array $params): void
    {
        $adId = (int)($params['id'] ?? 0);
        $user = $this->authenticationService->getCurrentUser();

        if (!$user) {
            $this->logger->warning("Попытка получения объявления без авторизации", ['ad_id' => $adId]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_UNAUTHORIZED);
            $response->json(['error' => 'Вы не авторизованы']);
            return;
        }

        try {
            $isFavorite = $this->adService->isFavorite($user->getId(), $adId);
            $response->json(['isFavorite' => $isFavorite]);
        } catch (Throwable $e) {
            $this->logger->error("Ошибка при проверке статуса избранного", [
                'exception' => get_class($e),
                'message' => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Ошибка сервера']);
        }
    }

    public function addToFavorites(Request $request, Response $response): void
    {
        $user = $this->authenticationService->getCurrentUser();

        if (!$user) {
            $this->logger->warning("Попытка добавления в избранное без авторизации");
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_UNAUTHORIZED);
            $response->json(['error' => 'Вы не авторизованы']);
            return;
        }

        $data = $request->getBody();
        $adId = isset($data['adId']) ? (int)$data['adId'] : 0;

        if ($adId <= 0) {
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_BAD_REQUEST);
            $response->json(['error' => 'Неверный ID объявления']);
            return;
        }

        try {
            $this->adService->addFavorite($user->getId(), $adId);
            $response->json(['message' => 'Добавлено в избранное']);
        } catch (Throwable $e) {
            $this->logger->error("Ошибка при добавлении в избранное", [
                'exception' => get_class($e),
                'message' => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Ошибка сервера']);
        }
    }

    public function removeFromFavorites(Request $request, Response $response): void
    {
        $user = $this->authenticationService->getCurrentUser();

        if (!$user) {
            $this->logger->warning("Попытка удаления из избранного без авторизации");
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_UNAUTHORIZED);
            $response->json(['error' => 'Вы не авторизованы']);
            return;
        }

        $data = $request->getBody();
        $adId = isset($data['adId']) ? (int)$data['adId'] : 0;

        if ($adId <= 0) {
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_BAD_REQUEST);
            $response->json(['error' => 'Неверный ID объявления']);
            return;
        }

        try {
            $this->adService->removeFavorite($user->getId(), $adId);
            $response->json(['message' => 'Удалено из избранного']);
        } catch (Throwable $e) {
            $this->logger->error("Ошибка при удалении из избранного", [
                'exception' => get_class($e),
                'message' => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Ошибка сервера']);
        }
    }

    public function getFavoriteAds(Request $request, Response $response): void
    {
        $user = $this->authenticationService->getCurrentUser();

        if (!$user) {
            $this->logger->warning("Попытка получения избранных объявлений без авторизации");
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_UNAUTHORIZED);
            $response->json(['error' => 'Вы не авторизованы']);
            return;
        }

        try {
            $favoriteAds = $this->adService->getFavoriteAdsByUserId($user->getId());
            $response->json($favoriteAds);
        } catch (Throwable $e) {
            $this->logger->error("Ошибка при получении избранных объявлений", [
                'exception' => get_class($e),
                'message' => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Ошибка сервера']);
        }
    }
}