<?php

namespace app\database\dao;

use app\database\connection\Connection;
use app\models\Review;
use DateMalformedStringException;
use DateTime;
use PDO;

class ReviewDAO
{
    private PDO $pdo;

    private const string SQL_GET_BY_ID            = "SELECT * FROM reviews WHERE id = :id";
    private const string SQL_GET_BY_RECEIVER_ID   = "SELECT * FROM reviews WHERE receiver_id = :receiver_id ORDER BY created_at DESC";
    private const string SQL_INSERT               = "INSERT INTO reviews (author_id, receiver_id, ad_id, created_at, text, rating)
                                              VALUES (:author_id, :receiver_id, :ad_id, :created_at, :text, :rating)";
    private const string SQL_DELETE               = "DELETE FROM reviews WHERE id = :id";

    public function __construct()
    {
        $this->pdo = Connection::getConnection();
    }

    /**
     * Получить отзыв по ID
     */
    public function getById(int $id): ?Review
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_ID);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToReview($row) : null;
    }

    /**
     * Получить все отзывы о пользователе (receiver_id)
     * @throws DateMalformedStringException
     */
    public function getByReceiverId(int $receiverId): array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_RECEIVER_ID);
        $stmt->execute(['receiver_id' => $receiverId]);

        $reviews = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $reviews[] = $this->mapRowToReview($row);
        }

        return $reviews;
    }

    /**
     * Добавить отзыв
     */
    public function save(Review $review): void
    {
        $stmt = $this->pdo->prepare(self::SQL_INSERT);

        $stmt->execute([
            'author_id'   => $review->getAuthorId(),
            'receiver_id' => $review->getReceiverId(),
            'ad_id'       => $review->getAdId(),
            'created_at'  => $review->getCreatedAt()->format('Y-m-d H:i:s'),
            'text'        => $review->getText(),
            'rating'      => $review->getRating()
        ]);
    }

    /**
     * Удалить отзыв по ID
     */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare(self::SQL_DELETE);
        $stmt->execute(['id' => $id]);
    }

    /**
     * Маппинг строки в объект Review
     * @throws DateMalformedStringException
     */
    private function mapRowToReview(array $row): Review
    {
        return new Review(
            (int)$row['id'],
            (int)$row['author_id'],
            (int)$row['receiver_id'],
            (int)$row['ad_id'],
            new DateTime($row['created_at']),
            $row['text'],
            (int)$row['rating']
        );
    }
}