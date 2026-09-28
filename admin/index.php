<?php
define('APP_SECURE', true);
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/admin_layout.php';
require_once __DIR__ . '/../includes/products.php';

require_admin_auth();

// 获取全部产品（含禁用）
$stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC");
$products = $stmt->fetchAll();

render_admin_header('Product Management');
?>
<div style="display:flex; justify-content:space-between; align-items:center;">
    <h2>Furniture Products</h2>
    <a href="product_edit.php" class="btn">+ Add New Product</a>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><strong><?= sanitize_output($p['name']) ?></strong></td>
            <td><?= sanitize_output($p['category_name']) ?></td>
            <td><?= format_price($p['price'], $p['currency']) ?></td>
            <td>
                <span style="padding:4px 8px; border-radius:4px; font-size:0.75rem; background:<?= $p['is_active'] ? '#dcfce7; color:#15803d;' : '#fee2e2; color:#b91c1c;' ?>">
                    <?= $p['is_active'] ? 'Active' : 'Disabled' ?>
                </span>
            </td>
            <td>
                <a href="product_edit.php?id=<?= $p['id'] ?>" style="color:var(--accent);">Edit</a> | 
                <a href="product_delete.php?id=<?= $p['id'] ?>" onclick="return confirm('Are you sure you want to delete this product?');" style="color:#ef4444;">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
render_admin_footer();