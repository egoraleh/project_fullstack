<?php

declare(strict_types=1);

namespace app\models;

class Ad
{
    private int $id {
        get {
            return $this->id;
        }
        set {
            $this->id = $value;
        }
    }
    private string $title {
        get {
            return $this->title;
        }
        set {
            $this->title = $value;
        }
    }
    private int $price {
        get {
            return $this->price;
        }
        set {
            $this->price = $value;
        }
    }
    private string $description {
        get {
            return $this->description;
        }
        set {
            $this->description = $value;
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
    private int $categoryId {
        get {
            return $this->categoryId;
        }
        set {
            $this->categoryId = $value;
        }
    }
    private string $address {
        get {
            return $this->address;
        }
        set {
            $this->address = $value;
        }
    }

    public function __construct(int $id, string $title, int $price,
                                string $description, int $userId,
                                int $categoryId, string $address) {
        $this->id = $id;
        $this->title = $title;
        $this->price = $price;
        $this->description = $description;
        $this->userId = $userId;
        $this->categoryId = $categoryId;
        $this->address = $address;
    }

}