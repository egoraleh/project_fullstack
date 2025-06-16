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
        } catch (\Throwable $e) {
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
        } catch (\Throwable $e) {
            $this->logger->error("Ошибка при получении объявлений", [
                'exception' => get_class($e),
                'message'   => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Internal Server Error']);
        }
    }

    public function getAd(Request $request, Response $response, int $id): void
    {
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
        } catch (\Throwable $e) {
            $this->logger->error("Ошибка при получении объявления", [
                'exception' => get_class($e),
                'message'   => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Internal Server Error']);
        }
    }

    public function deleteAd(Request $request, Response $response, int $id): void
    {
        $this->logger->info("Попытка удаления объявления", ['id' => $id]);

        try {
            $this->adService->deleteAd($id);
            $this->logger->info("Объявление удалено", ['id' => $id]);
            $response->json(['message' => 'Объявление удалено']);
        } catch (\Throwable $e) {
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

        $uploadDir = __DIR__.'/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imageUrl = '/uploads/' . $fileName;
            $this->logger->info("Файл загружен", ['image_url' => $imageUrl]);
            $response->json(['imageUrl' => $imageUrl]);
        } else {
            $this->logger->error("Ошибка при загрузке файла");
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Ошибка при загрузке файла']);
        }
    }
}