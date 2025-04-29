<?php

declare(strict_types=1);

namespace app\models;

class AdImage
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
    private string $url {
        get {
            return $this->url;
        }
        set {
            $this->url = $value;
        }
    }

    public function __construct(int $id, int $adId, string $url) {
        $this->id = $id;
        $this->adId = $adId;
        $this->url = $url;
    }
}