<?php
/**
 * Diagnóstico de Configuração do Servidor
 * Acesse: https://cleacasamentos.com.br/public/diagnostico.php
 * ou: https://cleacasamentos.com.br/diagnostico.php
 */
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico - Clea Casamentos</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }
        h1 {
            color: #652929;
            border-bottom: 3px solid #f2abb1;
            padding-bottom: 10px;
        }
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #17a2b8;
        }
        .success {
            background: #d4edda;
            border-left-color: #28a745;
            color: #155724;
        }
        .warning {
            background: #fff3cd;
            border-left-color: #ffc107;
            color: #856404;
        }
        .error {
            background: #f8d7da;
            border-left-color: #dc3545;
            color: #721c24;
        }
        code {
            background: #e9ecef;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }
        .step {
            background: white;
            border: 2px solid #f2abb1;
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
        }
        .step h3 {
            color: #652929;
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th,
        table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        table th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #652929;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
        }
        .btn:hover {
            background: #f2abb1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Diagnóstico do Servidor</h1>

        <!-- Status Geral -->
        <div class="info-box success">
            <strong>✅ Este arquivo está funcionando!</strong><br>
            Isso significa que o PHP está executando corretamente.
        </div>

        <!-- Informações do Servidor -->
        <div class="step">
            <h3>📊 Informações do Servidor</h3>
            <table>
                <tr>
                    <th>Item</th>
                    <th>Valor</th>
                </tr>
                <tr>
                    <td>Versão do PHP</td>
                    <td><code><?= PHP_VERSION ?></code></td>
                </tr>
                <tr>
                    <td>Sistema Operacional</td>
                    <td><code><?= PHP_OS ?></code></td>
                </tr>
                <tr>
                    <td>Servidor Web</td>
                    <td><code><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Desconhecido' ?></code></td>
                </tr>
                <tr>
                    <td>Nome do Host</td>
                    <td><code><?= $_SERVER['HTTP_HOST'] ?? 'Desconhecido' ?></code></td>
                </tr>
                <tr>
                    <td>Document Root</td>
                    <td><code><?= $_SERVER['DOCUMENT_ROOT'] ?? 'Desconhecido' ?></code></td>
                </tr>
                <tr>
                    <td>Script Atual</td>
                    <td><code><?= $_SERVER['SCRIPT_FILENAME'] ?? 'Desconhecido' ?></code></td>
                </tr>
                <tr>
                    <td>Protocolo</td>
                    <td><code><?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'HTTPS ✅' : 'HTTP ⚠️' ?></code></td>
                </tr>
            </table>
        </div>

        <!-- Caminho Atual -->
        <div class="step">
            <h3>📁 Caminho Atual do Arquivo</h3>
            <div class="info-box">
                <strong>Este arquivo está em:</strong><br>
                <code><?= __FILE__ ?></code>
            </div>
            <div class="info-box">
                <strong>Diretório atual:</strong><br>
                <code><?= __DIR__ ?></code>
            </div>
        </div>

        <!-- Verificação de Arquivos -->
        <div class="step">
            <h3>🔍 Verificação de Arquivos Importantes</h3>
            <?php
            $rootDir = dirname(__DIR__);
            $filesToCheck = [
                'index.php' => __DIR__ . '/index.php',
                '.htaccess (public)' => __DIR__ . '/.htaccess',
                '.htaccess (raiz)' => $rootDir . '/.htaccess',
                'config/config.php' => $rootDir . '/config/config.php',
                'app/helpers.php' => $rootDir . '/app/helpers.php',
                'app/Controllers/PublicController.php' => $rootDir . '/app/Controllers/PublicController.php',
            ];

            echo '<table>';
            echo '<tr><th>Arquivo</th><th>Status</th><th>Caminho</th></tr>';
            foreach ($filesToCheck as $name => $path) {
                $exists = file_exists($path);
                $readable = $exists && is_readable($path);

                echo '<tr>';
                echo '<td>' . $name . '</td>';

                if ($exists && $readable) {
                    echo '<td style="color: #28a745;">✅ OK</td>';
                } elseif ($exists && !$readable) {
                    echo '<td style="color: #ffc107;">⚠️ Sem permissão</td>';
                } else {
                    echo '<td style="color: #dc3545;">❌ Não encontrado</td>';
                }

                echo '<td><code>' . $path . '</code></td>';
                echo '</tr>';
            }
            echo '</table>';
            ?>
        </div>

        <!-- Extensões PHP -->
        <div class="step">
            <h3>🔌 Extensões PHP</h3>
            <?php
            $requiredExtensions = [
                'pdo' => 'PDO (Database)',
                'pdo_mysql' => 'PDO MySQL',
                'session' => 'Sessions',
                'json' => 'JSON',
                'mbstring' => 'Multibyte String'
            ];

            $allLoaded = true;
            echo '<table>';
            echo '<tr><th>Extensão</th><th>Status</th></tr>';
            foreach ($requiredExtensions as $ext => $desc) {
                $loaded = extension_loaded($ext);
                $allLoaded = $allLoaded && $loaded;

                echo '<tr>';
                echo '<td>' . $desc . ' <code>' . $ext . '</code></td>';
                echo '<td>' . ($loaded ? '<span style="color: #28a745;">✅ Instalada</span>' : '<span style="color: #dc3545;">❌ Faltando</span>') . '</td>';
                echo '</tr>';
            }
            echo '</table>';
            ?>
        </div>

        <!-- Diagnóstico do Problema -->
        <div class="step">
            <h3>🔧 Diagnóstico do Erro 403</h3>

            <?php
            $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
            $scriptPath = $_SERVER['SCRIPT_FILENAME'] ?? '';

            // Verificar se está na pasta public
            $inPublicFolder = strpos($scriptPath, '/public/') !== false || strpos($scriptPath, '\\public\\') !== false;
            ?>

            <?php if ($inPublicFolder): ?>
                <div class="info-box success">
                    <strong>✅ CORRETO!</strong><br>
                    Este arquivo está sendo executado da pasta <code>/public/</code><br>
                    O DocumentRoot está configurado corretamente!
                </div>

                <div class="info-box success">
                    <strong>🎉 Solução:</strong><br>
                    Seu servidor está configurado corretamente. O erro 403 foi resolvido!<br>
                    Agora você pode acessar:
                    <ul>
                        <li><a href="/" class="btn">Landing Page</a></li>
                        <li><a href="/vendors" class="btn">Fornecedores</a></li>
                        <li><a href="/test_config.php" class="btn">Teste Completo</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <div class="info-box error">
                    <strong>❌ PROBLEMA IDENTIFICADO!</strong><br>
                    O servidor não está acessando a pasta <code>/public/</code><br>
                    DocumentRoot atual: <code><?= $documentRoot ?></code>
                </div>

                <div class="info-box warning">
                    <strong>🔧 SOLUÇÃO:</strong><br>
                    Você precisa configurar o DocumentRoot para apontar para a pasta <code>public</code>.<br><br>

                    <strong>No Painel Hostinger:</strong>
                    <ol>
                        <li>Vá em <strong>Websites</strong></li>
                        <li>Selecione <strong>cleacasamentos.com.br</strong></li>
                        <li>Clique em <strong>Advanced</strong> (Avançado)</li>
                        <li>Encontre <strong>Document Root</strong></li>
                        <li>Altere para: <code><?= rtrim($documentRoot, '/') ?>/public</code></li>
                        <li>Salve e aguarde 1-2 minutos</li>
                    </ol>
                </div>

                <div class="info-box warning">
                    <strong>⚠️ ALTERNATIVA (se não conseguir mudar DocumentRoot):</strong><br>

                    <p><strong>Opção A:</strong> Acesse diretamente via:</p>
                    <code>https://cleacasamentos.com.br/public/index.php</code>

                    <p><strong>Opção B:</strong> Crie redirecionamento automático:</p>
                    <ol>
                        <li>Faça upload de <code>.htaccess</code> na raiz</li>
                        <li>Faça upload de <code>index.php</code> na raiz</li>
                        <li>Esses arquivos foram criados e estão prontos</li>
                    </ol>
                </div>
            <?php endif; ?>
        </div>

        <!-- Links de Teste -->
        <div class="step">
            <h3>🔗 Links para Teste</h3>
            <a href="/" class="btn">🏠 Home</a>
            <a href="/vendors" class="btn">👥 Fornecedores</a>
            <a href="/about" class="btn">ℹ️ Sobre</a>
            <a href="/contact" class="btn">📧 Contato</a>
            <a href="/register" class="btn">✍️ Registro</a>
            <a href="/test_config.php" class="btn">🔧 Teste Completo</a>
        </div>

        <div class="info-box warning" style="margin-top: 30px;">
            <strong>⚠️ SEGURANÇA:</strong><br>
            Após resolver o problema, <strong>DELETE este arquivo</strong>:<br>
            <code>public/diagnostico.php</code>
        </div>
    </div>
</body>
</html>
