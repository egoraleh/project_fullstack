<?php

declare(strict_types=1);

namespace migrations;

use app\core\Migration;

class Migration_4 extends Migration
{
    public function getVersion(): int
    {
        return 4;
    }

    public function up(): void
    {
        $this->pdo->exec(<<<SQL
CREATE TABLE IF NOT EXISTS reviews
(
    id          BIGSERIAL PRIMARY KEY,
    author_id   BIGINT    NOT NULL REFERENCES users (id)     ON DELETE CASCADE,
    receiver_id BIGINT    NOT NULL REFERENCES users (id)     ON DELETE CASCADE,
    ad_id       BIGINT    NOT NULL REFERENCES ads (id)       ON DELETE CASCADE,
    created_at  TIMESTAMP NOT NULL DEFAULT NOW(),
    text        TEXT      NOT NULL,
    rating      INT       NOT NULL CHECK (rating >= 1 AND rating <= 5)
);
SQL
        );

        parent::up();
    }

    public function down(): void {}
}
