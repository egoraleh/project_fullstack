<?php

declare(strict_types=1);

namespace app\core;

use PDO;

abstract class Migration
{
    protected PDO $pdo;

    public function setPdo(PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    abstract public function getVersion(): int;

    public function up(): void
    {
        $this->pdo->exec("DELETE FROM migrations;");
        $this->pdo->exec("INSERT INTO migrations (version) VALUES (" . $this->getVersion() . ");");
    }

    public function down(): void
    {
    }
}