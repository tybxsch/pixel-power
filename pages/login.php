<?php
require_once '../config.php';

if (isLoggedIn()) {
    redirect('../pages/dashboard.php');
}

$page_title = 'Login';
$error_message = '';

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
            <div class="hero-retro text-center mb-4">
                <h1 class="mb-3">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Entrar
                </h1>
                <p style="color: rgba(255, 255, 255, 0.8);">
                    Entre no seu universo de jogos retrô!
                </p>
            </div>

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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function() {
        const stopLoading = showLoading(submitBtn);
        
        setTimeout(() => {
            if (document.querySelector('.alert-danger-retro')) {
                stopLoading();
            }
        }, 100);
    });
    
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