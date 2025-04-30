<?php

namespace app\database\dao;

use app\database\connection\Connection;
use app\models\Ad;
use PDO;

class AdDAO
{
    private PDO $pdo;

    private const string SQL_GET_ALL        = "SELECT * FROM ads";
    private const string SQL_GET_BY_ID      = "SELECT * FROM ads WHERE id = :id";
    private const string SQL_GET_BY_USER_ID = "SELECT * FROM ads WHERE user_id = :user_id";
    private const string SQL_INSERT         = "INSERT INTO ads (title, price, description, user_id, category_id, address)
                                        VALUES (:title, :price, :description, :user_id, :category_id, :address)";
    private const string SQL_DELETE         = "DELETE FROM ads WHERE id = :id";

    public function __construct()
    {
        $this->pdo = Connection::getConnection();
    }

    /**
     * Получить все объявления
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_ALL);
        $stmt->execute();

        $ads = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ads[] = $this->mapRowToAd($row);
        }

        return $ads;
    }

    /**
     * Получить объявление по ID
     */
    public function getById(int $id): ?Ad
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_ID);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToAd($row) : null;
    }

    /**
     * Получить объявления по user_id
     */
    public function getByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_USER_ID);
        $stmt->execute(['user_id' => $userId]);

        $ads = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $ads[] = $this->mapRowToAd($row);
        }

        return $ads;
    }

    /**
     * Добавить новое объявление
     */
    public function save(Ad $ad): void
    {
        $stmt = $this->pdo->prepare(self::SQL_INSERT);

        $stmt->execute([
            'title'       => $ad->getTitle(),
            'price'       => $ad->getPrice(),
            'description' => $ad->getDescription(),
            'user_id'     => $ad->getUserId(),
            'category_id' => $ad->getCategoryId(),
            'address'     => $ad->getAddress()
        ]);
    }

    /**
     * Удалить объявление по ID
     */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare(self::SQL_DELETE);
        $stmt->execute(['id' => $id]);
    }

    /**
     * Приватный метод для преобразования строки из БД в объект Ad
     */
    private function mapRowToAd(array $row): Ad
    {
        return new Ad(
            (int)$row['id'],
            $row['title'],
            (int)$row['price'],
            $row['description'],
            (int)$row['user_id'],
            (int)$row['category_id'],
            $row['address']
        );
    }
}