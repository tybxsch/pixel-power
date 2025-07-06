<?php
echo "<h2>🔍 Teste de Conexão - Pixel Power</h2>";
echo "<hr>";

require_once 'config.php';

echo "<h3>📋 Configurações atuais:</h3>";
echo "<ul>";
echo "<li><strong>Host:</strong> " . DB_HOST . "</li>";
echo "<li><strong>Database:</strong> " . DB_NAME . "</li>";
echo "<li><strong>User:</strong> " . DB_USER . "</li>";
echo "<li><strong>Password:</strong> " . (DB_PASS ? "****** (configurada)" : "❌ NÃO CONFIGURADA") . "</li>";
echo "</ul>";

echo "<h3>🔗 Testando conexão...</h3>";

try {
    $test_pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    
    echo "<p style='color: green; font-weight: bold;'>✅ CONEXÃO ESTABELECIDA COM SUCESSO!</p>";
    
    $stmt = $test_pdo->query("SELECT VERSION() as version");
    $result = $stmt->fetch();
    
    echo "<h3>📊 Informações do servidor:</h3>";
    echo "<ul>";
    echo "<li><strong>Versão do MySQL:</strong> " . $result['version'] . "</li>";
    echo "<li><strong>Status da conexão:</strong> Ativa</li>";
    echo "</ul>";
    
    echo "<h3>🗂️ Verificando tabelas:</h3>";
    $tables = $test_pdo->query("SHOW TABLES")->fetchAll();
    
    if (count($tables) > 0) {
        echo "<p style='color: green;'>✅ Banco possui " . count($tables) . " tabela(s):</p>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>" . array_values($table)[0] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: orange;'>⚠️ Banco existe mas não possui tabelas. Você precisa importar o database.sql</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red; font-weight: bold;'>❌ ERRO NA CONEXÃO:</p>";
    echo "<div style='background: #ffebee; padding: 10px; border-left: 4px solid #f44336;'>";
    echo "<strong>Mensagem:</strong> " . $e->getMessage() . "<br>";
    echo "<strong>Código:</strong> " . $e->getCode();
    echo "</div>";
    
    echo "<h3>🔧 Possíveis soluções:</h3>";
    echo "<ol>";
    echo "<li>Verificar se o MySQL está rodando</li>";
    echo "<li>Confirmar host e porta (geralmente localhost:3306)</li>";
    echo "<li>Verificar usuário e senha</li>";
    echo "<li>Verificar se o banco de dados '" . DB_NAME . "' existe</li>";
    echo "<li>Verificar permissões do usuário</li>";
    echo "</ol>";
}

echo "<hr>";
echo "<p><em>Arquivo criado para teste. Você pode deletar depois! 😊</em></p>";
?> 