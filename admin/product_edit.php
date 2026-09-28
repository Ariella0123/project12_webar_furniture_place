<?php
define('APP_SECURE', true);
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/categories.php';
require_once __DIR__ . '/../includes/admin_layout.php';

require_admin_auth();

$id = $_GET['id'] ?? null;
$product = null;
$error = '';

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch();
}

$categories = get_active_categories($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $slug = trim($_POST['slug']);
    $category_id = (int)$_POST['category_id'];
    $price = (float)$_POST['price'];
    $description = trim($_POST['description']);
    $width_cm = (float)$_POST['width_cm'];
    $height_cm = (float)$_POST['height_cm'];
    $depth_cm = (float)$_POST['depth_cm'];
    $glb_path = trim($_POST['glb_path']);
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // 安全处理文件上传 (处理缩略图)
    $thumb_path = $product['thumb_path'] ?? '';
    if (isset($_FILES['thumb_file']) && $_FILES['thumb_file']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['thumb_file']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $newName = bin2hex(random_bytes(8)) . '.' . $ext;
            $targetDir = __DIR__ . '/../uploads/thumbs/';
            if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
            move_uploaded_file($_FILES['thumb_file']['tmp_name'], $targetDir . $newName);
            $thumb_path = 'uploads/thumbs/' . $newName;
        }
    }

    if ($id) {
        $sql = "UPDATE products SET category_id=?, slug=?, name=?, description=?, price=?, glb_path=?, thumb_path=?, width_cm=?, height_cm=?, depth_cm=?, is_active=? WHERE id=?";
        $pdo->prepare($sql)->execute([$category_id, $slug, $name, $description, $price, $glb_path, $thumb_path, $width_cm, $height_cm, $depth_cm, $is_active, $id]);
    } else {
        $sql = "INSERT INTO products (category_id, slug, name, description, price, glb_path, thumb_path, width_cm, height_cm, depth_cm, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $pdo->prepare($sql)->execute([$category_id, $slug, $name, $description, $price, $glb_path, $thumb_path, $width_cm, $height_cm, $depth_cm, $is_active]);
    }

    header('Location: index.php');
    exit;
}

render_admin_header($id ? 'Edit Product' : 'Add Product');
?>
<div class="form-card">
    <h2><?= $id ? 'Edit Product' : 'Add Product' ?></h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" value="<?= sanitize_output($product['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Slug (URL Identifier)</label>
            <input type="text" name="slug" value="<?= sanitize_output($product['slug'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Category</label>
            <select name="category_id" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($product['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                        <?= sanitize_output($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Price ($)</label>
            <input type="number" step="0.01" name="price" value="<?= $product['price'] ?? '0.00' ?>" required>
        </div>
        <div class="form-group">
            <label>GLB Model URL or Path</label>
            <input type="text" name="glb_path" value="<?= sanitize_output($product['glb_path'] ?? '') ?>" placeholder="models/item.glb or https://..." required>
        </div>
        <div class="form-group">
            <label>Thumbnail Image Upload</label>
            <input type="file" name="thumb_file" accept="image/*">
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px;">
            <div class="form-group"><label>Width (cm)</label><input type="number" step="0.1" name="width_cm" value="<?= $product['width_cm'] ?? '' ?>"></div>
            <div class="form-group"><label>Height (cm)</label><input type="number" step="0.1" name="height_cm" value="<?= $product['height_cm'] ?? '' ?>"></div>
            <div class="form-group"><label>Depth (cm)</label><input type="number" step="0.1" name="depth_cm" value="<?= $product['depth_cm'] ?? '' ?>"></div>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4"><?= sanitize_output($product['description'] ?? '') ?></textarea>
        </div>
        <div class="form-group" style="display:flex; align-items:center; gap:10px;">
            <input type="checkbox" name="is_active" value="1" id="is_active" <?= ($product['is_active'] ?? 1) ? 'checked' : '' ?> style="width:auto;">
            <label for="is_active" style="margin:0;">Active (Visible in Catalog)</label>
        </div>
        <button type="submit" class="btn">Save Product</button>
    </form>
</div>
<?php
render_admin_footer();