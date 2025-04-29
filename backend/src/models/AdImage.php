<?php

declare(strict_types=1);

namespace app\models;

class AdImage
{
    private int $id;
    private int $adId;
    private int $position;
    private string $url;

    public function __construct(int $id, int $adId, int $position, string $url) {
        $this->id = $id;
        $this->adId = $adId;
        $this->position = $position;
        $this->url = $url;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAdId(): int
    {
        return $this->adId;
    }

    public function setAdId(int $adId): void
    {
        $this->adId = $adId;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }
}