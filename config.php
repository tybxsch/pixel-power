<?php
session_start();

// Configurações do banco de dados
define('DB_HOST', 'localhost:3306');
define('DB_NAME', 'pixel_power');
define('DB_USER', 'root');
define('DB_PASS', 'senha123456');

// Configurações do site
define('SITE_NAME', 'Pixel Power');
define('SITE_URL', 'http://localhost:3000');

// Função para verificar se usuário está logado
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Função para redirecionar
function redirect($url) {
    header("Location: $url");
    exit();
}

// Função para obter o caminho correto dos assets
function getAssetPath($path) {
    $current_dir = dirname($_SERVER['PHP_SELF']);
    if (strpos($current_dir, '/pages') !== false) {
        return '../' . $path;
    }
    return $path;
}

// Incluir conexão com banco
require_once 'db/connection.php';
?> 