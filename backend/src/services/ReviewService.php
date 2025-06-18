<?php

namespace app\services;

use app\database\dao\ReviewDAO;
use app\exceptions\ValidationException;
use app\models\Review;
use DateTime;

class ReviewService
{
    private ReviewDAO $reviewDAO;

    public function __construct()
    {
        $this->reviewDAO = new ReviewDAO();
    }

    /**
     * @throws ValidationException
     */
    public function createReview(array $data): void
    {
        $this->validateReviewData($data);
        $review = new Review((int)null, $data['author_id'], $data['receiver_id'], $data['ad_id'], new DateTime(), $data['text'], $data['rating']);
        $this->reviewDAO->save($review);
    }

    public function getReviewsByAdId(int $adId): array
    {
        return $this->reviewDAO->getByAdId($adId) ?? [];
    }

    /**
     * @throws ValidationException
     */
    private function validateReviewData(array $data): void
    {
        if (empty($data['author_id'])) {
            throw new ValidationException("ID автора обязателен");
        }

        if (empty($data['receiver_id'])) {
            throw new ValidationException("ID получателя обязателен");
        }

        if (empty($data['ad_id'])) {
            throw new ValidationException("ID объявления обязателен");
        }

        if (empty($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
            throw new ValidationException("Оценка должна быть от 1 до 5");
        }

        if (empty($data['text'])) {
            throw new ValidationException("Текст отзыва обязателен");
        }
    }

    public function getReviewById(int $reviewId): array
    {
        return $this->reviewDAO->get($reviewId) ?? [];
    }

    public function deleteReviewById(int $reviewId): void
    {
        $this->reviewDAO->delete($reviewId);
    }
}