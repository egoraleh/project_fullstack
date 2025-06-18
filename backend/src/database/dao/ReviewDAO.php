<?php

namespace app\database\dao;

use app\core\DAOInterface;
use app\database\connection\Connection;
use app\models\Review;
use http\Exception\InvalidArgumentException;
use PDO;

class ReviewDAO implements DAOInterface
{
    private PDO $pdo;

    private const SQL_GET_BY_ID            = "SELECT * FROM reviews WHERE id = :id";
    private const SQL_GET_BY_RECEIVER_ID   = "SELECT * FROM reviews WHERE receiver_id = :receiver_id ORDER BY created_at DESC";
    private const SQL_GET_BY_AD_ID         = "SELECT * FROM reviews WHERE ad_id = :ad_id ORDER BY created_at DESC";
    private const SQL_INSERT               = "INSERT INTO reviews (author_id, receiver_id, ad_id, created_at, text, rating)
                                              VALUES (:author_id, :receiver_id, :ad_id, :created_at, :text, :rating)";
    private const SQL_UPDATE               = "UPDATE reviews SET
                author_id = :author_id, 
                receiver_id = :receiver_id, 
                ad_id = :ad_id, 
                created_at = :created_at, 
                text = :text, 
                rating = :rating
            WHERE id = :id";
    private const SQL_DELETE               = "DELETE FROM reviews WHERE id = :id";

    public function __construct()
    {
        $this->pdo = Connection::getConnection();
    }

    public function get(int $id): ?array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_ID);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getByReceiverId(int $receiverId): ?array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_RECEIVER_ID);
        $stmt->execute(['receiver_id' => $receiverId]);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getByAdId(int $adId): ?array {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_AD_ID);
        $stmt->execute(['ad_id' => $adId]);
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function save(object $review): void
    {
        if ($review instanceof Review) {
            $stmt = $this->pdo->prepare(self::SQL_INSERT);

            $stmt->execute([
                'author_id' => $review->getAuthorId(),
                'receiver_id' => $review->getReceiverId(),
                'ad_id' => $review->getAdId(),
                'created_at' => $review->getCreatedAt()->format('Y-m-d H:i:s'),
                'text' => $review->getText(),
                'rating' => $review->getRating()
            ]);
        } else {
            throw new InvalidArgumentException('Expected instance of Review');
        }
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare(self::SQL_DELETE);
        $stmt->execute(['id' => $id]);
    }

    public function update(object $review): void
    {
        if ($review instanceof Review) {
            $stmt = $this->pdo->prepare(self::SQL_UPDATE);

            $stmt->execute([
                'author_id' => $review->getAuthorId(),
                'receiver_id' => $review->getReceiverId(),
                'ad_id' => $review->getAdId(),
                'created_at' => $review->getCreatedAt()->format('Y-m-d H:i:s'),
                'text' => $review->getText(),
                'rating' => $review->getRating(),
                'id' => $review->getId()
            ]);
        } else {
            throw new InvalidArgumentException('Expected instance of Review');
        }
    }
}