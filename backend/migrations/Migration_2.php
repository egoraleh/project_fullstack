<?php

declare(strict_types=1);

namespace migrations;

use app\core\Migration;

class Migration_2 extends Migration
{
    public function getVersion(): int
    {
        return 2;
    }

    public function up(): void
    {
        $this->pdo->exec(<<<SQL
CREATE TABLE IF NOT EXISTS ads
(
    id          BIGSERIAL PRIMARY KEY,
    user_id     BIGINT     NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    title       VARCHAR(100) NOT NULL,
    description TEXT        NOT NULL,
    category_id BIGINT     NOT NULL REFERENCES categories (id),
    price       INT         NOT NULL,
    address     VARCHAR    NOT NULL,
    image_url   VARCHAR    NOT NULL
);
SQL
        );

        parent::up();
    }

    public function down(): void {}
}
