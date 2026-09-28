<?php
header('Content-Type: application/json; charset=utf-8');
define('APP_SECURE', true);

try {
    require_once __DIR__ . '/../includes/db.php';
    require_once __DIR__ . '/../includes/products.php';

    $category = $_GET['category'] ?? null;
    $products = get_active_products($pdo, $category);

    // 过滤排除任何后台或数据库敏感字段
    $cleaned_products = array_map(function($p) {
        return [
            'id' => (int)$p['id'],
            'name' => $p['name'],
            'slug' => $p['slug'],
            'category' => $p['category_name'],
            'price' => (float)$p['price'],
            'currency' => $p['currency'],
            'glb_path' => $p['glb_path'],
            'usdz_path' => $p['usdz_path'],
            'thumb_path' => $p['thumb_path'],
            'dimensions' => [
                'width_cm' => (float)$p['width_cm'],
                'height_cm' => (float)$p['height_cm'],
                'depth_cm' => (float)$p['depth_cm']
            ]
        ];
    }, $products);

    echo json_encode([
        'success' => true,
        'count' => count($cleaned_products),
        'products' => $cleaned_products
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Unable to fetch API data']);
}