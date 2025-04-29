<?php

declare(strict_types=1);

namespace app\models;

use DateTime;

class Review
{
    private int $id;
    private int $authorId;
    private int $receiverId;
    private int $adId;
    private DateTime $createdAt;
    private string $text;
    private int $rating;

    public function __construct(int $id, int $authorId, int $receiverId, int $adId, DateTime $createdAt, string $text, int $rating)
    {
        $this->id = $id;
        $this->authorId = $authorId;
        $this->receiverId = $receiverId;
        $this->adId = $adId;
        $this->createdAt = $createdAt;
        $this->text = $text;
        $this->rating = $rating;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getReceiverId(): int
    {
        return $this->receiverId;
    }

    public function setReceiverId(int $receiverId): void
    {
        $this->receiverId = $receiverId;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getAdId(): int
    {
        return $this->adId;
    }

    public function setAdId(int $adId): void
    {
        $this->adId = $adId;
    }
}