<?php

namespace app\database\dao;

use app\database\connection\Connection;
use app\models\AdImage;
use PDO;

class AdImageDAO
{
    private PDO $pdo;

    private const string SQL_GET_BY_AD_ID = "SELECT * FROM ad_images WHERE ad_id = :ad_id ORDER BY position";
    private const string SQL_INSERT       = "INSERT INTO ad_images (ad_id, position, url) VALUES (:ad_id, :position, :url)";
    private const string SQL_DELETE       = "DELETE FROM ad_images WHERE id = :id";
    private const string SQL_GET_BY_ID    = "SELECT * FROM ad_images WHERE id = :id";

    public function __construct()
    {
        $this->pdo = Connection::getConnection();
    }

    /**
     * Получить изображения по ID объявления (с сортировкой по позиции)
     */
    public function getByAdId(int $adId): array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_AD_ID);
        $stmt->execute(['ad_id' => $adId]);

        $images = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $images[] = $this->mapRowToAdImage($row);
        }

        return $images;
    }

    /**
     * Получить одно изображение по его ID
     */
    public function getById(int $id): ?AdImage
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_ID);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToAdImage($row) : null;
    }

    /**
     * Добавить новое изображение
     */
    public function save(AdImage $image): void
    {
        $stmt = $this->pdo->prepare(self::SQL_INSERT);

        $stmt->execute([
            'ad_id'    => $image->getAdId(),
            'position' => $image->getPosition(),
            'url'      => $image->getUrl()
        ]);
    }

    /**
     * Удалить изображение по ID
     */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare(self::SQL_DELETE);
        $stmt->execute(['id' => $id]);
    }

    /**
     * Маппинг строки в объект AdImage
     */
    private function mapRowToAdImage(array $row): AdImage
    {
        return new AdImage(
            (int)$row['id'],
            (int)$row['ad_id'],
            (int)$row['position'],
            $row['url']
        );
    }
}