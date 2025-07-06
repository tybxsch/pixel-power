    <footer class="mt-5" style="background: var(--card-bg); border-top: 2px solid var(--neon-purple); padding: 2rem 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 style="color: var(--neon-purple); font-family: 'Press Start 2P', cursive; font-size: 1rem;">
                        <i class="fas fa-gamepad me-2"></i>
                        <?php echo SITE_NAME; ?>
                    </h5>
                    <p class="mt-3" style="color: rgba(255, 255, 255, 0.8);">
                        Preserve suas memórias dos jogos clássicos e crie seu ranking pessoal dos melhores games retrô de todos os tempos!
                    </p>
                </div>
                <div class="col-md-3">
                    <h6 style="color: var(--neon-blue); text-transform: uppercase; font-weight: bold;">
                        Links Rápidos
                    </h6>
                    <ul class="list-unstyled mt-3">
                        <li><a href="<?php echo SITE_URL; ?>" style="color: rgba(255, 255, 255, 0.7); text-decoration: none;">Início</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/sobre.php" style="color: rgba(255, 255, 255, 0.7); text-decoration: none;">Sobre</a></li>
                        <?php if (!isLoggedIn()): ?>
                            <li><a href="<?php echo SITE_URL; ?>/pages/login.php" style="color: rgba(255, 255, 255, 0.7); text-decoration: none;">Login</a></li>
                            <li><a href="<?php echo SITE_URL; ?>/pages/register.php" style="color: rgba(255, 255, 255, 0.7); text-decoration: none;">Cadastro</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <hr style="border-color: var(--neon-purple); margin: 2rem 0;">
            <div class="row">
                <div class="col-12 text-center">
                    <p style="color: rgba(255, 255, 255, 0.6); margin: 0;">
                        © Desenvolvida para fins educativos. Pontifícia Universidade Católica do Paraná.
                    </p>
                    <p style="color: rgba(255, 255, 255, 0.4); font-size: 0.8rem; margin-top: 0.5rem;">
                        <i class="fas fa-code"></i> Eduarda Tybusch, 2025.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="<?php echo getAssetPath('assets/js/retro-effects.js'); ?>"></script>
</body>
</html> 