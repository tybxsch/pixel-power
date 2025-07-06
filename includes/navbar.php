<nav class="navbar navbar-expand-lg navbar-retro fixed-top">
    <div class="container">
        <a class="navbar-brand glitch" href="<?php echo SITE_URL; ?>">
            <i class="fas fa-gamepad me-2"></i>
            <?php echo SITE_NAME; ?>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                style="border: 2px solid var(--neon-purple); background: transparent;">
            <span style="color: var(--neon-purple);">
                <i class="fas fa-bars"></i>
            </span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo SITE_URL; ?>">
                        <i class="fas fa-home me-1"></i>
                        Início
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo SITE_URL; ?>/pages/sobre.php">
                        <i class="fas fa-info-circle me-1"></i>
                        Sobre
                    </a>
                </li>
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/pages/dashboard.php">
                            <i class="fas fa-tachometer-alt me-1"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/pages/add-game.php">
                            <i class="fas fa-plus-circle me-1"></i>
                            Adicionar Jogo
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            
            <ul class="navbar-nav">
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" 
                           data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <ul class="dropdown-menu" style="background: var(--card-bg); border: 2px solid var(--neon-blue);">
                            <li>
                                <a class="dropdown-item" href="<?php echo SITE_URL; ?>/pages/profile.php" 
                                   style="color: var(--neon-blue);">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Perfil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider" style="border-color: var(--neon-purple);"></li>
                            <li>
                                <a class="dropdown-item" href="<?php echo SITE_URL; ?>/actions/logout.php" 
                                   style="color: var(--neon-pink);">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    Sair
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/pages/login.php">
                            <i class="fas fa-sign-in-alt me-1"></i>
                            Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>/pages/register.php">
                            <i class="fas fa-user-plus me-1"></i>
                            Cadastro
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div style="height: 80px;"></div> 