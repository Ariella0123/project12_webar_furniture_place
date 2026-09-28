<?php
define('APP_SECURE', true);
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/products.php';

$slug = $_GET['slug'] ?? '';
$product = get_product_by_slug($pdo, $slug);

if (!$product) {
    http_response_code(404);
    die('<div style="font-family:sans-serif; text-align:center; padding:100px;"><h2>Product Not Found</h2><p><a href="index.php">← Back to Catalog</a></p></div>');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize_output($product['name']) ?> - LuxeAR</title>
    <link rel="stylesheet" href="css/styles.css">
    
    <!-- QRCode.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    
    <!-- Google <model-viewer> Web Component -->
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>

    <style>
        /* AR 按钮专属样式：确保在手机端醒目 */
        .ar-btn-custom {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: 12px 20px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.95rem;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
            cursor: pointer;
            z-index: 99;
        }
        .ar-btn-custom:active {
            transform: scale(0.96);
        }
    </style>
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

    <main class="container" style="padding-top: 20px; padding-bottom: 40px;">
        <div class="product-detail-grid">
            
            <!-- 3D & Camera AR 展示区 -->
            <div class="viewer-container" style="position: relative; background: #f8fafc; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
                
                <model-viewer 
                    id="furniture-viewer"
                    src="<?= sanitize_output($product['glb_path']) ?>" 
                    <?= !empty($product['usdz_path']) ? 'ios-src="' . sanitize_output($product['usdz_path']) . '"' : '' ?>
                    alt="<?= sanitize_output($product['name']) ?>"
                    ar
                    ar-modes="webxr scene-viewer quick-look" 
                    ar-placement="floor"
                    ar-scale="fixed"
                    camera-controls 
                    auto-rotate
                    shadow-intensity="1.5"
                    style="width: 100%; height: 420px; display: block;">
                    
                    <!-- 【核心】唤起手机摄像头的 AR 按钮 -->
                    <button slot="ar-button" class="ar-btn-custom">
                        📷 开启摄像头看 AR 效果
                    </button>

                </model-viewer>

            </div>

            <!-- 产品详情信息 -->
            <div class="product-info-panel" style="margin-top: 20px;">
                <span class="card-category"><?= sanitize_output($product['category_name'] ?? 'Furniture') ?></span>
                <h1 style="margin: 10px 0; font-size: 1.8rem; color: #0f172a;"><?= sanitize_output($product['name']) ?></h1>
                <div class="price-tag" style="font-size: 1.4rem; font-weight: 700; color: #2563eb; margin-bottom: 15px;">
                    <?= format_price($product['price'], $product['currency'] ?? 'USD') ?>
                </div>
                
                <p style="color: #64748b; line-height: 1.6; margin-bottom: 20px;">
                    <?= nl2br(sanitize_output($product['description'])) ?>
                </p>

                <!-- 扫码体验提示 -->
                <div class="qr-box" style="display: flex; gap: 15px; align-items: center; background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div id="qrcode" style="padding: 5px; background: #fff; border-radius: 4px; border: 1px solid #cbd5e1;"></div>
                    <div>
                        <strong style="display: block; font-size: 0.9rem; color: #0f172a; margin-bottom: 4px;">手机扫码唤起摄像头</strong>
                        <span style="font-size: 0.8rem; color: #64748b;">用手机扫描此二维码，点击右下角按钮即可调用相机查看真实效果。</span>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script src="js/qr.js"></script>
    <script src="js/app.js"></script>
</body>
</html>