<?php

namespace app\mappers;

use app\core\MapperInterface;
use app\models\Category;

class CategoryMapper implements MapperInterface
{
    public function map(array $row): object
    {
        return new Category(
            (int)$row['id'],
            $row['name']
        );
    }

    public function mapAll(array $rows): array
    {
        return array_map([$this, 'map'], $rows);
    }
}
