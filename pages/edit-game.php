<?php
require_once '../config.php';

// Verificar se está logado
if (!isLoggedIn()) {
    redirect('../pages/login.php');
}

// Verificar se foi fornecido um ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('../pages/dashboard.php');
}

$game_id = (int)$_GET['id'];
$page_title = 'Editar Jogo';
$error_message = '';
$success_message = '';

// Buscar o jogo
try {
    $stmt = $pdo->prepare("SELECT * FROM games WHERE id = ? AND user_id = ?");
    $stmt->execute([$game_id, $_SESSION['user_id']]);
    $game = $stmt->fetch();
    
    if (!$game) {
        redirect('../pages/dashboard.php');
    }
} catch (PDOException $e) {
    redirect('../pages/dashboard.php');
}

// Processar formulário de edição
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title'] ?? '');
    $platform = trim($_POST['platform'] ?? '');
    $release_year = trim($_POST['release_year'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $personal_rating = trim($_POST['personal_rating'] ?? '');
    $image_url = trim($_POST['image_url'] ?? '');
    $personal_comment = trim($_POST['personal_comment'] ?? '');
    
    // Validações
    if (empty($title) || empty($platform) || empty($release_year) || empty($genre) || empty($personal_rating)) {
        $error_message = 'Por favor, preencha todos os campos obrigatórios!';
    } elseif (!is_numeric($release_year) || $release_year < 1970 || $release_year > date('Y')) {
        $error_message = 'Por favor, digite um ano válido entre 1970 e ' . date('Y') . '!';
    } elseif (!is_numeric($personal_rating) || $personal_rating < 0 || $personal_rating > 10) {
        $error_message = 'A nota deve estar entre 0 e 10!';
    } elseif (!empty($image_url) && !filter_var($image_url, FILTER_VALIDATE_URL)) {
        $error_message = 'Por favor, digite uma URL válida para a imagem!';
    } else {
        try {
            $stmt = $pdo->prepare("
                UPDATE games 
                SET title = ?, platform = ?, release_year = ?, genre = ?, 
                    personal_rating = ?, image_url = ?, personal_comment = ?, updated_at = NOW()
                WHERE id = ? AND user_id = ?
            ");
            
            if ($stmt->execute([
                $title,
                $platform,
                $release_year,
                $genre,
                $personal_rating,
                $image_url ?: null,
                $personal_comment ?: null,
                $game_id,
                $_SESSION['user_id']
            ])) {
                $success_message = 'Jogo atualizado com sucesso!';
                
                // Atualizar os dados do jogo para refletir as mudanças
                $game['title'] = $title;
                $game['platform'] = $platform;
                $game['release_year'] = $release_year;
                $game['genre'] = $genre;
                $game['personal_rating'] = $personal_rating;
                $game['image_url'] = $image_url;
                $game['personal_comment'] = $personal_comment;
            } else {
                $error_message = 'Erro ao atualizar jogo. Tente novamente!';
            }
        } catch (PDOException $e) {
            $error_message = 'Erro no sistema. Tente novamente!';
        }
    }
}

// Listas para os selects
$platforms = [
    'Super Nintendo', 'Mega Drive', 'PlayStation', 'PlayStation 2', 'Nintendo 64',
    'Game Boy', 'Game Boy Color', 'Game Boy Advance', 'Nintendo DS',
    'Arcade', 'PC', 'Atari 2600', 'Master System', 'Dreamcast', 'Saturn',
    'PC Engine', 'Neo Geo', 'Outro'
];

$genres = [
    'Ação', 'Aventura', 'RPG', 'Plataforma', 'Luta', 'Tiro', 'Corrida',
    'Esporte', 'Estratégia', 'Puzzle', 'Simulação', 'Terror', 'Musical',
    'Arcade', 'Beat em Up', 'Outro'
];
?>

<?php include '../includes/header.php'; ?>
<?php include '../includes/navbar.php'; ?>

<div class="container">
    <!-- Hero Section -->
    <div class="hero-retro">
        <h1 class="mb-3">
            <i class="fas fa-edit me-3"></i>
            Editar Jogo
        </h1>
        <p class="lead" style="color: rgba(255, 255, 255, 0.9);">
            Atualize as informações do jogo "<?php echo htmlspecialchars($game['title']); ?>"
        </p>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-lg-8">
            <!-- Mensagens -->
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
            <?php endif; ?>

            <!-- Formulário -->
            <div class="card-retro p-4">
                <form method="POST" class="form-retro">
                    <div class="row g-3">
                        <!-- Título do Jogo -->
                        <div class="col-12">
                            <label for="title" class="form-label">
                                <i class="fas fa-gamepad me-2"></i>
                                Título do Jogo *
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="title" 
                                   name="title" 
                                   placeholder="Ex: Super Mario World"
                                   value="<?php echo htmlspecialchars($_POST['title'] ?? $game['title']); ?>"
                                   required>
                        </div>

                        <!-- Plataforma -->
                        <div class="col-md-6">
                            <label for="platform" class="form-label">
                                <i class="fas fa-tv me-2"></i>
                                Plataforma *
                            </label>
                            <select class="form-control" id="platform" name="platform" required>
                                <option value="">Selecione a plataforma</option>
                                <?php foreach ($platforms as $platform): ?>
                                    <option value="<?php echo htmlspecialchars($platform); ?>"
                                            <?php echo ($_POST['platform'] ?? $game['platform']) === $platform ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($platform); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Ano de Lançamento -->
                        <div class="col-md-6">
                            <label for="release_year" class="form-label">
                                <i class="fas fa-calendar me-2"></i>
                                Ano de Lançamento *
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="release_year" 
                                   name="release_year" 
                                   placeholder="Ex: 1990"
                                   min="1970" 
                                   max="<?php echo date('Y'); ?>"
                                   value="<?php echo htmlspecialchars($_POST['release_year'] ?? $game['release_year']); ?>"
                                   required>
                        </div>

                        <!-- Gênero -->
                        <div class="col-md-6">
                            <label for="genre" class="form-label">
                                <i class="fas fa-tags me-2"></i>
                                Gênero *
                            </label>
                            <select class="form-control" id="genre" name="genre" required>
                                <option value="">Selecione o gênero</option>
                                <?php foreach ($genres as $genre): ?>
                                    <option value="<?php echo htmlspecialchars($genre); ?>"
                                            <?php echo ($_POST['genre'] ?? $game['genre']) === $genre ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($genre); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Nota Pessoal -->
                        <div class="col-md-6">
                            <label for="personal_rating" class="form-label">
                                <i class="fas fa-star me-2"></i>
                                Sua Nota (0-10) *
                            </label>
                            <input type="number" 
                                   class="form-control" 
                                   id="personal_rating" 
                                   name="personal_rating" 
                                   placeholder="Ex: 9.5"
                                   min="0" 
                                   max="10" 
                                   step="0.1"
                                   value="<?php echo htmlspecialchars($_POST['personal_rating'] ?? $game['personal_rating']); ?>"
                                   required>
                            <small style="color: rgba(255, 255, 255, 0.6);">
                                Sua avaliação pessoal de 0 a 10
                            </small>
                        </div>

                        <!-- URL da Imagem -->
                        <div class="col-12">
                            <label for="image_url" class="form-label">
                                <i class="fas fa-image me-2"></i>
                                URL da Capa/Imagem (Opcional)
                            </label>
                            <input type="url" 
                                   class="form-control" 
                                   id="image_url" 
                                   name="image_url" 
                                   placeholder="https://exemplo.com/imagem-do-jogo.jpg"
                                   value="<?php echo htmlspecialchars($_POST['image_url'] ?? $game['image_url'] ?? ''); ?>">
                            <small style="color: rgba(255, 255, 255, 0.6);">
                                Cole aqui o link da imagem da capa do jogo
                            </small>
                        </div>

                        <!-- Comentário Pessoal -->
                        <div class="col-12">
                            <label for="personal_comment" class="form-label">
                                <i class="fas fa-comment me-2"></i>
                                Comentário Pessoal (Opcional)
                            </label>
                            <textarea class="form-control" 
                                      id="personal_comment" 
                                      name="personal_comment" 
                                      rows="4"
                                      placeholder="Compartilhe suas memórias, impressões e experiências com este jogo..."><?php echo htmlspecialchars($_POST['personal_comment'] ?? $game['personal_comment'] ?? ''); ?></textarea>
                            <small style="color: rgba(255, 255, 255, 0.6);">
                                Conte suas lembranças e o que tornou este jogo especial para você
                            </small>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex gap-3 justify-content-center">
                                <button type="submit" class="btn btn-retro btn-lg">
                                    <i class="fas fa-save me-2"></i>
                                    Salvar Alterações
                                </button>
                                <a href="dashboard.php" class="btn btn-retro-secondary btn-lg">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Voltar ao Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Preview atual -->
            <?php if ($game['image_url'] || $game['personal_comment']): ?>
                <div class="card-retro p-4 mt-4">
                    <h5 style="color: var(--neon-blue); text-align: center; margin-bottom: 1rem;">
                        <i class="fas fa-eye me-2"></i>
                        Preview Atual
                    </h5>
                    <div class="row">
                        <?php if ($game['image_url']): ?>
                            <div class="col-md-4">
                                <img src="<?php echo htmlspecialchars($game['image_url']); ?>" 
                                     class="img-fluid" 
                                     style="max-height: 200px; border-radius: 10px; border: 2px solid var(--neon-green);"
                                     alt="<?php echo htmlspecialchars($game['title']); ?>">
                            </div>
                        <?php endif; ?>
                        <div class="<?php echo $game['image_url'] ? 'col-md-8' : 'col-12'; ?>">
                            <h6 style="color: var(--neon-green);">
                                <?php echo htmlspecialchars($game['title']); ?>
                            </h6>
                            <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 0.5rem;">
                                <strong>Plataforma:</strong> <?php echo htmlspecialchars($game['platform']); ?> | 
                                <strong>Ano:</strong> <?php echo htmlspecialchars($game['release_year']); ?> | 
                                <strong>Gênero:</strong> <?php echo htmlspecialchars($game['genre']); ?>
                            </p>
                            <p style="color: rgba(255, 255, 255, 0.8); margin-bottom: 1rem;">
                                <strong>Nota:</strong> 
                                <span class="game-rating" style="display: inline-block; padding: 2px 8px; margin-left: 5px;">
                                    <?php echo number_format($game['personal_rating'], 1); ?>
                                </span>
                            </p>
                            <?php if ($game['personal_comment']): ?>
                                <div>
                                    <strong style="color: var(--neon-blue);">Comentário:</strong>
                                    <p style="color: rgba(255, 255, 255, 0.8); margin-top: 0.5rem;">
                                        <?php echo nl2br(htmlspecialchars($game['personal_comment'])); ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    const ratingInput = document.getElementById('personal_rating');
    
    // Feedback visual para a nota
    ratingInput.addEventListener('input', function() {
        const value = parseFloat(this.value);
        if (value >= 0 && value <= 10) {
            let color = 'var(--neon-green)';
            if (value >= 8) color = 'var(--neon-yellow)';
            if (value >= 9.5) color = 'var(--neon-pink)';
            
            this.style.borderColor = color;
            this.style.boxShadow = `0 0 15px rgba(${color === 'var(--neon-green)' ? '0, 255, 65' : color === 'var(--neon-yellow)' ? '255, 255, 0' : '255, 0, 128'}, 0.3)`;
        }
    });
    
    // Loading no submit
    form.addEventListener('submit', function() {
        const stopLoading = showLoading(submitBtn);
        
        // Se houver erro, parar o loading após um tempo
        setTimeout(() => {
            if (document.querySelector('.alert-danger-retro')) {
                stopLoading();
            }
        }, 100);
    });
    
    // Preview da imagem
    const imageUrlInput = document.getElementById('image_url');
    imageUrlInput.addEventListener('blur', function() {
        const url = this.value.trim();
        if (url && url.match(/\.(jpeg|jpg|gif|png|webp)$/i)) {
            // Remover preview anterior se existir
            const existingPreview = document.getElementById('image-preview');
            if (existingPreview) {
                existingPreview.remove();
            }
            
            // Criar preview
            const preview = document.createElement('div');
            preview.id = 'image-preview';
            preview.className = 'mt-2';
            preview.innerHTML = `
                <small style="color: var(--neon-green);">Novo preview da imagem:</small><br>
                <img src="${url}" style="max-width: 200px; max-height: 200px; border-radius: 10px; border: 2px solid var(--neon-green);" 
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div style="display: none; color: var(--neon-pink); font-size: 0.9rem;">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Não foi possível carregar a nova imagem
                </div>
            `;
            this.parentNode.appendChild(preview);
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?> 