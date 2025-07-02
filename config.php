<?php
session_start();

// Configurações do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'retro_games');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configurações do site
define('SITE_NAME', 'Retro Games Vault');
define('SITE_URL', 'http://localhost');

// Função para verificar se usuário está logado
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Função para redirecionar
function redirect($url) {
    header("Location: $url");
    exit();
}

// Incluir conexão com banco
require_once 'db/connection.php';
?> 