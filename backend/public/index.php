<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use app\core\Application;

$app = new Application();

require_once __DIR__ . '/../src/routes/web.php';

$app->run();
