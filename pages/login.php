<?php
require_once '../config.php';

// Se já estiver logado, redirecionar
if (isLoggedIn()) {
    redirect('../pages/dashboard.php');
}

$page_title = 'Login';
$error_message = '';

// Processar login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error_message = 'Por favor, preencha todos os campos!';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                redirect('../pages/dashboard.php');
            } else {
                $error_message = 'Usuário ou senha incorretos!';
            }
        } catch (PDOException $e) {
            $error_message = 'Erro no sistema. Tente novamente!';
        }
    }
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <!-- Hero Section -->
            <div class="hero-retro text-center mb-4">
                <h1 class="mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Player Login
                </h1>
                <p style="color: rgba(255, 255, 255, 0.8);">
                    Entre no seu universo de jogos retrô!
                </p>
            </div>

            <!-- Login Form -->
            <div class="card-retro p-4">
                <?php if ($error_message): ?>
                    <div class="alert alert-danger-retro mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="form-retro">
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <i class="fas fa-user me-2"></i>
                            Usuário ou Email
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="username" 
                               name="username" 
                               placeholder="Digite seu usuário ou email"
                               value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                               required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>
                            Senha
                        </label>
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               placeholder="Digite sua senha"
                               required>
                    </div>

                    <div class="d-grid mb-4">
                        <button type="submit" class="btn btn-retro btn-lg">
                            <i class="fas fa-gamepad me-2"></i>
                            Entrar no Game
                        </button>
                    </div>
                </form>

                <div class="text-center">
                    <p style="color: rgba(255, 255, 255, 0.7);">
                        Novo jogador?
                    </p>
                    <a href="register.php" class="btn btn-retro-secondary">
                        <i class="fas fa-user-plus me-2"></i>
                        Criar Nova Conta
                    </a>
                </div>
            </div>

            <!-- Login Tips -->
            <div class="card-retro p-4 mt-4">
                <h5 style="color: var(--neon-green); text-align: center; margin-bottom: 1rem;">
                    <i class="fas fa-lightbulb me-2"></i>
                    Dica de Teste
                </h5>
                <div style="background: rgba(0, 255, 65, 0.1); border: 1px solid var(--neon-green); border-radius: 8px; padding: 1rem;">
                    <p style="color: rgba(255, 255, 255, 0.9); margin: 0; text-align: center;">
                        <strong>Usuário:</strong> retrogamer<br>
                        <strong>Senha:</strong> password<br>
                        <small style="color: rgba(255, 255, 255, 0.7);">
                            (Conta de demonstração com jogos de exemplo)
                        </small>
                    </p>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="../index.php" style="color: var(--neon-blue); text-decoration: none;">
                    <i class="fas fa-arrow-left me-2"></i>
                    Voltar ao Início
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Adicionar efeitos especiais ao formulário de login
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function() {
        const stopLoading = showLoading(submitBtn);
        
        // Se houver erro, parar o loading após um tempo
        setTimeout(() => {
            if (document.querySelector('.alert-danger-retro')) {
                stopLoading();
            }
        }, 100);
    });
    
    // Efeito nos inputs
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.transform = 'scale(1.02)';
        });
        
        input.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>

<?php include '../includes/footer.php'; ?> 