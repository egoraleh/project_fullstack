<?php
declare(strict_types=1);

namespace app\services;

use app\models\User;
use app\database\dao\UserDAO;
use app\exceptions\ValidationException;

class RegistrationService
{
    private UserDAO $userDao;

    public function __construct()
    {
        $this->userDao = new UserDAO();
    }

    /**
     * @throws ValidationException
     */
    public function register(array $data): User
    {
        if (empty($data['email'])) {
            throw new ValidationException('Email is required');
        }
        if (empty($data['password'])) {
            throw new ValidationException('Password is required');
        }

        if ($this->userDao->getByEmail($data['email'])) {
            throw new ValidationException('Email already exists');
        }

        $user = new User(
            0,
            $data['first_name'] ?? null,
            $data['last_name']  ?? null,
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            'basic',
            $data['phone'] ?? null,
            '/uploads/avatars/default.png'
        );

        $this->userDao->save($user);
        return $user;
    }
}
