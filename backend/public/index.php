<?php

declare(strict_types=1);

use app\core\Application;

define('PROJECT_ROOT', dirname(__DIR__));

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/config.php';

$app = new Application();

require_once __DIR__ . '/../src/routes/web.php';

$app->run();
