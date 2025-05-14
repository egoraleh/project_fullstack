<?php

declare(strict_types=1);

namespace app\core;

use Exception;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class Logger extends AbstractLogger implements LoggerInterface
{
    private string $logDir;
    private string $logPrefix;

    /**
     * @throws Exception
     */
    public function __construct(string $logDir, string $logPrefix = 'app')
    {
        $this->logDir = rtrim($logDir, '/');
        $this->logPrefix = $logPrefix;

        if (!file_exists($this->logDir)) {
            $success = mkdir($this->logDir, 0777, true);
            if (!$success) {
                throw new Exception("Cannot create log directory: {$this->logDir}");
            }
        }
    }

    public function log($level, $message, array $context = []): void
    {
        $filename = $this->getDailyFilename();
        $timestamp = date("Y-m-d H:i:s");
        $logLine = sprintf(
            "%s\t[%s] %s %s\n",
            $timestamp,
            strtoupper($level),
            $message,
            json_encode($context, JSON_UNESCAPED_SLASHES)
        );

        file_put_contents(
            $filename,
            $logLine,
            FILE_APPEND | LOCK_EX
        );
    }

    private function getDailyFilename(): string
    {
        $date = date('Y-m-d');
        return "{$this->logDir}/{$this->logPrefix}_{$date}.log";
    }
}