<?php

namespace app\database\dao;

use app\core\DAOInterface;
use app\database\connection\Connection;
use app\models\Category;
use PDO;
use http\Exception\InvalidArgumentException;

class CategoryDAO implements DAOInterface
{
    private PDO $pdo;

    private const SQL_GET_ALL   = "SELECT * FROM categories";
    private const SQL_GET_BY_ID = "SELECT * FROM categories WHERE id = :id";
    private const SQL_INSERT    = "INSERT INTO categories (name) VALUES (:name)";
    private const SQL_UPDATE    = "UPDATE categories SET name = :name WHERE id = :id";
    private const SQL_DELETE    = "DELETE FROM categories WHERE id = :id";

    public function __construct()
    {
        $this->pdo = Connection::getConnection();
    }

    public function getAll(): ?array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_ALL);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $rows ?: null;
    }

    public function get(int $id): ?array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_ID);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function save(object $category): void
    {
        if ($category instanceof Category) {
            $stmt = $this->pdo->prepare(self::SQL_INSERT);
            $stmt->execute([
                'name' => $category->getName()
            ]);
        } else {
            throw new InvalidArgumentException('Expected instance of Category');
        }
    }

    public function update(object $category): void
    {
        if ($category instanceof Category) {
            $stmt = $this->pdo->prepare(self::SQL_UPDATE);
            $stmt->execute([
                'name' => $category->getName(),
                'id'   => $category->getId()
            ]);
        } else {
            throw new InvalidArgumentException('Expected instance of Category');
        }
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare(self::SQL_DELETE);
        $stmt->execute(['id' => $id]);
    }
}
