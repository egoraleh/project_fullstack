<?php

namespace app\core;

interface DAOInterface
{
    public function save(object $entity): void;

    public function update(object $entity): void;

    public function delete(int $id): void;

    public function get(int $id): ?array;
}
