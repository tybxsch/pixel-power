<?php
require_once '../config.php';

// Verificar se está logado
if (!isLoggedIn()) {
    redirect('../pages/login.php');
}

$page_title = 'Perfil';
$error_message = '';
$success_message = '';

// Buscar dados do usuário
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    
    if (!$user) {
        redirect('../pages/login.php');
    }
    
    // Buscar estatísticas do usuário
    $stmt = $pdo->prepare("SELECT COUNT(*) as total_games, AVG(personal_rating) as avg_rating FROM games WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $stats = $stmt->fetch();
    
    // Buscar jogo mais bem avaliado
    $stmt = $pdo->prepare("SELECT title, personal_rating FROM games WHERE user_id = ? ORDER BY personal_rating DESC LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    $favorite_game = $stmt->fetch();
    
} catch (PDOException $e) {
    $error_message = 'Erro ao carregar dados do perfil!';
    $stats = ['total_games' => 0, 'avg_rating' => 0];
    $favorite_game = null;
}

// Processar formulário de edição
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'update_profile') {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        
        // Validações
        if (empty($username) || empty($email)) {
            $error_message = 'Por favor, preencha todos os campos!';
        } elseif (strlen($username) < 3) {
            $error_message = 'O nome de usuário deve ter pelo menos 3 caracteres!';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = 'Por favor, digite um email válido!';
        } else {
            try {
                // Verificar se username ou email já existem (exceto o próprio usuário)
                $stmt = $pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
                $stmt->execute([$username, $email, $_SESSION['user_id']]);
                
                if ($stmt->fetch()) {
                    $error_message = 'Nome de usuário ou email já estão em uso!';
                } else {
                    // Atualizar dados
                    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
                    
                    if ($stmt->execute([$username, $email, $_SESSION['user_id']])) {
                        $_SESSION['username'] = $username;
                        $user['username'] = $username;
                        $user['email'] = $email;
                        $success_message = 'Perfil atualizado com sucesso!';
                    } else {
                        $error_message = 'Erro ao atualizar perfil. Tente novamente!';
                    }
                }
            } catch (PDOException $e) {
                $error_message = 'Erro no sistema. Tente novamente!';
            }
        }
    } elseif ($action == 'change_password') {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_new_password = $_POST['confirm_new_password'] ?? '';
        
        // Validações
        if (empty($current_password) || empty($new_password) || empty($confirm_new_password)) {
            $error_message = 'Por favor, preencha todos os campos da senha!';
        } elseif (!password_verify($current_password, $user['password'])) {
            $error_message = 'Senha atual incorreta!';
        } elseif (strlen($new_password) < 6) {
            $error_message = 'A nova senha deve ter pelo menos 6 caracteres!';
        } elseif ($new_password !== $confirm_new_password) {
            $error_message = 'As senhas não coincidem!';
        } else {
            try {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                
                if ($stmt->execute([$hashed_password, $_SESSION['user_id']])) {
                    $success_message = 'Senha alterada com sucesso!';
                } else {
                    $error_message = 'Erro ao alterar senha. Tente novamente!';
                }
            } catch (PDOException $e) {
                $error_message = 'Erro no sistema. Tente novamente!';
            }
        }
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container">
    <!-- Hero Section -->
    <div class="hero-retro">
        <h1 class="mb-3">
            <i class="fas fa-user-edit me-3"></i>
            Perfil de <?php echo htmlspecialchars($user['username']); ?>
        </h1>
        <p class="lead" style="color: rgba(255, 255, 255, 0.9);">
            Gerencie suas informações pessoais e veja suas estatísticas gaming!
        </p>
    </div>

    <!-- Mensagens de erro e sucesso -->
    <?php if ($error_message): ?>
        <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <?php if ($success_message): ?>
        <div class="alert alert-success" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>

    <!-- Estatísticas do Usuário -->
    <div class="row mt-5 g-4">
        <div class="col-md-3">
            <div class="card-retro p-4 text-center">
                <i class="fas fa-gamepad mb-3" style="font-size: 3rem; color: var(--neon-blue);"></i>
                <h3 style="font-size: 2rem; color: var(--neon-blue);"><?php echo $stats['total_games']; ?></h3>
                <p style="color: rgba(255, 255, 255, 0.8);">Jogos Cadastrados</p>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card-retro p-4 text-center">
                <i class="fas fa-star mb-3" style="font-size: 3rem; color: var(--neon-yellow);"></i>
                <h3 style="font-size: 2rem; color: var(--neon-yellow);">
                    <?php echo $stats['avg_rating'] ? number_format($stats['avg_rating'], 1) : '0.0'; ?>
                </h3>
                <p style="color: rgba(255, 255, 255, 0.8);">Nota Média</p>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card-retro p-4 text-center">
                <i class="fas fa-calendar mb-3" style="font-size: 3rem; color: var(--neon-green);"></i>
                <h3 style="font-size: 1.2rem; color: var(--neon-green);">
                    <?php echo date('d/m/Y', strtotime($user['created_at'])); ?>
                </h3>
                <p style="color: rgba(255, 255, 255, 0.8);">Membro desde</p>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card-retro p-4 text-center">
                <i class="fas fa-trophy mb-3" style="font-size: 3rem; color: var(--neon-pink);"></i>
                <h3 style="font-size: 1.2rem; color: var(--neon-pink);">
                    <?php echo $favorite_game ? htmlspecialchars($favorite_game['title']) : 'Nenhum'; ?>
                </h3>
                <p style="color: rgba(255, 255, 255, 0.8);">
                    <?php echo $favorite_game ? 'Nota: ' . number_format($favorite_game['personal_rating'], 1) : 'Adicione jogos!'; ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Formulários de Edição -->
    <div class="row mt-5">
        <!-- Editar Perfil -->
        <div class="col-md-6">
            <div class="card-retro p-4">
                <h3 class="mb-4">
                    <i class="fas fa-user-edit me-2"></i>
                    Editar Perfil
                </h3>
                
                <form method="POST" class="form-retro">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <i class="fas fa-user me-2"></i>
                            Nome de Usuário
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="username" 
                               name="username" 
                               value="<?php echo htmlspecialchars($user['username']); ?>"
                               required
                               minlength="3">
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>
                            Email
                        </label>
                        <input type="email" 
                               class="form-control" 
                               id="email" 
                               name="email" 
                               value="<?php echo htmlspecialchars($user['email']); ?>"
                               required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-retro">
                            <i class="fas fa-save me-2"></i>
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Alterar Senha -->
        <div class="col-md-6">
            <div class="card-retro p-4">
                <h3 class="mb-4">
                    <i class="fas fa-lock me-2"></i>
                    Alterar Senha
                </h3>
                
                <form method="POST" class="form-retro">
                    <input type="hidden" name="action" value="change_password">
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">
                            <i class="fas fa-key me-2"></i>
                            Senha Atual
                        </label>
                        <input type="password" 
                               class="form-control" 
                               id="current_password" 
                               name="current_password" 
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">
                            <i class="fas fa-lock me-2"></i>
                            Nova Senha
                        </label>
                        <input type="password" 
                               class="form-control" 
                               id="new_password" 
                               name="new_password" 
                               required
                               minlength="6">
                    </div>
                    
                    <div class="mb-4">
                        <label for="confirm_new_password" class="form-label">
                            <i class="fas fa-lock me-2"></i>
                            Confirmar Nova Senha
                        </label>
                        <input type="password" 
                               class="form-control" 
                               id="confirm_new_password" 
                               name="confirm_new_password" 
                               required
                               minlength="6">
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-retro">
                            <i class="fas fa-shield-alt me-2"></i>
                            Alterar Senha
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Informações Adicionais -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card-retro p-4">
                <h3 class="text-center mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    Informações da Conta
                </h3>
                
                <div class="row g-4">
                    <div class="col-md-4 text-center">
                        <i class="fas fa-id-card mb-3" style="font-size: 2.5rem; color: var(--neon-blue);"></i>
                        <h4 style="font-size: 1.1rem;">ID do Usuário</h4>
                        <p style="color: rgba(255, 255, 255, 0.8);">
                            #<?php echo $user['id']; ?>
                        </p>
                    </div>
                    
                    <div class="col-md-4 text-center">
                        <i class="fas fa-clock mb-3" style="font-size: 2.5rem; color: var(--neon-green);"></i>
                        <h4 style="font-size: 1.1rem;">Conta Criada</h4>
                        <p style="color: rgba(255, 255, 255, 0.8);">
                            <?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?>
                        </p>
                    </div>
                    
                    <div class="col-md-4 text-center">
                        <i class="fas fa-shield-alt mb-3" style="font-size: 2.5rem; color: var(--neon-yellow);"></i>
                        <h4 style="font-size: 1.1rem;">Segurança</h4>
                        <p style="color: rgba(255, 255, 255, 0.8);">
                            Senha protegida por hash
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Links Úteis -->
    <div class="row mt-5">
        <div class="col-12 text-center">
            <div class="card-retro p-4">
                <h3 class="mb-4">
                    <i class="fas fa-gamepad me-2"></i>
                    Continue sua jornada gaming!
                </h3>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="dashboard.php" class="btn btn-retro">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Ir para Dashboard
                    </a>
                    <a href="add-game.php" class="btn btn-retro-secondary">
                        <i class="fas fa-plus-circle me-2"></i>
                        Adicionar Jogo
                    </a>
                    <a href="../index.php" class="btn btn-retro-secondary">
                        <i class="fas fa-home me-2"></i>
                        Página Inicial
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Validação em tempo real para senhas
document.getElementById('confirm_new_password').addEventListener('input', function() {
    var newPassword = document.getElementById('new_password').value;
    var confirmPassword = this.value;
    
    if (newPassword !== confirmPassword) {
        this.setCustomValidity('As senhas não coincidem');
    } else {
        this.setCustomValidity('');
    }
});

// Limpar campos de senha após submissão
document.addEventListener('DOMContentLoaded', function() {
    // Se houve erro relacionado a senha, limpar campos
    <?php if ($error_message && strpos($error_message, 'senha') !== false): ?>
        document.getElementById('current_password').value = '';
        document.getElementById('new_password').value = '';
        document.getElementById('confirm_new_password').value = '';
    <?php endif; ?>
});
</script>

<?php include '../includes/footer.php'; ?> 