<?php
define('APP_SECURE', true);
require_once __DIR__ . '/../includes/auth.php';
logout_admin();
header('Location: login.php');
exit;