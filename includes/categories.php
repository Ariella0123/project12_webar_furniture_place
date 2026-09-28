<?php
function get_active_categories(PDO $pdo): array {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order ASC, name ASC");
    $stmt->execute();
    return $stmt->fetchAll();
}