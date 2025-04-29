<?php

declare(strict_types=1);

use app\core\Application;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application();

require_once __DIR__ . '/routes/web.php';

return $app;