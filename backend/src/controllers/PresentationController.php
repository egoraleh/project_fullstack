<?php
declare(strict_types=1);
namespace app\controllers;

use app\core\Application;

class PresentationController
{
    public function getView(): void
    {
        $backendUrl = getenv('BACKEND_URL') ?: 'http://localhost:8000';

        Application::$app
            ->getRouter()
            ->renderTemplate('spa', [
                'title'       => 'Classifieds Board',
                'backend_url' => $backendUrl
            ]);
    }
}