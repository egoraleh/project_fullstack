<?php

namespace app\mappers;

use app\core\MapperInterface;
use app\models\Review;
use DateMalformedStringException;
use DateTime;

class ReviewMapper implements MapperInterface
{
    /**
     * @throws DateMalformedStringException
     */
    public function map(array $row): object
    {
        return new Review(
            (int)$row['id'],
            (int)$row['author_id'],
            (int)$row['receiver_id'],
            (int)$row['ad_id'],
            new DateTime($row['created_at']),
            $row['text'],
            (int)$row['rating']
        );
    }

    public function mapAll(array $rows): array
    {
        return array_map([$this, 'map'], $rows);
    }
}
