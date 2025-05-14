<?php
declare(strict_types=1);

namespace app\core;

use Exception;

class ConfigParser
{
    /**
     * Загружает параметры из JSON-конфига в $_ENV и переменные окружения
     * @throws Exception
     */
    public static function load(string $path): void
    {
        self::validateConfigFile($path);
        $data = self::parseConfig($path);
        self::setEnvironmentVariables($data);
    }

    /**
     * @throws Exception
     */
    private static function validateConfigFile(string $path): void
    {
        if (!file_exists($path)) {
            throw new Exception("Config file not found: $path");
        }

        if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== 'json') {
            throw new Exception("Only JSON config files are supported");
        }
    }

    /**
     * @throws Exception
     */
    private static function parseConfig(string $path): array
    {
        $raw = file_get_contents($path);
        $data = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON parse error: " . json_last_error_msg());
        }

        return $data;
    }

    private static function setEnvironmentVariables(array $data): void
    {
        foreach ($data as $key => $value) {
            $key = strtoupper($key);

            if (is_array($value)) {
                $value = json_encode($value);
            }

            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}