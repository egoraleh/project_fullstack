<?php

namespace app\mappers;

use app\core\MapperInterface;
use app\models\Ad;

class AdMapper implements MapperInterface
{
    public function map(array $row): object
    {
        return new Ad(
            (int)$row['id'],
            $row['title'],
            (int)$row['price'],
            $row['description'],
            (int)$row['user_id'],
            (int)$row['category_id'],
            $row['address'],
            $row['image_url']
        );
    }

    public function mapAll(array $rows): array
    {
        return array_map([$this, 'map'], $rows);
    }
}
