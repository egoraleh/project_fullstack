<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use app\core\Migration;
use app\database\connection\Connection;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$pdo = Connection::getConnection();

$pdo->exec(<<<SQL
CREATE TABLE IF NOT EXISTS migrations (
    version BIGINT PRIMARY KEY
);
SQL
);

$count = (int)$pdo->query("SELECT COUNT(*) FROM migrations;")->fetchColumn();
if ($count === 0) {
    $pdo->exec("INSERT INTO migrations (version) VALUES (-1);");
}

$maxVer = (int)$pdo->query("SELECT MAX(version) FROM migrations;")->fetchColumn();
echo "Current migration version: $maxVer\n";

require_once __DIR__ . '/migrations/AllMigrations.php';
$migrations = getMigrations();
echo count($migrations) . " migrations found\n";

foreach ($migrations as $migration) {
    /** @var Migration $migration */
    $ver = $migration->getVersion();
    if ($ver <= $maxVer) {
        continue;
    }
    echo "Applying migration $ver...\n";
    $migration->setPdo($pdo);
    $migration->up();
    echo "Migration $ver applied.\n";
}

echo "All done.\n";
