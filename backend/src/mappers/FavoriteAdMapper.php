<?php

namespace app\mappers;

use app\core\MapperInterface;
use app\models\FavoriteAd;
use DateMalformedStringException;
use DateTime;

class FavoriteAdMapper implements MapperInterface
{
    /**
     * @throws DateMalformedStringException
     */
    public function map(array $row): object
    {
        return new FavoriteAd(
            (int)$row['id'],
            (int)$row['user_id'],
            (int)$row['ad_id'],
            new DateTime($row['created_at'])
        );
    }

    public function mapAll(array $rows): array
    {
        return array_map([$this, 'map'], $rows);
    }
}
