<?php

// Estilo para a saída, para ficar mais fácil de ler
echo "
<style>
    body { font-family: sans-serif; background-color: #f4f4f4; }
    .container { max-width: 800px; margin: 50px auto; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 15px; border-radius: 4px; }
    .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 15px; border-radius: 4px; }
    pre { background-color: #eee; padding: 10px; border-radius: 4px; white-space: pre-wrap; word-wrap: break-word; }
</style>
<div class='container'>
<h1>Teste de Conexão com Banco de Dados Remoto</h1>
";

// 1. Incluir o arquivo de configuração
// O @ suprime warnings caso o arquivo não exista, mas o die() trata o erro.
@require_once __DIR__ . '/config/config.php';

if (!defined('DB_HOST')) {
    echo "<div class='error'><strong>ERRO:</strong> O arquivo de configuração (config/config.php) não foi encontrado ou não está definindo as constantes do banco de dados.</div>";
    die();
}

echo "<p>Tentando conectar ao banco de dados '<strong>" . DB_NAME . "</strong>' no host '<strong>" . DB_HOST . "</strong>'...</p>";

try {
    // 2. Tentar criar a conexão PDO
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS);

    // 3. Se a linha acima não falhou, a conexão foi bem-sucedida
    echo "<div class='success'><strong>SUCESSO!</strong> A conexão com o banco de dados foi estabelecida corretamente.</div>";

    // Opcional: Verificar a versão do servidor MySQL
    $version = $pdo->query('select version()')->fetchColumn();
    echo "<p>Versão do Servidor MySQL: " . $version . "</p>";


} catch (PDOException $e) {
    // 4. Se a conexão falhar, capturar a exceção e mostrar o erro
    echo "<div class='error'><strong>FALHA NA CONEXÃO!</strong> Ocorreu um erro ao tentar conectar.<br><br><strong>Detalhes do Erro:</strong><pre>" . $e->getMessage() . "</pre></div>";
    echo "<p><strong>Possíveis causas:</strong></p><ul><li>A senha (DB_PASS) está incorreta.</li><li>O nome de usuário (DB_USER) ou nome do banco (DB_NAME) está errado.</li><li>O Host Remoto (DB_HOST) não está correto.</li><li>O usuário do banco de dados não tem permissão para acesso remoto (verifique as configurações na Hostinger).</li></ul>";
}

echo "</div>";

?>