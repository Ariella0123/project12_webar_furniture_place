<?php
define('APP_SECURE', true);
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/products.php';
require_once __DIR__ . '/includes/categories.php';

$active_category = $_GET['category'] ?? null;
$categories = get_active_categories($pdo);
$products = get_active_products($pdo, $active_category);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LuxeSpace - AR Furniture Catalog</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="container navbar">
            <a href="index.php" class="logo">Luxe<span>AR</span></a>
            <nav class="nav-links">
                <a href="index.php">Catalog</a>
                <a href="admin/login.php">Admin Login</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="hero">
            <div class="hero-content">
                <h1>See Furniture in Your Space Before You Buy</h1>
                <p>Explore our modern collection in interactive 3D and place real-scale models directly in your room using Augmented Reality.</p>
            </div>
            <div class="qr-box">
                <div id="qrcode"></div>
                <p>Scan with Phone for AR</p>
            </div>
        </section>

        <!-- Dynamic Category Filter -->
        <div class="categories-nav">
            <a href="index.php" class="cat-btn <?= !$active_category ? 'active' : '' ?>">All Products</a>
            <?php foreach ($categories as $cat): ?>
                <a href="index.php?category=<?= urlencode($cat['slug']) ?>" 
                   class="cat-btn <?= $active_category === $cat['slug'] ? 'active' : '' ?>">
                   <?= sanitize_output($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Product Cards Grid -->
        <div class="product-grid">
            <?php if (empty($products)): ?>
                <p style="grid-column: 1/-1; text-align:center; padding: 40px; color: var(--text-muted);">No furniture items found in this category.</p>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                    <div class="product-card">
                        <div class="card-thumb">
                            <?php if (!empty($p['thumb_path'])): ?>
                                <img src="<?= sanitize_output($p['thumb_path']) ?>" alt="<?= sanitize_output($p['name']) ?>" loading="lazy">
                            <?php else: ?>
                                <span class="placeholder">3D Model Ready</span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <span class="card-category"><?= sanitize_output($p['category_name']) ?></span>
                            <h2 class="card-title"><?= sanitize_output($p['name']) ?></h2>
                            <div class="card-price">
                                <span><?= format_price($p['price'], $p['currency']) ?></span>
                                <a href="product.php?slug=<?= urlencode($p['slug']) ?>" class="btn-view">View in AR →</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <script src="js/qr.js"></script>
    <script src="js/app.js"></script>
</body>
</html>