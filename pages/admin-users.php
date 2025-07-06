<?php
require_once '../config.php';

// Verificar se o usuário está logado (você pode adicionar verificação de admin aqui)
session_start();

$page_title = 'Administração - Usuários';
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <div class="card-retro p-4">
                <h1 class="text-center mb-4">
                    <i class="fas fa-users me-2"></i>
                    Administração de Usuários
                </h1>
                
                <div class="alert alert-retro mb-4">
                    <i class="fas fa-info-circle me-2"></i>
                    Aqui você pode visualizar todas as informações dos usuários cadastrados no sistema! 📊
                </div>
                
                <?php
                try {
                    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    // Query 1: Estatísticas gerais
                    $stmt = $pdo->query("
                        SELECT 
                            COUNT(*) as total_usuarios,
                            COUNT(CASE WHEN u.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) as usuarios_ultimos_30_dias,
                            (SELECT COUNT(*) FROM games) as total_jogos_cadastrados,
                            (SELECT AVG(personal_rating) FROM games WHERE personal_rating IS NOT NULL) as media_geral_avaliacoes
                        FROM users u
                    ");
                    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
                    ?>
                    
                    <!-- Estatísticas Gerais -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-neon-purple text-white p-3 text-center">
                                <h4><?php echo $stats['total_usuarios']; ?></h4>
                                <small>Total de Usuários</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-neon-blue text-white p-3 text-center">
                                <h4><?php echo $stats['usuarios_ultimos_30_dias']; ?></h4>
                                <small>Novos (30 dias)</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-neon-green text-dark p-3 text-center">
                                <h4><?php echo $stats['total_jogos_cadastrados']; ?></h4>
                                <small>Total de Jogos</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-neon-pink text-white p-3 text-center">
                                <h4><?php echo number_format($stats['media_geral_avaliacoes'], 1); ?></h4>
                                <small>Média de Avaliações</small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Lista de Usuários -->
                    <h3 class="mb-3">
                        <i class="fas fa-list me-2"></i>
                        Lista Completa de Usuários
                    </h3>
                    
                    <?php
                    // Query 2: Usuários com detalhes
                    $stmt = $pdo->query("
                        SELECT 
                            u.id as user_id,
                            u.username,
                            u.email,
                            u.created_at as data_cadastro,
                            COUNT(g.id) as total_jogos,
                            AVG(g.personal_rating) as media_avaliacao,
                            COUNT(CASE WHEN g.personal_rating >= 9.0 THEN 1 END) as jogos_excelentes,
                            COUNT(CASE WHEN g.personal_rating >= 7.0 AND g.personal_rating < 9.0 THEN 1 END) as jogos_bons,
                            COUNT(CASE WHEN g.personal_rating < 7.0 THEN 1 END) as jogos_ruins
                        FROM users u
                        LEFT JOIN games g ON u.id = g.user_id
                        GROUP BY u.id, u.username, u.email, u.created_at
                        ORDER BY total_jogos DESC, media_avaliacao DESC
                    ");
                    
                    if ($stmt->rowCount() > 0) {
                        ?>
                        <div class="table-responsive">
                            <table class="table table-dark table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuário</th>
                                        <th>Email</th>
                                        <th>Data Cadastro</th>
                                        <th>Total Jogos</th>
                                        <th>Média Avaliação</th>
                                        <th>Excelentes</th>
                                        <th>Bons</th>
                                        <th>Ruins</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                                        <tr>
                                            <td><?php echo $user['user_id']; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                                            </td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($user['data_cadastro'])); ?></td>
                                            <td>
                                                <span class="badge bg-primary"><?php echo $user['total_jogos']; ?></span>
                                            </td>
                                            <td>
                                                <?php if ($user['media_avaliacao']) { ?>
                                                    <span class="badge bg-success"><?php echo number_format($user['media_avaliacao'], 1); ?></span>
                                                <?php } else { ?>
                                                    <span class="badge bg-secondary">N/A</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-success"><?php echo $user['jogos_excelentes']; ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning"><?php echo $user['jogos_bons']; ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger"><?php echo $user['jogos_ruins']; ?></span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-retro" onclick="verJogos(<?php echo $user['user_id']; ?>)">
                                                    <i class="fas fa-gamepad"></i> Ver Jogos
                                                </button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                    } else {
                        echo '<div class="alert alert-warning">Nenhum usuário encontrado.</div>';
                    }
                    
                    // Query 3: Top usuários mais ativos
                    echo '<h3 class="mt-5 mb-3"><i class="fas fa-trophy me-2"></i>Top Usuários Mais Ativos</h3>';
                    
                    $stmt = $pdo->query("
                        SELECT 
                            u.username,
                            u.email,
                            COUNT(g.id) as total_jogos,
                            AVG(g.personal_rating) as media_avaliacao,
                            MAX(g.created_at) as ultima_atividade
                        FROM users u
                        LEFT JOIN games g ON u.id = g.user_id
                        GROUP BY u.id, u.username, u.email
                        HAVING total_jogos > 0
                        ORDER BY total_jogos DESC, media_avaliacao DESC
                        LIMIT 5
                    ");
                    
                    if ($stmt->rowCount() > 0) {
                        ?>
                        <div class="row">
                            <?php 
                            $position = 1;
                            while ($top_user = $stmt->fetch(PDO::FETCH_ASSOC)) { 
                                $bg_class = $position == 1 ? 'bg-warning' : ($position == 2 ? 'bg-secondary' : ($position == 3 ? 'bg-danger' : 'bg-info'));
                            ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card <?php echo $bg_class; ?> text-white">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">
                                                <?php if ($position <= 3) { ?>
                                                    <i class="fas fa-medal me-2"></i>
                                                <?php } ?>
                                                #<?php echo $position; ?> - <?php echo htmlspecialchars($top_user['username']); ?>
                                            </h5>
                                            <p class="card-text">
                                                <strong><?php echo $top_user['total_jogos']; ?></strong> jogos<br>
                                                Média: <strong><?php echo number_format($top_user['media_avaliacao'], 1); ?></strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                                $position++;
                            } 
                            ?>
                        </div>
                        <?php
                    }
                    
                } catch (PDOException $e) {
                    echo '<div class="alert alert-danger">Erro ao conectar com o banco de dados: ' . $e->getMessage() . '</div>';
                }
                ?>
                
                <div class="text-center mt-4">
                    <a href="../index.php" class="btn btn-retro">
                        <i class="fas fa-home me-2"></i>
                        Voltar ao Início
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function verJogos(userId) {
    alert('Funcionalidade para ver jogos do usuário ID: ' + userId + ' será implementada!');
}
</script>

<?php include '../includes/footer.php'; ?> 