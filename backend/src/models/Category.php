<?php

declare(strict_types=1);

namespace app\models;

class Category
{
    private int $id {
        get {
            return $this->id;
        }
        set {
            $this->id = $value;
        }
    }
    private string $name {
        get {
            return $this->name;
        }
        set {
            $this->name = $value;
        }
    }

    public function __construct(int $id, string $name) {
        $this->id = $id;
        $this->name = $name;
    }
}