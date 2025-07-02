<?php
require_once 'config.php';
$page_title = 'Início';
?>

<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div class="container">
    <!-- Hero Section -->
    <div class="hero-retro">
        <h1 class="hero-title mb-4">
            <i class="fas fa-gamepad me-3"></i>
            Retro Games Vault
        </h1>
        <p class="lead mb-4" style="color: rgba(255, 255, 255, 0.9); font-size: 1.3rem;">
            Preserve suas memórias dos jogos clássicos e crie seu ranking pessoal dos melhores games retrô de todos os tempos!
        </p>
        <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
            <?php if (!isLoggedIn()): ?>
                <a href="pages/register.php" class="btn btn-retro btn-lg">
                    <i class="fas fa-rocket me-2"></i>
                    Comece Agora
                </a>
                <a href="pages/login.php" class="btn btn-retro-secondary btn-lg">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    Já Tenho Conta
                </a>
            <?php else: ?>
                <a href="pages/dashboard.php" class="btn btn-retro btn-lg">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Meu Dashboard
                </a>
                <a href="pages/add-game.php" class="btn btn-retro-secondary btn-lg">
                    <i class="fas fa-plus-circle me-2"></i>
                    Adicionar Jogo
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="text-center mb-5">
                <i class="fas fa-star me-2"></i>
                Por que usar o Retro Games Vault?
            </h2>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card-retro p-4 h-100 text-center">
                <div class="mb-3">
                    <i class="fas fa-trophy" style="font-size: 3rem; color: var(--neon-yellow);"></i>
                </div>
                <h3 style="font-size: 1rem;">Ranking Pessoal</h3>
                <p style="color: rgba(255, 255, 255, 0.8);">
                    Crie seu próprio ranking dos jogos clássicos que marcaram sua vida. 
                    Avalie, comente e organize seus games favoritos!
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-retro p-4 h-100 text-center">
                <div class="mb-3">
                    <i class="fas fa-memory" style="font-size: 3rem; color: var(--neon-pink);"></i>
                </div>
                <h3 style="font-size: 1rem;">Preserve Memórias</h3>
                <p style="color: rgba(255, 255, 255, 0.8);">
                    Guarde suas lembranças e impressões dos jogos que fizeram parte da sua infância 
                    e adolescência. Nunca mais esqueça!
                </p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-retro p-4 h-100 text-center">
                <div class="mb-3">
                    <i class="fas fa-gamepad" style="font-size: 3rem; color: var(--neon-green);"></i>
                </div>
                <h3 style="font-size: 1rem;">Multiplataforma</h3>
                <p style="color: rgba(255, 255, 255, 0.8);">
                    Super Nintendo, Mega Drive, PlayStation, Arcade, PC... 
                    Organize jogos de todas as plataformas retrô em um só lugar!
                </p>
            </div>
        </div>
    </div>

    <!-- Platforms Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="text-center mb-5">
                <i class="fas fa-tv me-2"></i>
                Plataformas Suportadas
            </h2>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
            <div class="card-retro p-3 text-center h-100">
                <i class="fas fa-gamepad mb-2" style="color: var(--neon-blue); font-size: 2rem;"></i>
                <small style="color: rgba(255, 255, 255, 0.9);">Super Nintendo</small>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
            <div class="card-retro p-3 text-center h-100">
                <i class="fas fa-gamepad mb-2" style="color: var(--neon-green); font-size: 2rem;"></i>
                <small style="color: rgba(255, 255, 255, 0.9);">Mega Drive</small>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
            <div class="card-retro p-3 text-center h-100">
                <i class="fas fa-compact-disc mb-2" style="color: var(--neon-purple); font-size: 2rem;"></i>
                <small style="color: rgba(255, 255, 255, 0.9);">PlayStation</small>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
            <div class="card-retro p-3 text-center h-100">
                <i class="fas fa-desktop mb-2" style="color: var(--neon-yellow); font-size: 2rem;"></i>
                <small style="color: rgba(255, 255, 255, 0.9);">Arcade</small>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
            <div class="card-retro p-3 text-center h-100">
                <i class="fas fa-laptop mb-2" style="color: var(--neon-pink); font-size: 2rem;"></i>
                <small style="color: rgba(255, 255, 255, 0.9);">PC Retro</small>
            </div>
        </div>
        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
            <div class="card-retro p-3 text-center h-100">
                <i class="fas fa-plus mb-2" style="color: var(--neon-blue); font-size: 2rem;"></i>
                <small style="color: rgba(255, 255, 255, 0.9);">E Muito Mais</small>
            </div>
        </div>
    </div>

    <!-- Games Preview Section -->
    <?php if (isLoggedIn()): ?>
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center mb-5">
                    <i class="fas fa-chart-line me-2"></i>
                    Seus Jogos Recentes
                </h2>
            </div>
        </div>

        <?php
        // Buscar os últimos 3 jogos do usuário
        try {
            $stmt = $pdo->prepare("
                SELECT * FROM games 
                WHERE user_id = ? 
                ORDER BY created_at DESC 
                LIMIT 3
            ");
            $stmt->execute([$_SESSION['user_id']]);
            $recent_games = $stmt->fetchAll();
        } catch (PDOException $e) {
            $recent_games = [];
        }
        ?>

        <?php if (!empty($recent_games)): ?>
            <div class="row g-4">
                <?php foreach ($recent_games as $game): ?>
                    <div class="col-md-4">
                        <div class="game-card p-3">
                            <?php if ($game['image_url']): ?>
                                <img src="<?php echo htmlspecialchars($game['image_url']); ?>" 
                                     class="card-img-top mb-3" 
                                     style="height: 200px; object-fit: cover; border-radius: 10px;"
                                     alt="<?php echo htmlspecialchars($game['title']); ?>">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center mb-3" 
                                     style="height: 200px; background: var(--gradient-bg); border-radius: 10px;">
                                    <i class="fas fa-gamepad" style="font-size: 4rem; color: var(--neon-blue);"></i>
                                </div>
                            <?php endif; ?>
                            
                            <h5 style="color: var(--neon-green);">
                                <?php echo htmlspecialchars($game['title']); ?>
                            </h5>
                            <p class="mb-2">
                                <strong style="color: var(--neon-blue);">Plataforma:</strong>
                                <span style="color: rgba(255, 255, 255, 0.8);">
                                    <?php echo htmlspecialchars($game['platform']); ?>
                                </span>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="game-rating">
                                    <?php echo number_format($game['personal_rating'], 1); ?>
                                </span>
                                <small style="color: rgba(255, 255, 255, 0.6);">
                                    <?php echo date('d/m/Y', strtotime($game['created_at'])); ?>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-4">
                <a href="pages/dashboard.php" class="btn btn-retro">
                    <i class="fas fa-list me-2"></i>
                    Ver Todos os Jogos
                </a>
            </div>
        <?php else: ?>
            <div class="text-center">
                <div class="card-retro p-5">
                    <i class="fas fa-plus-circle mb-3" style="font-size: 4rem; color: var(--neon-green);"></i>
                    <h3>Adicione seu primeiro jogo!</h3>
                    <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 2rem;">
                        Comece criando seu ranking pessoal dos melhores jogos retrô.
                    </p>
                    <a href="pages/add-game.php" class="btn btn-retro btn-lg">
                        <i class="fas fa-gamepad me-2"></i>
                        Adicionar Primeiro Jogo
                    </a>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- CTA Section -->
    <?php if (!isLoggedIn()): ?>
        <div class="row mt-5">
            <div class="col-12">
                <div class="card-retro p-5 text-center">
                    <h2 class="mb-4">
                        <i class="fas fa-rocket me-2"></i>
                        Pronto para começar?
                    </h2>
                    <p class="lead mb-4" style="color: rgba(255, 255, 255, 0.8);">
                        Junte-se à comunidade de gamers retrô e preserve suas memórias dos jogos clássicos!
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="pages/register.php" class="btn btn-retro btn-lg">
                            <i class="fas fa-user-plus me-2"></i>
                            Criar Conta Grátis
                        </a>
                        <a href="pages/sobre.php" class="btn btn-retro-secondary btn-lg">
                            <i class="fas fa-info-circle me-2"></i>
                            Saiba Mais
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?> 