<?php
require_once '../config.php';

if (!isLoggedIn()) {
    redirect('../pages/login.php');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['game_id'])) {
    redirect('../pages/dashboard.php');
}

$game_id = (int)$_POST['game_id'];

try {
    $stmt = $pdo->prepare("SELECT id FROM games WHERE id = ? AND user_id = ?");
    $stmt->execute([$game_id, $_SESSION['user_id']]);
    
    if (!$stmt->fetch()) {
        redirect('../pages/dashboard.php');
    }
    
    // Deletar o jogo
    $stmt = $pdo->prepare("DELETE FROM games WHERE id = ? AND user_id = ?");
    $stmt->execute([$game_id, $_SESSION['user_id']]);
    
    redirect('../pages/dashboard.php');
    
} catch (PDOException $e) {
    redirect('../pages/dashboard.php');
}
?> 