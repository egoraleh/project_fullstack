<?php
declare(strict_types=1);

if (php_sapi_name()==='cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $file = __DIR__ . $url;
    if (is_file($file)) {
        return false;
    }
}

const PROJECT_ROOT = __DIR__ . "/../";

require_once PROJECT_ROOT . '/vendor/autoload.php';

use app\core\Application;
use app\core\Template;

Template::$cache_enabled = true;
Template::ClearCache();

try {
    $app = new Application();

    if (getenv('APP_ENV') === 'development') {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
    }

    $app->getLogger()->info('Application bootstrap completed', [
        'php_version' => PHP_VERSION,
        'environment' => getenv('APP_ENV') ?? 'development',
        'memory_usage' => round(memory_get_usage() / 1024 / 1024, 2) . ' MB'
    ]);

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        $app->getLogger()->debug('Preflight OPTIONS request');
        http_response_code(200);
        exit();
    }

    require_once __DIR__ . '/../src/routes/web.php';
    $app->getLogger()->debug('Routes loaded', [
        'route_count' => count($app->getRouter()->getRoutes())
    ]);

    $app->getLogger()->info('Application started processing request', [
        'method' => $_SERVER['REQUEST_METHOD'],
        'uri' => $_SERVER['REQUEST_URI'] ?? '/',
        'client_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
    ]);

    $app->run();

    $app->getLogger()->debug('Request processed successfully');

} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Internal Server Error']);
    exit();
}