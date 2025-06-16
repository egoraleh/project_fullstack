<?php

declare(strict_types=1);

namespace migrations;

use app\core\Migration;

class Migration_0 extends Migration
{
    public function getVersion(): int
    {
        return 0;
    }

    public function up(): void
    {
        $this->pdo->exec(<<<SQL
CREATE TABLE IF NOT EXISTS users
(
    id           BIGSERIAL PRIMARY KEY,
    name         VARCHAR(50),
    surname      VARCHAR(70),
    role         CHAR(5)   DEFAULT 'basic' NOT NULL,
    email        VARCHAR(50) UNIQUE NOT NULL,
    avatar_path  VARCHAR,
    password     VARCHAR(255) NOT NULL,
    phone_number CHAR(12) UNIQUE,
    remember_token VARCHAR(255)
);
SQL
        );
        parent::up();
    }

    public function down(): void
    {
    }
}
