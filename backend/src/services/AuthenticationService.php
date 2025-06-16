<?php
declare(strict_types=1);

namespace app\services;

use app\database\dao\UserDAO;
use app\exceptions\AuthenticationException;
use app\exceptions\ValidationException;
use app\mappers\UserMapper;
use app\models\User;
use Random\RandomException;

class AuthenticationService
{
    private UserDAO $userDao;

    public function __construct()
    {
        $this->userDao = new UserDAO();
    }

    /**
     * @throws ValidationException
     * @throws RandomException
     * @throws AuthenticationException
     */
    public function login(array $data): User
    {
        if (empty($data['email'])) {
            throw new ValidationException('Email is required');
        }
        if (empty($data['password'])) {
            throw new ValidationException('Password is required');
        }

        $userData = $this->userDao->getByEmail($data['email']);
        if (!$userData) {
            throw new AuthenticationException('Invalid credentials');
        }

        $user = (new UserMapper())->map($userData);

        if (!password_verify($data['password'], $user->getPassword())) {
            throw new AuthenticationException('Invalid credentials');
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['user_id'] = $user->getId();

        if (!empty($data['remember']) && $data['remember']) {
            $token = bin2hex(random_bytes(32));
            $this->userDao->setRememberToken($user->getId(), $token);
            setcookie('remember_token', $token, time() + 60 * 60 * 24 * 30, '/', '', false, true);
        }

        return $user;
    }

    public function getCurrentUser(): ?User
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!empty($_SESSION['user_id'])) {
            $userData = $this->userDao->get((int)$_SESSION['user_id']);
            return $userData ? (new UserMapper())->map($userData) : null;
        }

        if (!empty($_COOKIE['remember_token'])) {
            $userData = $this->userDao->getByRememberToken($_COOKIE['remember_token']);
            if ($userData) {
                $user = (new UserMapper())->map($userData);
                $_SESSION['user_id'] = $user->getId();
                return $user;
            }
        }

        return null;
    }

    public function logout(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if (!empty($_COOKIE['remember_token'])) {
            $this->userDao->clearRememberToken($_COOKIE['remember_token']);
            setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        }
        session_unset();
        session_destroy();
    }
}
