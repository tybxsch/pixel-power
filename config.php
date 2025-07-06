<?php
session_start();

define('DB_HOST', 'localhost:3306');
define('DB_NAME', 'pixel_power');
define('DB_USER', 'root');
define('DB_PASS', 'senha123456');

define('SITE_NAME', 'Pixel Power');
define('SITE_URL', 'http://localhost:3000');

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function getAssetPath($path) {
    $current_dir = dirname($_SERVER['PHP_SELF']);
    if (strpos($current_dir, '/pages') !== false) {
        return '../' . $path;
    }
    return $path;
}

require_once 'db/connection.php';
?> 