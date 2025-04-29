<?php

declare(strict_types=1);

namespace app\models;

use DateTime;

class Review
{
    private int $id {
        get {
            return $this->id;
        }
        set {
            $this->id = $value;
        }
    }
    private int $authorId {
        get {
            return $this->authorId;
        }
        set {
            $this->authorId = $value;
        }
    }
    private int $receiverId {
        get {
            return $this->receiverId;
        }
        set {
            $this->receiverId = $value;
        }
    }
    private int $adId {
        get {
            return $this->adId;
        }
        set {
            $this->adId = $value;
        }
    }
    private DateTime $createdAt {
        get {
            return $this->createdAt;
        }
        set {
            $this->createdAt = $value;
        }
    }
    private string $text {
        get {
            return $this->text;
        }
        set {
            $this->text = $value;
        }
    }
    private int $rating {
        get {
            return $this->rating;
        }
        set {
            $this->rating = $value;
        }
    }

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
}