<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/db.php';

/** Build a full URL relative to BASE_URL */
if (!function_exists('url')) {
    function url($path = '') {
        return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('user')) {
    function user() {
        return $_SESSION['user'] ?? null;
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        return isset($_SESSION['user']);
    }
}

if (!function_exists('is_admin')) {
    function is_admin() {
        return is_logged_in() && (($_SESSION['user']['role'] ?? '') === 'admin');
    }
}

if (!function_exists('require_login')) {
    function require_login() {
        if (!is_logged_in()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? url('public/index.php');
            header('Location: ' . url('public/login.php'));
            exit;
        }
    }
}

if (!function_exists('require_admin')) {
    function require_admin() {
        if (!is_admin()) {
            header('Location: ' . url('admin/login.php'));
            exit;
        }
    }
}

if (!function_exists('refresh_user')) {
    function refresh_user() {
        if (!is_logged_in()) return;
        global $pdo;
        $stmt = $pdo->prepare("SELECT id,name,email,role FROM users WHERE id=? LIMIT 1");
        $stmt->execute([$_SESSION['user']['id']]);
        if ($u = $stmt->fetch()) {
            $_SESSION['user'] = $u;
        }
    }
}
