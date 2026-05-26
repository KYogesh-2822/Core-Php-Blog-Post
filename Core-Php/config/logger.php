<?php

// ─── Only define once ───
if (!defined('LOG_INFO')) {
    define('LOG_INFO',    'INFO');
    define('LOG_ERROR',   'ERROR');
    define('LOG_WARNING', 'WARNING');
    define('LOG_DEBUG',   'DEBUG');
    define('LOG_AUTH',    'AUTH');
    define('LOG_DB',      'DB');
}

function writeLog(string $level, string $message, array $context = []) {
    $logDir  = ROOT . '/logs';
    $logFile = $logDir . '/' . date('Y-m-d') . '.log';

    if (!is_dir($logDir)) {
        mkdir($logDir, 0775, true);
    }

    $contextStr = '';
    if (!empty($context)) {
        $contextStr = ' | ' . json_encode($context);
    }

    $line = sprintf(
        "[%s] [%s] %s%s%s",
        date('Y-m-d H:i:s'),
        strtoupper($level),
        $message,
        $contextStr,
        PHP_EOL
    );

    file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
}

function logInfo(string $message, array $context = []) {
    writeLog(LOG_INFO, $message, $context);
}

function logError(string $message, array $context = []) {
    // writeLog(LOG_ERROR, $message, $context);
}

function logWarning(string $message, array $context = []) {
    writeLog(LOG_WARNING, $message, $context);
}

function logDebug(string $message, array $context = []) {
    writeLog(LOG_DEBUG, $message, $context);
}

function logAuth(string $message, array $context = []) {
    writeLog(LOG_AUTH, $message, $context);
}

function logDb(string $message, array $context = []) {
    writeLog(LOG_DB, $message, $context);
}

// ─── Verification code logger ───
function logVerificationCode(string $email, int $code, string $type = 'NEW') {
    $logDir  = ROOT . '/logs';
    $logFile = $logDir . '/verification_log.txt';

    if (!is_dir($logDir)) {
        mkdir($logDir, 0775, true);
    }

    $line = date('Y-m-d H:i:s') . " | $type | $email | Code: $code\n";
    file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
}