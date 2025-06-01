<?php
declare(strict_types=1);

namespace app\core;

interface MapperInterface
{
    public function map(array $row): object;

    public function mapAll(array $rows): array;
}
