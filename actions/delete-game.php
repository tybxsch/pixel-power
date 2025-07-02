<?php
require_once '../config.php';

// Verificar se está logado
if (!isLoggedIn()) {
    redirect('../pages/login.php');
}

// Verificar se foi enviado o ID do jogo
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['game_id'])) {
    redirect('../pages/dashboard.php');
}

$game_id = (int)$_POST['game_id'];

try {
    // Verificar se o jogo pertence ao usuário logado
    $stmt = $pdo->prepare("SELECT id FROM games WHERE id = ? AND user_id = ?");
    $stmt->execute([$game_id, $_SESSION['user_id']]);
    
    if (!$stmt->fetch()) {
        // Jogo não encontrado ou não pertence ao usuário
        redirect('../pages/dashboard.php');
    }
    
    // Deletar o jogo
    $stmt = $pdo->prepare("DELETE FROM games WHERE id = ? AND user_id = ?");
    $stmt->execute([$game_id, $_SESSION['user_id']]);
    
    // Redirecionar de volta ao dashboard
    redirect('../pages/dashboard.php');
    
} catch (PDOException $e) {
    // Em caso de erro, redirecionar ao dashboard
    redirect('../pages/dashboard.php');
}
?> 