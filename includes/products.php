<?php
function get_active_products(PDO $pdo, ?string $category_slug = null): array {
    $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
            FROM products p 
            JOIN categories c ON p.category_id = c.id 
            WHERE p.is_active = 1 AND c.is_active = 1";
    $params = [];

    if ($category_slug) {
        $sql .= " AND c.slug = :slug";
        $params['slug'] = $category_slug;
    }

    $sql .= " ORDER BY p.sort_order ASC, p.id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function get_product_by_slug(PDO $pdo, string $slug): ?array {
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE p.slug = :slug AND p.is_active = 1 LIMIT 1");
    $stmt->execute(['slug' => $slug]);
    $res = $stmt->fetch();
    return $res ?: null;
}

function format_price(float $price, string $currency = 'USD'): string {
    $symbols = ['USD' => '$', 'EUR' => '€', 'GBP' => '£', 'RM' => 'RM '];
    $symbol = $symbols[$currency] ?? ($currency . ' ');
    return $symbol . number_format($price, 2);
}

function sanitize_output(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}