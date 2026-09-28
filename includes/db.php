<?php
if (!defined('APP_SECURE')) exit('Direct access script denied.');

$config = require __DIR__ . '/../config/config.php';

try {
    // 增加 port 参数支持
    $port = isset($config['db']['port']) ? ";port={$config['db']['port']}" : '';
    $dsn = "mysql:host={$config['db']['host']}{$port};dbname={$config['db']['name']};charset={$config['db']['charset']}";
    
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $options);
} catch (\PDOException $e) {
    http_response_code(500);
    if ($config['app']['debug'] ?? false) {
        die('数据库连接失败原因：' . $e->getMessage());
    }
    die('<div style="font-family:sans-serif; text-align:center; padding:50px;"><h2>Database Connection Error</h2><p>Unable to connect to database. Please verify configuration settings.</p></div>');
}