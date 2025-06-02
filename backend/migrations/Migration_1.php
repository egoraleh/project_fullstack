<?php

declare(strict_types=1);

namespace migrations;

use app\core\Migration;

class Migration_1 extends Migration
{
    public function getVersion(): int
    {
        return 1;
    }

    public function up(): void
    {
        $this->pdo->exec(<<<SQL
CREATE TABLE IF NOT EXISTS categories
(
    id   BIGSERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);
SQL
        );

        parent::up();
    }

    public function down(): void {}
}
