<?php

namespace app\database\dao;

use app\database\connection\Connection;
use app\models\Category;
use PDO;

class CategoryDAO
{
    private PDO $pdo;

    private const string SQL_GET_ALL   = "SELECT * FROM categories ORDER BY name";
    private const string SQL_GET_BY_ID = "SELECT * FROM categories WHERE id = :id";
    private const string SQL_INSERT    = "INSERT INTO categories (name) VALUES (:name)";
    private const string SQL_DELETE    = "DELETE FROM categories WHERE id = :id";

    public function __construct()
    {
        $this->pdo = Connection::getConnection();
    }

    /**
     * Получить все категории
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_ALL);
        $stmt->execute();

        $categories = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categories[] = new Category(
                (int)$row['id'],
                $row['name']
            );
        }

        return $categories;
    }

    /**
     * Получить категорию по ID
     */
    public function getById(int $id): ?Category
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_ID);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? new Category((int)$row['id'], $row['name']) : null;
    }

    /**
     * Добавить категорию
     */
    public function save(Category $category): void
    {
        $stmt = $this->pdo->prepare(self::SQL_INSERT);
        $stmt->execute(['name' => $category->getName()]);
    }

    /**
     * Удалить категорию по ID
     */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare(self::SQL_DELETE);
        $stmt->execute(['id' => $id]);
    }
}