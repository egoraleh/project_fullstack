<?php
declare(strict_types=1);

namespace app\controllers;

use app\core\Application;
use app\core\Logger;

class UserController
{
    private Logger $logger;

    public function __construct()
    {
        $this->logger = Application::$app->getLogger();
    }
}