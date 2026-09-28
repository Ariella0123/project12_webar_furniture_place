<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function check_admin_login(): bool {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

function require_admin_auth(): void {
    if (!check_admin_login()) {
        header('Location: login.php');
        exit;
    }
}

function login_admin(string $username, string $password, PDO $pdo): bool {
    $stmt = $pdo->prepare("SELECT id, username, password_hash FROM admins WHERE username = :username LIMIT 1");
    $stmt->execute(['username' => $username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        return true;
    }
    return false;
}

function logout_admin(): void {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    session_destroy();
}