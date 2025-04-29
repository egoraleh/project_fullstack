<?php
declare(strict_types = 1);

namespace app\models;

use DateTime;

class FavoriteAd
{
    private int $id {
        get {
            return $this->id;
        }
        set {
            $this->id = $value;
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
    private int $userId {
        get {
            return $this->userId;
        }
        set {
            $this->userId = $value;
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

    public function __construct(int $id, int $userId, int $adId, DateTime $createdAt) {
        $this->id = $id;
        $this->adId = $adId;
        $this->userId = $userId;
        $this->createdAt = $createdAt;
    }
}