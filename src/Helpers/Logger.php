<?php

namespace App\Helpers;

class Logger
{
    private static string $logFile = __DIR__ . '/../../storage/log/log.txt';

    public static function init(): void
    {
        ini_set('log_errors', '1');
        ini_set('error_log', self::$logFile);

        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function log(string $message): void
    {
        $date = date('Y-m-d H:i:s');
        $entry = "[$date] $message" . PHP_EOL;
        file_put_contents(self::$logFile, $entry, FILE_APPEND);
    }

    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): void
    {
        $msg = "ERROR [$errno] $errstr en $errfile línea $errline";
        self::log($msg);
    }

    public static function handleException(\Throwable $e): void
    {
        $msg = "EXCEPCIÓN: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine();
        self::log($msg);
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error !== null) {
            $msg = "SHUTDOWN ERROR [{$error['type']}] {$error['message']} en {$error['file']} línea {$error['line']}";
            self::log($msg);
        }
    }
}