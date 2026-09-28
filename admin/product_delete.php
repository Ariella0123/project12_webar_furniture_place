<?php
define('APP_SECURE', true);
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin_auth();

$id = $_GET['id'] ?? null;
if ($id) {
    // 查找对应图片路径删除文件，避免磁盘空间浪费
    $stmt = $pdo->prepare("SELECT thumb_path FROM products WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $p = $stmt->fetch();
    
    if ($p && !empty($p['thumb_path']) && file_exists(__DIR__ . '/../' . $p['thumb_path'])) {
        @unlink(__DIR__ . '/../' . $p['thumb_path']);
    }

    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

header('Location: index.php');
exit;