<?php

declare(strict_types=1);

namespace app\models;

class User
{
    private int $id;
    private mixed $name;
    private mixed $surname;
    private string $email;
    private string $password;
    private string $role;
    private mixed $phoneNumber;
    private string $avatarUrl;

    public function __construct(int $id, mixed $name, mixed $surname,
                                string $email, string $password, string $role,
                                mixed $phoneNumber, string $avatarUrl) {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->phoneNumber = $phoneNumber;
        $this->avatarUrl = $avatarUrl;
    }


    public function getAvatarUrl(): string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(string $avatarUrl): void
    {
        $this->avatarUrl = $avatarUrl;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): mixed
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhoneNumber(): mixed
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getSurname(): mixed
    {
        return $this->surname;
    }

    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}