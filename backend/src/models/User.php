<?php

declare(strict_types=1);

namespace app\models;

class User
{
    private int $id {
        get {
            return $this->id;
        }
        set {
            $this->id = $value;
        }
    }
    private string $name {
        get {
            return $this->name;
        }
        set {
            $this->name = $value;
        }
    }
    private string $surname {
        get {
            return $this->surname;
        }
        set {
            $this->surname = $value;
        }
    }
    private string $email {
        get {
            return $this->email;
        }
        set {
            $this->email = $value;
        }
    }
    private string $password {
        get {
            return $this->password;
        }
        set {
            $this->password = $value;
        }
    }
    private string $role {
        get {
            return $this->role;
        }
        set {
            $this->role = $value;
        }
    }
    private string $phoneNumber {
        get {
            return $this->phoneNumber;
        }
        set {
            $this->phoneNumber = $value;
        }
    }
    private string $avatarUrl {
        get {
            return $this->avatarUrl;
        }
        set {
            $this->avatarUrl = $value;
        }
    }

    public function __construct(int $id, string $name, string $surname,
                                string $email, string $password, string $role,
                                string $phoneNumber, string $avatarUrl) {
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->phoneNumber = $phoneNumber;
        $this->avatarUrl = $avatarUrl;
    }
}