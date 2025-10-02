<?php
/**
 * 🔧 SCRIPT DE CORREÇÃO AUTOMÁTICA - CASE-SENSITIVE
 *
 * Este script renomeia as pastas para o case correto no servidor Linux
 *
 * COMO USAR:
 * 1. Fazer upload deste arquivo para: /public_html/public/fix_case.php
 * 2. Acessar: https://cleacasamentos.com.br/fix_case.php
 * 3. Seguir as instruções
 * 4. DELETAR este arquivo após usar
 */

// Proteção básica - descomente e configure uma senha se quiser
// $senha = 'cleacasamentos2025';
// if (!isset($_GET['senha']) || $_GET['senha'] !== $senha) {
//     die('❌ Acesso negado. Use: ?senha=cleacasamentos2025');
// }

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Correção Case-Sensitive - Clea</title>
    <style>
        body {
            font-family: monospace;
            background: #1a1a1a;
            color: #00ff00;
            padding: 20px;
            max-width: 900px;
            margin: 0 auto;
        }
        .container {
            background: #000;
            border: 2px solid #00ff00;
            padding: 20px;
            border-radius: 10px;
        }
        h1 {
            color: #00ff00;
            text-align: center;
            border-bottom: 2px solid #00ff00;
            padding-bottom: 10px;
        }
        .success {
            color: #00ff00;
            background: #003300;
            padding: 10px;
            margin: 10px 0;
            border-left: 4px solid #00ff00;
        }
        .error {
            color: #ff0000;
            background: #330000;
            padding: 10px;
            margin: 10px 0;
            border-left: 4px solid #ff0000;
        }
        .warning {
            color: #ffff00;
            background: #333300;
            padding: 10px;
            margin: 10px 0;
            border-left: 4px solid #ffff00;
        }
        .info {
            color: #00aaff;
            background: #001133;
            padding: 10px;
            margin: 10px 0;
            border-left: 4px solid #00aaff;
        }
        pre {
            background: #111;
            padding: 10px;
            border: 1px solid #00ff00;
            overflow-x: auto;
        }
        .btn {
            background: #00ff00;
            color: #000;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            margin: 10px 5px;
            border-radius: 5px;
        }
        .btn:hover {
            background: #00cc00;
        }
        .btn-danger {
            background: #ff0000;
            color: #fff;
        }
        .btn-danger:hover {
            background: #cc0000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 CORREÇÃO CASE-SENSITIVE</h1>

        <?php
        $appDir = dirname(__DIR__) . '/app';
        $action = $_GET['action'] ?? 'check';

        echo "<div class='info'>";
        echo "<strong>📁 Diretório APP:</strong> $appDir<br>";
        echo "<strong>🖥️ Sistema:</strong> " . PHP_OS . "<br>";
        echo "<strong>🔍 Ação:</strong> $action";
        echo "</div>";

        if ($action === 'check') {
            // Verificar estrutura atual
            echo "<h2>📊 ESTRUTURA ATUAL:</h2>";

            $folders = [
                'core' => 'Core',
                'controllers' => 'Controllers',
                'models' => 'Models',
                'views' => 'Views'
            ];

            $needsFix = false;
            $report = [];

            foreach ($folders as $wrong => $correct) {
                $wrongPath = $appDir . '/' . $wrong;
                $correctPath = $appDir . '/' . $correct;

                if (is_dir($wrongPath) && strtolower($wrong) === strtolower($correct)) {
                    // Existe com nome errado
                    if (!is_dir($correctPath)) {
                        echo "<div class='error'>❌ Encontrado: <strong>app/$wrong/</strong> (incorreto)</div>";
                        $needsFix = true;
                        $report[] = ['wrong' => $wrong, 'correct' => $correct, 'status' => 'needs_rename'];
                    } else {
                        echo "<div class='warning'>⚠️ Existem ambos: <strong>app/$wrong/</strong> e <strong>app/$correct/</strong></div>";
                        $needsFix = true;
                        $report[] = ['wrong' => $wrong, 'correct' => $correct, 'status' => 'duplicate'];
                    }
                } elseif (is_dir($correctPath)) {
                    // Já está correto
                    echo "<div class='success'>✅ Correto: <strong>app/$correct/</strong></div>";
                    $report[] = ['wrong' => null, 'correct' => $correct, 'status' => 'ok'];
                } else {
                    // Não existe nenhum
                    echo "<div class='error'>❌ Não encontrado: <strong>app/$correct/</strong></div>";
                    $report[] = ['wrong' => null, 'correct' => $correct, 'status' => 'missing'];
                }
            }

            echo "<hr>";

            if ($needsFix) {
                echo "<div class='warning'>";
                echo "<h3>⚠️ CORREÇÃO NECESSÁRIA</h3>";
                echo "<p>As pastas precisam ser renomeadas para funcionar no Linux.</p>";
                echo "<form method='GET' style='margin-top: 20px;'>";
                echo "<input type='hidden' name='action' value='fix'>";
                echo "<button type='submit' class='btn'>🔧 CORRIGIR AUTOMATICAMENTE</button>";
                echo "</form>";
                echo "</div>";
            } else {
                echo "<div class='success'>";
                echo "<h3>✅ ESTRUTURA CORRETA!</h3>";
                echo "<p>Todas as pastas estão com os nomes corretos.</p>";
                echo "<p><a href='/' class='btn'>🏠 IR PARA O SITE</a></p>";
                echo "</div>";
            }

        } elseif ($action === 'fix') {
            // Aplicar correções
            echo "<h2>🔧 APLICANDO CORREÇÕES:</h2>";

            $folders = [
                'core' => 'Core',
                'controllers' => 'Controllers',
                'models' => 'Models',
                'views' => 'Views'
            ];

            $success = true;

            foreach ($folders as $wrong => $correct) {
                $wrongPath = $appDir . '/' . $wrong;
                $correctPath = $appDir . '/' . $correct;
                $tempPath = $appDir . '/' . $correct . '_temp';

                if (is_dir($wrongPath) && !is_dir($correctPath)) {
                    // Renomear via temp (evita problemas de case no mesmo nome)
                    echo "<div class='info'>📝 Renomeando: app/$wrong/ → app/$correct/</div>";

                    // Passo 1: Renomear para temp
                    if (rename($wrongPath, $tempPath)) {
                        // Passo 2: Renomear temp para correto
                        if (rename($tempPath, $correctPath)) {
                            echo "<div class='success'>✅ Sucesso: app/$correct/</div>";
                        } else {
                            echo "<div class='error'>❌ Erro ao renomear de temp para $correct</div>";
                            $success = false;
                        }
                    } else {
                        echo "<div class='error'>❌ Erro ao renomear $wrong para temp</div>";
                        $success = false;
                    }
                } elseif (is_dir($correctPath)) {
                    echo "<div class='success'>✅ Já existe: app/$correct/</div>";
                }
            }

            echo "<hr>";

            if ($success) {
                echo "<div class='success'>";
                echo "<h3>🎉 CORREÇÃO CONCLUÍDA!</h3>";
                echo "<p>Todas as pastas foram renomeadas com sucesso.</p>";
                echo "<p><strong>Próximos passos:</strong></p>";
                echo "<ol>";
                echo "<li>Teste o site: <a href='/' style='color: #00ff00;'>https://cleacasamentos.com.br/</a></li>";
                echo "<li>Se funcionar, DELETE este arquivo (fix_case.php)</li>";
                echo "</ol>";
                echo "<br>";
                echo "<a href='/' class='btn'>🏠 TESTAR SITE AGORA</a>";
                echo "</div>";
            } else {
                echo "<div class='error'>";
                echo "<h3>❌ ERRO NA CORREÇÃO</h3>";
                echo "<p>Algumas pastas não puderam ser renomeadas.</p>";
                echo "<p>Tente renomear manualmente via File Manager.</p>";
                echo "</div>";
            }
        }
        ?>

        <hr>
        <div class='warning'>
            <strong>⚠️ IMPORTANTE:</strong>
            <p>Após corrigir e verificar que o site funciona, <strong>DELETE este arquivo</strong> por segurança:</p>
            <pre>rm /public_html/public/fix_case.php</pre>
            <p>Ou via File Manager: delete o arquivo <strong>public/fix_case.php</strong></p>
        </div>

        <hr>
        <div class='info' style='font-size: 12px; text-align: center;'>
            Script de correção v1.0 | Clea Casamentos | <?= date('Y-m-d H:i:s') ?>
        </div>
    </div>
</body>
</html>
