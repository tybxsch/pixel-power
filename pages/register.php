<?php
require_once '../config.php';

if (isLoggedIn()) {
    redirect('../pages/dashboard.php');
}

$page_title = 'Cadastro';
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = 'Por favor, preencha todos os campos!';
    } elseif (strlen($username) < 3) {
        $error_message = 'O nome de usuário deve ter pelo menos 3 caracteres!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Por favor, digite um email válido!';
    } elseif (strlen($password) < 6) {
        $error_message = 'A senha deve ter pelo menos 6 caracteres!';
    } elseif ($password !== $confirm_password) {
        $error_message = 'As senhas não coincidem!';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->fetch()) {
                $error_message = 'Nome de usuário ou email já estão em uso!';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                
                if ($stmt->execute([$username, $email, $hashed_password])) {
                    $success_message = 'Conta criada com sucesso! Agora você pode fazer login.';
                    
                    $_POST = [];
                } else {
                    $error_message = 'Erro ao criar conta. Tente novamente!';
                }
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
                    <i class="fas fa-user-plus me-2"></i>
                    Criar conta
                </h1>
                <p style="color: rgba(255, 255, 255, 0.8);">
                    Junte-se à comunidade dos gamers retrô!
                </p>
            </div>

            <div class="card-retro p-4">
                <?php if ($error_message): ?>
                    <div class="alert alert-danger-retro mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <?php if ($success_message): ?>
                    <div class="alert alert-retro mb-4">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                    <div class="text-center">
                        <a href="login.php" class="btn btn-retro btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Fazer Login Agora
                        </a>
                    </div>
                <?php else: ?>
                    <form method="POST" class="form-retro">
                        <div class="mb-3">
                            <label for="username" class="form-label">
                                <i class="fas fa-user me-2"></i>
                                Nome de Usuário
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="username" 
                                   name="username" 
                                   placeholder="Escolha um nome de usuário único"
                                   value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                                   required
                                   minlength="3">
                            <small style="color: rgba(255, 255, 255, 0.6);">
                                Mínimo 3 caracteres. Este será seu nome gamer!
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>
                                Email
                            </label>
                            <input type="email" 
                                   class="form-control" 
                                   id="email" 
                                   name="email" 
                                   placeholder="seu@email.com"
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>
                                Senha
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Crie uma senha segura"
                                   required
                                   minlength="6">
                            <small style="color: rgba(255, 255, 255, 0.6);">
                                Mínimo 6 caracteres para proteger sua coleção!
                            </small>
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">
                                <i class="fas fa-lock me-2"></i>
                                Confirmar Senha
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   placeholder="Digite a senha novamente"
                                   required
                                   minlength="6">
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-retro btn-lg">
                                <i class="fas fa-gamepad me-2"></i>
                                Criar Conta Gamer
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <p style="color: rgba(255, 255, 255, 0.7);">
                            Já tem uma conta?
                        </p>
                        <a href="login.php" class="btn btn-retro-secondary">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Fazer Login
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="card-retro p-4 mt-4">
                <h5 style="color: var(--neon-green); text-align: center; margin-bottom: 1rem;">
                    <i class="fas fa-star me-2"></i>
                    Ao criar sua conta, você poderá:
                </h5>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="text-center">
                            <i class="fas fa-trophy mb-2" style="color: var(--neon-yellow); font-size: 1.5rem;"></i>
                            <small style="color: rgba(255, 255, 255, 0.8); display: block;">
                                Criar rankings personalizados
                            </small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <i class="fas fa-gamepad mb-2" style="color: var(--neon-pink); font-size: 1.5rem;"></i>
                            <small style="color: rgba(255, 255, 255, 0.8); display: block;">
                                Catalogar jogos retrô
                            </small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <i class="fas fa-comment mb-2" style="color: var(--neon-blue); font-size: 1.5rem;"></i>
                            <small style="color: rgba(255, 255, 255, 0.8); display: block;">
                                Adicionar comentários
                            </small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <i class="fas fa-star mb-2" style="color: var(--neon-green); font-size: 1.5rem;"></i>
                            <small style="color: rgba(255, 255, 255, 0.8); display: block;">
                                Avaliar seus games
                            </small>
                        </div>
                    </div>
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
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const submitBtn = form?.querySelector('button[type="submit"]');
    
    function validatePasswords() {
        if (password.value && confirmPassword.value) {
            if (password.value === confirmPassword.value) {
                confirmPassword.style.borderColor = 'var(--neon-green)';
                confirmPassword.style.boxShadow = '0 0 15px rgba(0, 255, 65, 0.3)';
            } else {
                confirmPassword.style.borderColor = 'var(--neon-pink)';
                confirmPassword.style.boxShadow = '0 0 15px rgba(255, 0, 128, 0.5)';
            }
        }
    }
    
    if (password && confirmPassword) {
        password.addEventListener('input', validatePasswords);
        confirmPassword.addEventListener('input', validatePasswords);
    }
    
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            const stopLoading = showLoading(submitBtn);
            
            setTimeout(() => {
                if (document.querySelector('.alert-danger-retro')) {
                    stopLoading();
                }
            }, 100);
        });
    }
    
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