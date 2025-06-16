<?php

namespace app\mappers;

use app\core\MapperInterface;
use app\models\User;

class UserMapper implements MapperInterface
{
    public function map(array $row): object
    {
        return new User(
            (int)$row['id'],
            $row['name'],
            $row['surname'],
            $row['email'],
            $row['password'],
            $row['role'],
            $row['phone_number'],
            $row['avatar_path'],
            $row['remember_token'] ?? null
        );
    }

    public function mapAll(array $rows): array
    {
        return array_map([$this, 'map'], $rows);
    }
}