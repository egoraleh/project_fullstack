<?php

declare(strict_types=1);

namespace migrations;

use app\core\Migration;

class Migration_3 extends Migration
{
    public function getVersion(): int
    {
        return 3;
    }

    public function up(): void
    {
        $this->pdo->exec(<<<SQL
CREATE TABLE IF NOT EXISTS favorite_ads
(
    id         BIGSERIAL PRIMARY KEY,
    user_id    BIGINT    NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    ad_id      BIGINT    NOT NULL REFERENCES ads (id) ON DELETE CASCADE,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    UNIQUE (user_id, ad_id)
);
SQL
        );

        parent::up();
    }

    public function down(): void {}
}
