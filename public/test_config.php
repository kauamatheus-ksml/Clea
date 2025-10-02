<?php
/**
 * Teste de Configuração do Sistema
 * Acesse: https://cleacasamentos.com.br/test_config.php
 */

// Carrega configurações
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/app/helpers.php';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste de Configuração - Clea Casamentos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            padding: 2rem;
            background: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #652929;
            margin-bottom: 1rem;
        }
        .test-item {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 5px;
            border-left: 4px solid #ddd;
        }
        .success {
            background: #d4edda;
            border-color: #28a745;
        }
        .error {
            background: #f8d7da;
            border-color: #dc3545;
        }
        .info {
            background: #d1ecf1;
            border-color: #17a2b8;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        .links {
            margin-top: 2rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .links a {
            display: block;
            padding: 0.5rem;
            color: #652929;
            text-decoration: none;
            margin: 0.25rem 0;
        }
        .links a:hover {
            background: #e9ecef;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Teste de Configuração do Sistema</h1>

        <!-- Teste 1: Constantes -->
        <div class="test-item <?= defined('BASE_URL') ? 'success' : 'error' ?>">
            <strong>Teste 1: Constantes Definidas</strong><br>
            BASE_URL: <?= defined('BASE_URL') ? '<code>' . BASE_URL . '</code> ✅' : '❌ Não definida' ?><br>
            APP_URL: <?= defined('APP_URL') ? '<code>' . APP_URL . '</code> ✅' : '❌ Não definida' ?>
        </div>

        <!-- Teste 2: Funções Helper -->
        <div class="test-item <?= function_exists('url') ? 'success' : 'error' ?>">
            <strong>Teste 2: Funções Helper</strong><br>
            Função url(): <?= function_exists('url') ? '✅ Disponível' : '❌ Não encontrada' ?><br>
            Função asset(): <?= function_exists('asset') ? '✅ Disponível' : '❌ Não encontrada' ?><br>
            <?php if (function_exists('url')): ?>
                Teste: <code>url('vendors')</code> = <code><?= url('vendors') ?></code>
            <?php endif; ?>
        </div>

        <!-- Teste 3: Banco de Dados -->
        <?php
        $dbConnected = false;
        $dbError = '';
        try {
            require_once dirname(__DIR__) . '/app/core/Database.php';
            $db = App\Core\Database::getInstance();
            $conn = $db->getConnection();
            $dbConnected = true;
        } catch (Exception $e) {
            $dbError = $e->getMessage();
        }
        ?>
        <div class="test-item <?= $dbConnected ? 'success' : 'error' ?>">
            <strong>Teste 3: Conexão com Banco de Dados</strong><br>
            Status: <?= $dbConnected ? '✅ Conectado' : '❌ Erro na conexão' ?><br>
            <?php if (!$dbConnected): ?>
                Erro: <code><?= htmlspecialchars($dbError) ?></code>
            <?php else: ?>
                Host: <code><?= DB_HOST ?></code><br>
                Database: <code><?= DB_NAME ?></code>
            <?php endif; ?>
        </div>

        <!-- Teste 4: Apache mod_rewrite -->
        <div class="test-item info">
            <strong>Teste 4: Apache mod_rewrite</strong><br>
            <?php if (function_exists('apache_get_modules')): ?>
                <?php $modules = apache_get_modules(); ?>
                mod_rewrite: <?= in_array('mod_rewrite', $modules) ? '✅ Ativo' : '❌ Inativo' ?>
            <?php else: ?>
                ⚠️ Não é possível verificar (não disponível via PHP)
            <?php endif; ?>
        </div>

        <!-- Teste 5: Permissões de Escrita -->
        <?php
        $tmpDir = sys_get_temp_dir();
        $canWrite = is_writable($tmpDir);
        ?>
        <div class="test-item <?= $canWrite ? 'success' : 'error' ?>">
            <strong>Teste 5: Permissões de Escrita</strong><br>
            Diretório temporário: <code><?= $tmpDir ?></code><br>
            Status: <?= $canWrite ? '✅ Gravável' : '❌ Sem permissão' ?>
        </div>

        <!-- Teste 6: PHP Version -->
        <div class="test-item <?= version_compare(PHP_VERSION, '8.0.0', '>=') ? 'success' : 'error' ?>">
            <strong>Teste 6: Versão do PHP</strong><br>
            Versão atual: <code><?= PHP_VERSION ?></code><br>
            Requerido: <code>>= 8.0.0</code><br>
            Status: <?= version_compare(PHP_VERSION, '8.0.0', '>=') ? '✅ OK' : '❌ Atualização necessária' ?>
        </div>

        <!-- Teste 7: Extensões PHP -->
        <?php
        $requiredExtensions = ['pdo', 'pdo_mysql', 'session', 'json', 'mbstring'];
        $missingExtensions = [];
        foreach ($requiredExtensions as $ext) {
            if (!extension_loaded($ext)) {
                $missingExtensions[] = $ext;
            }
        }
        ?>
        <div class="test-item <?= empty($missingExtensions) ? 'success' : 'error' ?>">
            <strong>Teste 7: Extensões PHP</strong><br>
            <?php if (empty($missingExtensions)): ?>
                ✅ Todas as extensões necessárias estão instaladas
            <?php else: ?>
                ❌ Extensões faltando: <code><?= implode(', ', $missingExtensions) ?></code>
            <?php endif; ?>
        </div>

        <!-- Links para Teste -->
        <div class="links">
            <strong>🔗 Links para Teste:</strong>
            <?php if (function_exists('url')): ?>
                <a href="<?= url('/') ?>" target="_blank">→ Landing Page (Home)</a>
                <a href="<?= url('vendors') ?>" target="_blank">→ Galeria de Fornecedores</a>
                <a href="<?= url('about') ?>" target="_blank">→ Sobre</a>
                <a href="<?= url('contact') ?>" target="_blank">→ Contato</a>
                <a href="<?= url('register') ?>" target="_blank">→ Registro</a>
                <a href="<?= url('login.php') ?>" target="_blank">→ Login</a>
            <?php else: ?>
                <p style="color: #dc3545;">⚠️ Funções helper não disponíveis - links não podem ser gerados</p>
            <?php endif; ?>
        </div>

        <div style="margin-top: 2rem; padding: 1rem; background: #fff3cd; border-radius: 5px;">
            <strong>⚠️ Importante:</strong><br>
            Após verificar que tudo está OK, <strong>DELETE este arquivo</strong> por segurança:<br>
            <code>public/test_config.php</code>
        </div>
    </div>
</body>
</html>
