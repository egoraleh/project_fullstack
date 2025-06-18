<?php

namespace app\services;

use app\core\Logger;
use app\database\dao\AdDAO;
use app\database\dao\FavoriteAdDAO;
use app\exceptions\ForbiddenException;
use app\exceptions\NotFoundException;
use app\models\Ad;
use app\exceptions\ValidationException;
use app\models\FavoriteAd;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Throwable;

class AdService
{
    private AdDAO $adDao;
    private FavoriteAdDAO $favoriteAdDao;
    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->adDao = new AdDAO();
        $this->favoriteAdDao = new FavoriteAdDAO();
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

    /**
     * @throws ValidationException
     * @throws ForbiddenException
     * @throws Throwable
     */
    public function updateAd(int $adId, int $userId, array $data): void
    {
        $existingAd = $this->adDao->get($adId);

        if (!$existingAd) {
            throw new ValidationException("Объявление не найдено");
        }

        if ((int)$existingAd['user_id'] !== $userId) {
            throw new ForbiddenException("Вы не можете изменить это объявление");
        }

        $updatedAd = new Ad(
            $adId,
            $data['title'] ?? $existingAd['title'],
            (int)$data['price'] ?? $existingAd['price'],
            $data['description'] ?? $existingAd['description'],
            (int)$existingAd['user_id'],
            (int)$data['category_id'] ?? $existingAd['category_id'],
            $data['address'] ?? $existingAd['address'],
            $data['image_url'] ?? $existingAd['image_url']
        );

        try {
            $this->adDao->update($updatedAd);
            $this->logger->info("Объявление обновлено пользователем id={$userId}", ['ad_id' => $adId]);
        } catch (Throwable $e) {
            if (!empty($data['image_url']) && $data['image_url'] !== $existingAd['image_url']) {
                $filePath = __DIR__ . '/../../public' . $data['image_url'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                    $this->logger->info("Удалено изображение после неудачного обновления объявления", ['path' => $filePath]);
                }
            }

            throw $e;
        }
    }


    public function getAllAds(): array
    {
        return $this->adDao->getAll();
    }

    public function getAdsByUserId(int $userId): array
    {
        return $this->adDao->getByUserId($userId) ?? [];
    }

    public function getAdById(int $id): ?array
    {
        return $this->adDao->get($id);
    }

    /**
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    public function deleteAd(int $adId, int $userId): void
    {
        $ad = $this->adDao->get($adId);

        if (!$ad) {
            throw new NotFoundException("Объявление не найдено");
        }

        if ($ad['user_id'] !== $userId) {
            throw new ForbiddenException("Вы не можете удалить это объявление");
        }

        $filePath = __DIR__ . '/../../public' . $ad['image_url'];
        if (!empty($filePath) && file_exists($filePath)) {
            try {
                unlink($filePath);
                $this->logger->info("Изображение объявления id={$adId} удалено: {$ad['image_url']}");
            } catch (Throwable $e) {
                $this->logger->error("Не удалось удалить изображение объявления id={$adId}: " . $e->getMessage());
                throw new FileNotFoundException();
            }
        }

        $this->adDao->delete($adId);
        $this->logger->info("Объявление с id={$adId} удалено пользователем id={$userId}");
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

    /**
     * @throws ValidationException
     * @throws Throwable
     */
    public function addFavorite(int $userId, int $adId): void
    {
        if ($userId <= 0 || $adId <= 0) {
            throw new ValidationException("Неверные данные для добавления в избранное");
        }

        $favorites = $this->favoriteAdDao->getByUserId($userId);
        foreach ($favorites ?? [] as $fav) {
            if ($fav['ad_id'] === $adId) {
                $this->logger->info("Объявление уже в избранном", ['user_id' => $userId, 'ad_id' => $adId]);
                return;
            }
        }

        $favoriteAd = new FavoriteAd(null, $userId, $adId, null);
        $this->favoriteAdDao->save($favoriteAd);
        $this->logger->info("Добавлено объявление в избранное", ['user_id' => $userId, 'ad_id' => $adId]);
    }

    /**
     * @throws ValidationException
     * @throws Throwable
     */
    public function removeFavorite(int $userId, int $adId): void
    {
        if ($userId <= 0 || $adId <= 0) {
            throw new ValidationException("Неверные данные для удаления из избранного");
        }

        $favorites = $this->favoriteAdDao->getByUserId($userId);
        if ($favorites === null) {
            $this->logger->info("Избранных объявлений для пользователя не найдено", ['user_id' => $userId]);
            return;
        }

        foreach ($favorites as $fav) {
            if ($fav['ad_id'] === $adId) {
                $this->favoriteAdDao->delete($fav['id']);
                $this->logger->info("Удалено объявление из избранного", ['user_id' => $userId, 'ad_id' => $adId]);
                return;
            }
        }

        $this->logger->info("Объявление не найдено в избранном при попытке удаления", ['user_id' => $userId, 'ad_id' => $adId]);
    }

    public function isFavorite(int $userId, int $adId): bool
    {
        if ($userId <= 0 || $adId <= 0) {
            return false;
        }

        $favorites = $this->favoriteAdDao->getByUserId($userId);
        if ($favorites === null) {
            return false;
        }

        foreach ($favorites as $fav) {
            if ($fav['ad_id'] === $adId) {
                return true;
            }
        }

        return false;
    }

    public function getFavoriteAdsByUserId(int $userId): array
    {
        $favoriteAdsRecords = $this->favoriteAdDao->getByUserId($userId);
        if (!$favoriteAdsRecords) {
            return [];
        }

        $favoriteAds = [];
        foreach ($favoriteAdsRecords as $fav) {
            $ad = $this->adDao->get($fav['ad_id']);
            if ($ad) {
                $favoriteAds[] = $ad;
            }
        }

        return $favoriteAds;
    }
}