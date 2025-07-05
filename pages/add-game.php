<?php
require_once '../config.php';

// Verificar se está logado
if (!isLoggedIn()) {
    redirect('../pages/login.php');
}

$page_title = 'Adicionar Jogo';
$error_message = '';
$success_message = '';

// Processar formulário
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
                INSERT INTO games (user_id, title, platform, release_year, genre, personal_rating, image_url, personal_comment) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            if ($stmt->execute([
                $_SESSION['user_id'],
                $title,
                $platform,
                $release_year,
                $genre,
                $personal_rating,
                $image_url ?: null,
                $personal_comment ?: null
            ])) {
                $success_message = 'Jogo adicionado com sucesso!';
                // Limpar campos após sucesso
                $_POST = [];
            } else {
                $error_message = 'Erro ao adicionar jogo. Tente novamente!';
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
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
            <i class="fas fa-plus-circle me-3"></i>
            Adicionar Novo Jogo
        </h1>
        <p class="lead" style="color: rgba(255, 255, 255, 0.9);">
            Adicione um jogo clássico à sua coleção pessoal!
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
                <div class="text-center mb-4">
                    <a href="dashboard.php" class="btn btn-retro me-3">
                        <i class="fas fa-list me-2"></i>
                        Ver Minha Coleção
                    </a>
                    <a href="add-game.php" class="btn btn-retro-secondary">
                        <i class="fas fa-plus me-2"></i>
                        Adicionar Outro Jogo
                    </a>
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
                                   value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>"
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
                                            <?php echo ($_POST['platform'] ?? '') === $platform ? 'selected' : ''; ?>>
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
                                   value="<?php echo htmlspecialchars($_POST['release_year'] ?? ''); ?>"
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
                                            <?php echo ($_POST['genre'] ?? '') === $genre ? 'selected' : ''; ?>>
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
                                   value="<?php echo htmlspecialchars($_POST['personal_rating'] ?? ''); ?>"
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
                                   value="<?php echo htmlspecialchars($_POST['image_url'] ?? ''); ?>">
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
                                      placeholder="Compartilhe suas memórias, impressões e experiências com este jogo..."><?php echo htmlspecialchars($_POST['personal_comment'] ?? ''); ?></textarea>
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
                                    Adicionar à Coleção
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

            <!-- Dicas -->
            <div class="card-retro p-4 mt-4">
                <h5 style="color: var(--neon-green); text-align: center; margin-bottom: 1rem;">
                    <i class="fas fa-lightbulb me-2"></i>
                    Dicas para adicionar jogos
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-center">
                            <i class="fas fa-search mb-2" style="color: var(--neon-blue); font-size: 1.5rem;"></i>
                            <small style="color: rgba(255, 255, 255, 0.8); display: block;">
                                <strong>Imagens:</strong> Use sites como MobyGames ou Google Imagens para encontrar capas oficiais
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center">
                            <i class="fas fa-heart mb-2" style="color: var(--neon-pink); font-size: 1.5rem;"></i>
                            <small style="color: rgba(255, 255, 255, 0.8); display: block;">
                                <strong>Comentários:</strong> Conte suas memórias! O que tornou este jogo especial?
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center">
                            <i class="fas fa-star mb-2" style="color: var(--neon-yellow); font-size: 1.5rem;"></i>
                            <small style="color: rgba(255, 255, 255, 0.8); display: block;">
                                <strong>Avaliação:</strong> Use sua experiência pessoal, não notas de críticos
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center">
                            <i class="fas fa-gamepad mb-2" style="color: var(--neon-green); font-size: 1.5rem;"></i>
                            <small style="color: rgba(255, 255, 255, 0.8); display: block;">
                                <strong>Nostalgia:</strong> Adicione jogos que marcaram sua infância/adolescência
                            </small>
                        </div>
                    </div>
                </div>
            </div>
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
            this.style.boxShadow = `0 0 15px ${color.replace('var(', '').replace(')', '').replace('--neon-green', 'rgba(0, 255, 65, 0.3)').replace('--neon-yellow', 'rgba(255, 255, 0, 0.3)').replace('--neon-pink', 'rgba(255, 0, 128, 0.3)')}`;
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
                <small style="color: var(--neon-green);">Preview da imagem:</small><br>
                <img src="${url}" style="max-width: 200px; max-height: 200px; border-radius: 10px; border: 2px solid var(--neon-green);" 
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div style="display: none; color: var(--neon-pink); font-size: 0.9rem;">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Não foi possível carregar a imagem
                </div>
            `;
            this.parentNode.appendChild(preview);
        }
    });
});
</script>

<?php include '../includes/footer.php'; ?> 