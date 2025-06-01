<?php

namespace app\database\dao;

use app\core\DAOInterface;
use app\database\connection\Connection;
use app\models\FavoriteAd;
use DateMalformedStringException;
use http\Exception\InvalidArgumentException;
use PDO;
use DateTime;

class FavoriteAdDAO implements DAOInterface
{
    private PDO $pdo;

    private const string SQL_GET_BY_USER_ID = "SELECT * FROM favorite_ads WHERE user_id = :user_id ORDER BY created_at DESC";
    private const string SQL_GET            = "SELECT * FROM favorite_ads WHERE id = :id ORDER BY created_at DESC";
    private const string SQL_INSERT         = "INSERT INTO favorite_ads (user_id, ad_id) VALUES (:user_id, :ad_id)";
    private const string SQL_UPDATE         = "UPDATE favorite_ads SET ad_id = :ad_id, user_id = :user_id WHERE id = :id";
    private const string SQL_DELETE         = "DELETE FROM favorite_ads WHERE id = :id";

    public function __construct()
    {
        $this->pdo = Connection::getConnection();
    }

    public function getByUserId(int $id): ?array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_USER_ID);
        $stmt->execute(['user_id' => $id]);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function get(int $id): ?array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function save(object $entity): void
    {
        if ($entity instanceof FavoriteAd) {
            $stmt = $this->pdo->prepare(self::SQL_INSERT);
            $stmt->execute([
                'user_id' => $entity->getUserId(),
                'ad_id' => $entity->getAdId(),
                'created_at' => $entity->getCreatedAt()
            ]);
        } else {
            throw new InvalidArgumentException('Expected instance of FavoriteAd');
        }
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare(self::SQL_DELETE);
        $stmt->execute(['id' => $id]);
    }

    public function update(object $entity): void
    {
        if ($entity instanceof FavoriteAd) {
            $stmt = $this->pdo->prepare(self::SQL_UPDATE);
            $stmt->execute([
                'ad_id' => $entity->getAdId(),
                'user_id' => $entity->getUserId(),
                'id' => $entity->getId()
            ]);
        } else {
            throw new InvalidArgumentException('Expected instance of FavoriteAd');
        }
    }
}