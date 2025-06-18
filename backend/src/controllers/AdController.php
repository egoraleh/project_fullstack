<?php
declare(strict_types=1);

namespace app\controllers;

use app\core\Application;
use app\core\Request;
use app\core\Response;
use app\core\Logger;
use app\services\AdService;
use app\exceptions\ValidationException;
use app\enums\HttpStatusCodeEnum;
use Throwable;

class AdController
{
    private AdService $adService;
    private Logger $logger;

    public function __construct()
    {
        $this->logger = Application::$app->getLogger();
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

    public function deleteAd(Request $request, Response $response, array $params): void
    {
        $id = (int)($params['id'] ?? 0);
        $this->logger->info("Попытка удаления объявления", ['id' => $id]);

        try {
            $this->adService->deleteAd($id);
            $this->logger->info("Объявление удалено", ['id' => $id]);
            $response->json(['message' => 'Объявление удалено']);
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
}