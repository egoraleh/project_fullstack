<?php

namespace app\database\dao;

use app\database\connection\Connection;
use app\models\FavoriteAd;
use PDO;
use DateTime;

class FavoriteAdDAO
{
    private PDO $pdo;

    private const string SQL_GET_BY_USER_ID = "SELECT * FROM favorite_ads WHERE user_id = :user_id ORDER BY created_at DESC";
    private const string SQL_INSERT         = "INSERT INTO favorite_ads (user_id, ad_id) VALUES (:user_id, :ad_id)";
    private const string SQL_DELETE         = "DELETE FROM favorite_ads WHERE user_id = :user_id AND ad_id = :ad_id";
    private const string SQL_EXISTS         = "SELECT COUNT(*) FROM favorite_ads WHERE user_id = :user_id AND ad_id = :ad_id";

    public function __construct()
    {
        $this->pdo = Connection::getConnection();
    }

    /**
     * Получить все избранные объявления пользователя
     */
    public function getByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_USER_ID);
        $stmt->execute(['user_id' => $userId]);

        $favorites = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $favorites[] = new FavoriteAd(
                (int)$row['id'],
                (int)$row['user_id'],
                (int)$row['ad_id'],
                new DateTime($row['created_at'])
            );
        }

        return $favorites;
    }

    /**
     * Добавить объявление в избранное
     */
    public function save(FavoriteAd $favoriteAd): void
    {
        $stmt = $this->pdo->prepare(self::SQL_INSERT);
        $stmt->execute([
            'user_id' => $favoriteAd->getUserId(),
            'ad_id'   => $favoriteAd->getAdId()
        ]);
    }

    /**
     * Удалить объявление из избранного
     */
    public function delete(int $userId, int $adId): void
    {
        $stmt = $this->pdo->prepare(self::SQL_DELETE);
        $stmt->execute([
            'user_id' => $userId,
            'ad_id'   => $adId
        ]);
    }

    /**
     * Проверить, есть ли объявление в избранном у пользователя
     */
    public function exists(int $userId, int $adId): bool
    {
        $stmt = $this->pdo->prepare(self::SQL_EXISTS);
        $stmt->execute([
            'user_id' => $userId,
            'ad_id'   => $adId
        ]);

        return (bool)$stmt->fetchColumn();
    }
}