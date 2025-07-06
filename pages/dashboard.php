<?php
require_once '../config.php';

if (!isLoggedIn()) {
    redirect('../pages/login.php');
}

$page_title = 'Dashboard';

try {
    $stmt = $pdo->prepare("
        SELECT * FROM games 
        WHERE user_id = ? 
        ORDER BY personal_rating DESC, created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $games = $stmt->fetchAll();
    
    $total_games = count($games);
    $avg_rating = $total_games > 0 ? array_sum(array_column($games, 'personal_rating')) / $total_games : 0;
    
    $platforms = [];
    foreach ($games as $game) {
        $platform = $game['platform'];
        $platforms[$platform] = ($platforms[$platform] ?? 0) + 1;
    }
    
} catch (PDOException $e) {
    $games = [];
    $total_games = 0;
    $avg_rating = 0;
    $platforms = [];
}
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container">
    <div class="hero-retro">
        <h1 class="mb-3">
            <i class="fas fa-tachometer-alt me-3"></i>
            Dashboard de <?php echo htmlspecialchars($_SESSION['username']); ?>
        </h1>
        <p class="lead" style="color: rgba(255, 255, 255, 0.9);">
            Gerencie sua coleção pessoal de jogos retrô!
        </p>
    </div>

    <div class="row mt-5 g-4">
        <div class="col-md-4">
            <div class="card-retro p-4 text-center">
                <i class="fas fa-gamepad mb-3" style="font-size: 3rem; color: var(--neon-blue);"></i>
                <h3 style="font-size: 2rem; color: var(--neon-blue);"><?php echo $total_games; ?></h3>
                <p style="color: rgba(255, 255, 255, 0.8);">Jogos Cadastrados</p>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card-retro p-4 text-center">
                <i class="fas fa-star mb-3" style="font-size: 3rem; color: var(--neon-yellow);"></i>
                <h3 style="font-size: 2rem; color: var(--neon-yellow);">
                    <?php echo number_format($avg_rating, 1); ?>
                </h3>
                <p style="color: rgba(255, 255, 255, 0.8);">Nota Média</p>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card-retro p-4 text-center">
                <i class="fas fa-tv mb-3" style="font-size: 3rem; color: var(--neon-green);"></i>
                <h3 style="font-size: 2rem; color: var(--neon-green);"><?php echo count($platforms); ?></h3>
                <p style="color: rgba(255, 255, 255, 0.8);">Plataformas</p>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>
                    <i class="fas fa-list me-2"></i>
                    Minha Coleção
                </h2>
                <a href="add-game.php" class="btn btn-retro">
                    <i class="fas fa-plus-circle me-2"></i>
                    Adicionar Jogo
                </a>
            </div>
        </div>
    </div>

    <?php if (empty($games)): ?>
        <div class="row">
            <div class="col-12">
                <div class="card-retro p-5 text-center">
                    <i class="fas fa-gamepad mb-4" style="font-size: 5rem; color: var(--neon-blue);"></i>
                    <h3>Nenhum jogo cadastrado ainda!</h3>
                    <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 2rem;">
                        Que tal começar adicionando seu primeiro jogo clássico favorito?
                    </p>
                    <a href="add-game.php" class="btn btn-retro btn-lg">
                        <i class="fas fa-plus-circle me-2"></i>
                        Adicionar Primeiro Jogo
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($games as $game): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="game-card p-3 h-100">
                        <?php if ($game['image_url']): ?>
                            <img src="<?php echo htmlspecialchars($game['image_url']); ?>" 
                                 class="card-img-top mb-3" 
                                 style="height: 200px; object-fit: cover; border-radius: 10px;"
                                 alt="<?php echo htmlspecialchars($game['title']); ?>">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center mb-3" 
                                 style="height: 200px; background: var(--gradient-bg); border-radius: 10px; border: 2px dashed var(--neon-blue);">
                                <i class="fas fa-gamepad" style="font-size: 4rem; color: var(--neon-blue);"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 style="color: var(--neon-green); flex: 1; margin-right: 1rem;">
                                <?php echo htmlspecialchars($game['title']); ?>
                            </h5>
                            <span class="game-rating">
                                <?php echo number_format($game['personal_rating'], 1); ?>
                            </span>
                        </div>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <small style="color: var(--neon-blue); font-weight: bold;">Plataforma:</small><br>
                                <small style="color: rgba(255, 255, 255, 0.8);">
                                    <?php echo htmlspecialchars($game['platform']); ?>
                                </small>
                            </div>
                            <div class="col-6">
                                <small style="color: var(--neon-blue); font-weight: bold;">Ano:</small><br>
                                <small style="color: rgba(255, 255, 255, 0.8);">
                                    <?php echo htmlspecialchars($game['release_year']); ?>
                                </small>
                            </div>
                            <div class="col-6">
                                <small style="color: var(--neon-blue); font-weight: bold;">Gênero:</small><br>
                                <small style="color: rgba(255, 255, 255, 0.8);">
                                    <?php echo htmlspecialchars($game['genre']); ?>
                                </small>
                            </div>
                            <div class="col-6">
                                <small style="color: var(--neon-blue); font-weight: bold;">Adicionado:</small><br>
                                <small style="color: rgba(255, 255, 255, 0.8);">
                                    <?php echo date('d/m/Y', strtotime($game['created_at'])); ?>
                                </small>
                            </div>
                        </div>
                        
                        <?php if ($game['personal_comment']): ?>
                            <div class="mb-3">
                                <small style="color: var(--neon-blue); font-weight: bold;">Comentário:</small>
                                <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem; margin-top: 0.5rem;">
                                    <?php echo nl2br(htmlspecialchars($game['personal_comment'])); ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="d-flex gap-2 mt-auto">
                            <a href="edit-game.php?id=<?php echo $game['id']; ?>" 
                               class="btn btn-retro-secondary btn-sm flex-fill">
                                <i class="fas fa-edit me-1"></i>
                                Editar
                            </a>
                            <button class="btn btn-outline-danger btn-sm" 
                                    onclick="confirmDelete(<?php echo $game['id']; ?>, '<?php echo addslashes($game['title']); ?>')">
                                <i class="fas fa-trash me-1"></i>
                                Excluir
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (!empty($platforms)): ?>
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card-retro p-4">
                        <h3 class="text-center mb-4">
                            <i class="fas fa-chart-bar me-2"></i>
                            Jogos por Plataforma
                        </h3>
                        <div class="row g-3">
                            <?php foreach ($platforms as $platform => $count): ?>
                                <div class="col-md-3 col-sm-6">
                                    <div class="text-center p-3" style="background: rgba(0, 191, 255, 0.1); border-radius: 10px; border: 1px solid var(--neon-blue);">
                                        <h5 style="color: var(--neon-blue); font-size: 1rem;">
                                            <?php echo htmlspecialchars($platform); ?>
                                        </h5>
                                        <p style="color: white; font-size: 1.5rem; margin: 0;">
                                            <?php echo $count; ?> jogo<?php echo $count > 1 ? 's' : ''; ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: var(--card-bg); border: 2px solid var(--neon-pink);">
            <div class="modal-header" style="border-color: var(--neon-pink);">
                <h5 class="modal-title" style="color: var(--neon-pink);">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirmar Exclusão
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <p style="color: rgba(255, 255, 255, 0.9);">
                    Tem certeza que deseja excluir o jogo <strong id="gameTitle" style="color: var(--neon-yellow);"></strong>?
                </p>
                <p style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">
                    Esta ação não pode ser desfeita!
                </p>
            </div>
            <div class="modal-footer" style="border-color: var(--neon-pink);">
                <button type="button" class="btn btn-retro-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Cancelar
                </button>
                <button type="button" class="btn btn-outline-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-2"></i>
                    Excluir
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let gameToDelete = null;

function confirmDelete(gameId, gameTitle) {
    gameToDelete = gameId;
    document.getElementById('gameTitle').textContent = gameTitle;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (gameToDelete) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '../actions/delete-game.php';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'game_id';
        input.value = gameToDelete;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
});
</script>

<?php include '../includes/footer.php'; ?> 