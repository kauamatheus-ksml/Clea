<?php
/**
 * 🔧 FIX FOLDERS - Correção Permanente
 *
 * Execute este script sempre que fizer upload dos arquivos
 * https://cleacasamentos.com.br/fix_folders.php
 */

// Proteção (descomente se quiser senha)
// if (!isset($_GET['key']) || $_GET['key'] !== 'clea2025') {
//     die('Acesso negado');
// }

$baseDir = dirname(__DIR__);
$appDir = $baseDir . '/app';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix Folders - Clea</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background: #000;
            color: #0f0;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #111;
            padding: 20px;
            border: 2px solid #0f0;
            border-radius: 10px;
        }
        h1 {
            text-align: center;
            color: #0f0;
            text-shadow: 0 0 10px #0f0;
        }
        .success { color: #0f0; }
        .error { color: #f00; }
        .warning { color: #ff0; }
        .info { color: #0af; }
        pre {
            background: #000;
            padding: 10px;
            border: 1px solid #0f0;
            overflow-x: auto;
        }
        .btn {
            background: #0f0;
            color: #000;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            margin: 10px 5px;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover { background: #0c0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 FIX FOLDERS</h1>
        <pre><?php

echo "📁 Diretório APP: $appDir\n";
echo "🖥️  Sistema: " . PHP_OS . "\n";
echo str_repeat("=", 60) . "\n\n";

$action = $_GET['action'] ?? 'check';

if ($action === 'check') {
    echo "🔍 VERIFICANDO ESTRUTURA...\n\n";

    $folders = ['core', 'controllers', 'models', 'views'];
    $needsFix = false;

    foreach ($folders as $folder) {
        $lowerPath = "$appDir/$folder";
        $upperPath = "$appDir/" . ucfirst($folder);

        if (is_dir($lowerPath) && !is_dir($upperPath)) {
            echo "❌ <span class='error'>INCORRETO: app/$folder/</span>\n";
            $needsFix = true;
        } elseif (is_dir($upperPath)) {
            echo "✅ <span class='success'>CORRETO: app/" . ucfirst($folder) . "/</span>\n";
        } else {
            echo "⚠️  <span class='warning'>NÃO ENCONTRADO: app/" . ucfirst($folder) . "/</span>\n";
        }
    }

    echo "\n" . str_repeat("=", 60) . "\n\n";

    if ($needsFix) {
        echo "<span class='warning'>⚠️  CORREÇÃO NECESSÁRIA!</span>\n\n";
        echo "Clique no botão abaixo para corrigir:\n";
        echo "</pre>";
        echo "<a href='?action=fix' class='btn'>🔧 CORRIGIR AGORA</a>";
        echo "<pre>";
    } else {
        echo "<span class='success'>✅ ESTRUTURA ESTÁ CORRETA!</span>\n\n";
        echo "O site deve estar funcionando normalmente.\n";
        echo "</pre>";
        echo "<a href='/' class='btn'>🏠 IR PARA O SITE</a>";
        echo "<pre>";
    }

} elseif ($action === 'fix') {
    echo "🔧 APLICANDO CORREÇÕES...\n\n";

    $folders = [
        'core' => 'Core',
        'controllers' => 'Controllers',
        'models' => 'Models',
        'views' => 'Views'
    ];

    $fixed = 0;
    $errors = 0;

    foreach ($folders as $lower => $upper) {
        $lowerPath = "$appDir/$lower";
        $upperPath = "$appDir/$upper";
        $tempPath = "$appDir/{$upper}_temp_" . time();

        if (is_dir($lowerPath) && !is_dir($upperPath)) {
            echo "📝 Renomeando: app/$lower/ → app/$upper/\n";

            // Método 1: Via temp
            if (@rename($lowerPath, $tempPath)) {
                if (@rename($tempPath, $upperPath)) {
                    echo "   ✅ <span class='success'>Sucesso!</span>\n";
                    $fixed++;
                } else {
                    echo "   ❌ <span class='error'>Erro no passo 2</span>\n";
                    @rename($tempPath, $lowerPath); // Reverter
                    $errors++;
                }
            } else {
                echo "   ❌ <span class='error'>Erro ao renomear</span>\n";
                $errors++;
            }
        } elseif (is_dir($upperPath)) {
            echo "✅ Já existe: app/$upper/\n";
        }
    }

    echo "\n" . str_repeat("=", 60) . "\n\n";

    if ($errors === 0) {
        echo "<span class='success'>🎉 CORREÇÃO CONCLUÍDA!</span>\n\n";
        echo "Total corrigido: $fixed pasta(s)\n\n";
        echo "Próximos passos:\n";
        echo "1. Teste o site\n";
        echo "2. Se funcionar, delete este arquivo\n";
        echo "</pre>";
        echo "<a href='/' class='btn'>🏠 TESTAR SITE</a>";
        echo "<a href='?action=check' class='btn'>🔍 VERIFICAR NOVAMENTE</a>";
        echo "<pre>";
    } else {
        echo "<span class='error'>❌ ALGUNS ERROS OCORRERAM</span>\n\n";
        echo "Corrigidos: $fixed\n";
        echo "Erros: $errors\n\n";
        echo "Tente fazer a correção manual via File Manager.\n";
    }
}

?>
        </pre>

        <hr style="border-color: #0f0;">

        <div style="margin-top: 20px; padding: 10px; background: #220; border: 1px solid #ff0;">
            <strong class="warning">⚠️ IMPORTANTE:</strong><br>
            Após corrigir e verificar que funciona, DELETE este arquivo:<br>
            <code>public/fix_folders.php</code>
        </div>

        <div style="margin-top: 20px; text-align: center; opacity: 0.5; font-size: 12px;">
            Fix Folders v2.0 | <?= date('Y-m-d H:i:s') ?>
        </div>
    </div>
</body>
</html>
