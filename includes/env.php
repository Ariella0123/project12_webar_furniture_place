<?php
if (!defined('APP_SECURE')) {
    exit('Direct access script denied.');
}

/**
 * 原生 PHP 轻量级 .env 解析函数
 */
function load_env(string $filePath): void {
    if (!file_exists($filePath)) {
        return;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // 忽略注释行
        $line = trim($line);
        if (str_starts_with($line, '#') || empty($line)) {
            continue;
        }

        // 解析 KEY=VALUE
        if (str_contains($line, '=')) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // 去除首尾双引号或单引号
            $value = trim($value, '"\'');

            // 写入环境变量与 $_ENV
            if (!array_key_exists($key, $_SERVER) && !array_key_exists($key, $_ENV)) {
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

/**
 * 安全获取环境变量的辅助函数
 */
function env(string $key, mixed $default = null): mixed {
    $value = getenv($key);

    if ($value === false) {
        return $default;
    }

    // 转换布尔值类型
    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'empty':
        case '(empty)':
            return '';
        case 'null':
        case '(null)':
            return null;
    }

    return $value;
}