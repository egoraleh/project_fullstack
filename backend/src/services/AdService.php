<?php

namespace app\services;

use app\core\Logger;
use app\database\dao\AdDAO;
use app\models\Ad;
use app\exceptions\ValidationException;
use Throwable;

class AdService
{
    private AdDAO $adDao;
    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->adDao = new AdDAO();
        $this->logger = $logger;
    }

    /**
     * @throws ValidationException
     * @throws Throwable
     */
    public function createAd(array $data): void
    {
        $this->validateAdData($data);

        $ad = new Ad(
            0,
            $data['title'],
            (int)$data['price'],
            $data['description'],
            (int)$data['user_id'],
            (int)$data['category_id'],
            $data['address'],
            $data['image_url']
        );

        try {
            $this->adDao->save($ad);
            $this->logger->info("Объявление создано пользователем {$data['user_id']}");
        } catch (Throwable $e) {
            if (!empty($data['image_url'])) {
                $filePath = __DIR__ . '/../../public' . $data['image_url'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                    $this->logger->info("Удалено изображение после неудачного сохранения объявления", ['path' => $filePath]);
                }
            }

            throw $e;
        }
    }

    public function getAllAds(): array
    {
        return $this->adDao->getAll();
    }

    public function getAdById(int $id): ?array
    {
        return $this->adDao->get($id);
    }

    public function deleteAd(int $id): void
    {
        $this->adDao->delete($id);
        $this->logger->info("Объявление с id={$id} удалено");
    }

    /**
     * @throws ValidationException
     */
    private function validateAdData(array $data): void
    {
        $requiredFields = ['title', 'price', 'description', 'user_id', 'category_id', 'address', 'image_url'];

        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new ValidationException("Поле {$field} обязательно для заполнения");
            }
        }

        if (!is_numeric($data['price']) || $data['price'] <= 0) {
            throw new ValidationException("Цена должна быть положительным числом");
        }
    }
}