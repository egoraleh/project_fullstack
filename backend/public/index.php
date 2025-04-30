<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use app\core\Application;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$app = new Application();

require_once __DIR__ . '/../src/routes/web.php';

$app->run();
