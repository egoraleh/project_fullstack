<?php

namespace app\database\dao;

use app\database\connection\Connection;
use app\models\User;
use PDO;

class UserDAO
{
    private PDO $pdo;

    private const string SQL_GET_ALL        = "SELECT * FROM users";
    private const string SQL_GET_BY_ID      = "SELECT * FROM users WHERE id = :id";
    private const string SQL_GET_BY_EMAIL   = "SELECT * FROM users WHERE email = :email";
    private const string SQL_GET_BY_PHONE       = "SELECT * FROM users WHERE phone_number = :phone_number";
    private const string SQL_INSERT         = "INSERT INTO users 
        (name, surname, email, password, role, phone_number, avatar_path) 
        VALUES 
        (:name, :surname, :email, :password, :role, :phone_number, :avatar_path)";
    private const string SQL_DELETE         = "DELETE FROM users WHERE id = :id";

    public function __construct()
    {
        $this->pdo = Connection::getConnection();
    }

    /**
     * Получить всех пользователей
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_ALL);
        $stmt->execute();

        $users = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $this->mapRowToUser($row);
        }

        return $users;
    }

    /**
     * Получить пользователя по ID
     */
    public function getById(int $id): ?User
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_ID);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToUser($row) : null;
    }

    /**
     * Получить пользователя по Email
     */
    public function getByEmail(string $email): ?User
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_EMAIL);
        $stmt->execute(['email' => $email]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToUser($row) : null;
    }

    /**
     * Получить пользователя по номеру телефона
     */
    public function getByPhoneNumber(string $phoneNumber): ?User
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_PHONE);
        $stmt->execute(['phone_number' => $phoneNumber]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToUser($row) : null;
    }

    /**
     * Добавить нового пользователя
     */
    public function save(User $user): void
    {
        $stmt = $this->pdo->prepare(self::SQL_INSERT);

        $stmt->execute([
            'name'         => $user->getName(),
            'surname'      => $user->getSurname(),
            'email'        => $user->getEmail(),
            'password'     => $user->getPassword(),
            'role'         => $user->getRole(),
            'phone_number' => $user->getPhoneNumber(),
            'avatar_path'  => $user->getAvatarUrl()
        ]);
    }

    /**
     * Удалить пользователя по ID
     */
    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare(self::SQL_DELETE);
        $stmt->execute(['id' => $id]);
    }

    /**
     * Преобразование строки из БД в объект User
     */
    private function mapRowToUser(array $row): User
    {
        return new User(
            (int)$row['id'],
            $row['name'],
            $row['surname'],
            $row['email'],
            $row['password'],
            $row['role'],
            $row['phone_number'],
            $row['avatar_path'] ?? ''
        );
    }
}