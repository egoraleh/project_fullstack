<?php

namespace app\database\dao;

use app\core\DAOInterface;
use app\database\connection\Connection;
use app\models\User;
use http\Exception\InvalidArgumentException;
use PDO;

class UserDAO implements DAOInterface
{
    private PDO $pdo;

    private const SQL_GET_ALL        = "SELECT * FROM users";
    private const SQL_GET_BY_ID      = "SELECT * FROM users WHERE id = :id";
    private const SQL_GET_BY_TOKEN   = "SELECT * FROM users WHERE remember_token = :token";
    private const SQL_UPDATE_TOKEN   = "UPDATE users SET remember_token = :token WHERE id = :id";
    private const SQL_DELETE_TOKEN  = "UPDATE users SET remember_token = NULL WHERE remember_token = :token";
    private const SQL_GET_BY_EMAIL   = "SELECT * FROM users WHERE email = :email";
    private const SQL_GET_BY_PHONE   = "SELECT * FROM users WHERE phone_number = :phone_number";
    private const SQL_INSERT         = "INSERT INTO users 
        (name, surname, email, password, role, phone_number, avatar_path) 
        VALUES 
        (:name, :surname, :email, :password, :role, :phone_number, :avatar_path)";
    private const SQL_UPDATE         = "UPDATE users SET
        name = :name,
        surname = :surname,
        email = :email,
        password = :password,
        role = :role,
        phone_number = :phone_number,
        avatar_path = :avatar_path
        WHERE id = :id";
    private const SQL_DELETE         = "DELETE FROM users WHERE id = :id";

    public function __construct()
    {
        $this->pdo = Connection::getConnection();
    }

    public function getAll(): ?array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_ALL);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get(int $id): ?array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_ID);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_EMAIL);
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getByPhoneNumber(string $phoneNumber): ?array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_PHONE);
        $stmt->execute(['phone_number' => $phoneNumber]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function save(object $user): void
    {
        if ($user instanceof User) {
            $stmt = $this->pdo->prepare(self::SQL_INSERT);

            $stmt->execute([
                'name' => $user->getName(),
                'surname' => $user->getSurname(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'role' => $user->getRole(),
                'phone_number' => $user->getPhoneNumber(),
                'avatar_path' => $user->getAvatarUrl()
            ]);
        } else {
            throw new InvalidArgumentException('Expected instance of User');
        }
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare(self::SQL_DELETE);
        $stmt->execute(['id' => $id]);
    }

    public function update(object $user): void
    {
        if ($user instanceof User) {
            $stmt = $this->pdo->prepare(self::SQL_UPDATE);

            $stmt->execute([
                'name'         => $user->getName(),
                'surname'      => $user->getSurname(),
                'email'        => $user->getEmail(),
                'password'     => $user->getPassword(),
                'role'         => $user->getRole(),
                'phone_number' => $user->getPhoneNumber(),
                'avatar_path'  => $user->getAvatarUrl(),
                'id'           => $user->getId()
            ]);
        } else {
            throw new InvalidArgumentException('Expected instance of User');
        }
    }

    public function getByRememberToken(string $token): ?array
    {
        $stmt = $this->pdo->prepare(self::SQL_GET_BY_TOKEN);
        $stmt->execute(['token' => $token]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function setRememberToken(int $id, string $token): void
    {
        $stmt = $this->pdo->prepare(self::SQL_UPDATE_TOKEN);
        $stmt->execute([
            'token' => $token,
            'id'    => $id
        ]);
    }

    public function clearRememberToken(mixed $remember_token): void
    {
        $stmt = $this->pdo->prepare(self::SQL_DELETE_TOKEN);
        $stmt->execute(['token' => $remember_token]);
    }
}