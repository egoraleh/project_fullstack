<?php
declare(strict_types = 1);

namespace app\models;

use DateTime;

class FavoriteAd
{
    private int $id;
    private int $adId;
    private int $userId;
    private DateTime $createdAt;

    public function __construct(int $id, int $userId, int $adId, DateTime $createdAt) {
        $this->id = $id;
        $this->adId = $adId;
        $this->userId = $userId;
        $this->createdAt = $createdAt;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getAdId(): int
    {
        return $this->adId;
    }

    public function setAdId(int $adId): void
    {
        $this->adId = $adId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getId(): int
    {
        return $this->id;
    }
}