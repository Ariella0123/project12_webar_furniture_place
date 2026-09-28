<?php
function render_admin_header(string $title = 'Dashboard'): void {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?> - LuxeAR Admin</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .admin-nav { background: var(--primary); padding: 15px 0; color:#fff; }
        .admin-nav a { color: #94a3b8; margin-right: 20px; font-weight: 600; }
        .admin-nav a:hover, .admin-nav a.active { color: #fff; }
        .admin-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; margin-top: 20px; }
        .admin-table th, .admin-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid var(--border-color); }
        .admin-table th { background: #f8fafc; font-weight: 600; }
        .form-card { background: #fff; padding: 25px; border-radius: 8px; border: 1px solid var(--border-color); max-width: 600px; margin: 30px auto; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 5px; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid var(--border-color); border-radius: 6px; }
        .btn { padding: 10px 20px; background: var(--accent); color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; display: inline-block; }
    </style>
</head>
<body>
    <div class="admin-nav">
        <div class="container" style="display:flex; justify-content:space-between;">
            <div>
                <strong>LuxeAR Admin</strong> &nbsp;|&nbsp; 
                <a href="index.php">Products</a>
                <a href="categories.php">Categories</a>
            </div>
            <div>
                <a href="../index.php" target="_blank">View Storefront ↗</a>
                <a href="logout.php" style="color:#ef4444;">Logout</a>
            </div>
        </div>
    </div>
    <div class="container" style="padding-top: 30px;">
<?php
}

function render_admin_footer(): void {
?>
    </div>
</body>
</html>
<?php
}