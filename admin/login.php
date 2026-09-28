<?php
define('APP_SECURE', true);
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        if (login_admin($username, $password, $pdo)) {
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    } else {
        $error = 'Please fill in all fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - LuxeAR</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body style="background:var(--bg-light); display:flex; align-items:center; justify-content:center; min-height:100vh;">
    <div style="background:#fff; padding:40px; border-radius:12px; border:1px solid var(--border-color); width:100%; max-width:400px; box-shadow:var(--shadow);">
        <h2 style="text-align:center; margin-bottom:20px;">Admin Login</h2>
        <?php if ($error): ?>
            <div style="background:#fee2e2; color:#dc2626; padding:10px; border-radius:6px; font-size:0.85rem; margin-bottom:15px; text-align:center;"><?= sanitize_output($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div style="margin-bottom:15px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Username</label>
                <input type="text" name="username" required style="width:100%; padding:10px; border:1px solid var(--border-color); border-radius:6px;">
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block; font-weight:600; margin-bottom:5px;">Password</label>
                <input type="password" name="password" required style="width:100%; padding:10px; border:1px solid var(--border-color); border-radius:6px;">
            </div>
            <button type="submit" class="btn" style="width:100%;">Sign In</button>
        </form>
    </div>
</body>
</html>