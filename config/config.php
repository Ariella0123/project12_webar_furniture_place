<?php
if (!defined('APP_SECURE')) {
    define('APP_SECURE', true);
}

// 引入 .env 解析模块
require_once __DIR__ . '/../includes/env.php';

// 加载根目录下的 .env
load_env(__DIR__ . '/../.env');

return [
    'app' => [
        'name' => env('APP_NAME', 'LuxeSpace AR Furniture'),
        'env'  => env('APP_ENV', 'production'),
        'debug' => env('APP_DEBUG', false),
        'base_url' => '', // 自动识别域名/路径，无缝支持 XAMPP 和 cPanel
    ],
    'db' => [
        'host' => env('DB_HOST', 'localhost'),
        'name' => env('DB_NAME', 'ar_furniture'),
        'user' => env('DB_USER', 'root'),
        'pass' => env('DB_PASS', '1234'),
        'charset' => env('DB_CHARSET', 'utf8mb4'),
        'port' => env('DB_PORT','3307')
    ],
    'upload' => [
        'max_size' => (int)env('MAX_UPLOAD_SIZE', 50 * 1024 * 1024),
        'allowed_3d' => ['glb', 'gltf', 'usdz'],
        'allowed_img' => ['jpg', 'jpeg', 'png', 'webp']
    ]
];