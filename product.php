<?php
define('APP_SECURE', true);
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/products.php';

$slug = $_GET['slug'] ?? '';
$product = get_product_by_slug($pdo, $slug);

if (!$product) {
    http_response_code(404);
    die('<div style="font-family:sans-serif; text-align:center; padding:100px;"><h2>Product Not Found</h2><a href="index.php">← Back to Catalog</a></div>');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize_output($product['name']) ?> - LuxeAR</title>
    <link rel="stylesheet" href="css/styles.css">
    <!-- Google <model-viewer> Web Component -->
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
</head>
<body>
    <header>
        <div class="container navbar">
            <a href="index.php" class="logo">Luxe<span>AR</span></a>
            <nav class="nav-links">
                <a href="index.php">← Back to Catalog</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="product-detail-grid">
            <!-- 3D & AR Interactive Area -->
            <div class="viewer-container">
                <model-viewer 
                    id="furniture-viewer"
                    src="<?= sanitize_output($product['glb_path']) ?>" 
                    <?= !empty($product['usdz_path']) ? 'ios-src="' . sanitize_output($product['usdz_path']) . '"' : '' ?>
                    alt="<?= sanitize_output($product['name']) ?> 3D Model"
                    
                    <!-- 基础控制与交互 -->
                    camera-controls 
                    auto-rotate
                    auto-rotate-delay="3000"
                    rotation-per-second="30deg"
                    shadow-intensity="1.5"
                    shadow-softness="0.8"
                    exposure="1"
                    
                    <!-- 移动端 AR 保持配置 -->
                    ar 
                    ar-modes="webxr scene-viewer quick-look" 
                    touch-action="pan-y">
                    
                    <!-- 1. 移动端 AR 按钮 -->
                    <button slot="ar-button" class="ar-btn-custom">
                        📱 View in Your Room
                    </button>

                    <!-- 2. [新增] 桌面端全屏查看按钮 -->
                    <button id="fullscreen-btn" class="desktop-btn-custom">
                        ⛶ 全屏查看模型
                    </button>

                    <!-- 3. [新增] 桌面端重置视角按钮 -->
                    <button id="reset-cam-btn" class="desktop-btn-custom reset-btn">
                        ↺ 重置视角
                    </button>
                </model-viewer>

                <!-- [新增] 桌面端鼠标操作提示 -->
                <div class="desktop-hint">
                    💡 提示：按住鼠标左键旋转 | 滚轮缩放 | 按住右键平移
                </div>

                <div id="ar-notice" style="display:none; position:absolute; top:10px; left:10px; background:rgba(0,0,0,0.7); color:#fff; padding:6px 12px; border-radius:4px; font-size:0.8rem;"></div>
            </div>

            <!-- Product Detail Info -->
            <div class="product-info-panel">
                <span class="card-category"><?= sanitize_output($product['category_name']) ?></span>
                <h1><?= sanitize_output($product['name']) ?></h1>
                <div class="price-tag"><?= format_price($product['price'], $product['currency']) ?></div>
                
                <p style="color:var(--text-muted);"><?= nl2br(sanitize_output($product['description'])) ?></p>

                <!-- Dimensions Display -->
                <?php if ($product['width_cm'] || $product['height_cm'] || $product['depth_cm']): ?>
                <div class="dim-box">
                    <strong>Real-World Dimensions (Scale 1:1)</strong>
                    <div class="dim-grid">
                        <div class="dim-item"><val><?= $product['width_cm'] ?: '-' ?> cm</val><lbl>Width</lbl></div>
                        <div class="dim-item"><val><?= $product['height_cm'] ?: '-' ?> cm</val><lbl>Height</lbl></div>
                        <div class="dim-item"><val><?= $product['depth_cm'] ?: '-' ?> cm</val><lbl>Depth</lbl></div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="qr-box" style="margin:20px 0 0 0; width:100%; display:flex; gap:15px; align-items:center; text-align:left; background:var(--bg-light); border: 1px solid var(--border-color);">
                    <div id="qrcode"></div>
                    <div>
                        <strong style="display:block; font-size:0.9rem;">Open on your phone</strong>
                        <span style="font-size:0.8rem; color:var(--text-muted);">Scan this QR code with your mobile camera to quickly view this item in AR.</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="js/qr.js"></script>
    <script src="js/app.js"></script>
</body>
</html>