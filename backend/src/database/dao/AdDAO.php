<?php

namespace app\database\dao;

use app\core\DAOInterface;
use app\database\connection\Connection;
use app\models\Ad;
use http\Exception\InvalidArgumentException;
use PDO;

class AdDAO implements DAOInterface
{
    private PDO $pdo;

    private const SQL_GET_ALL        = "SELECT * FROM ads";
    private const SQL_GET_BY_ID      = "SELECT * FROM ads WHERE id = :id";
    private const SQL_GET_BY_USER_ID = "SELECT * FROM ads WHERE user_id = :user_id";
    private const SQL_INSERT         = "INSERT INTO ads (title, price, description, user_id, category_id, address, image_url)
                                        VALUES (:title, :price, :description, :user_id, :category_id, :address, :image_url)";
    private const SQL_UPDATE         = "UPDATE ads SET 
               title = :title, 
               price = :price, 
               description = :description, 
               user_id = :user_id, 
               category_id = :category_id, 
               address = :address, 
               image_url = :image_url 
           WHERE id = :id";
    private const SQL_DELETE         = "DELETE FROM ads WHERE id = :id";

    public function __construct()
    {
        $this->pdo = Connection::getConnection();
    }

    public function getAll(): ?array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_ALL);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get(int $id): ?array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_ID);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getByUserId(int $userId): ?array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_USER_ID);
        $stmt->execute(['user_id' => $userId]);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function save(object $ad): void
    {
        if ($ad instanceof Ad) {
            $stmt = $this->pdo->prepare(self::SQL_INSERT);

            $stmt->execute([
                'title' => $ad->getTitle(),
                'price' => $ad->getPrice(),
                'description' => $ad->getDescription(),
                'user_id' => $ad->getUserId(),
                'category_id' => $ad->getCategoryId(),
                'address' => $ad->getAddress(),
                'image_url' => $ad->getImageUrl()
            ]);
        } else {
            throw new InvalidArgumentException('Expected instance of Ad');
        }
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare(self::SQL_DELETE);
        $stmt->execute(['id' => $id]);
    }

    public function update(object $ad): void
    {
        if ($ad instanceof Ad) {
            $stmt = $this->pdo->prepare(self::SQL_UPDATE);

            $stmt->execute([
                'title' => $ad->getTitle(),
                'price' => $ad->getPrice(),
                'description' => $ad->getDescription(),
                'user_id' => $ad->getUserId(),
                'category_id' => $ad->getCategoryId(),
                'address' => $ad->getAddress(),
                'image_url' => $ad->getImageUrl(),
                'id' => $ad->getId()
            ]);
        } else {
            throw new InvalidArgumentException('Expected instance of Ad');
        }
    }
}