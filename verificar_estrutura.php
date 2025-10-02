<?php
/**
 * Verificação de Estrutura de Pastas
 * Execute antes de fazer upload para garantir que está tudo correto
 */

echo "🔍 VERIFICANDO ESTRUTURA DO PROJETO\n";
echo str_repeat("=", 60) . "\n\n";

$baseDir = __DIR__;
$errors = [];
$warnings = [];
$success = [];

// Verificar estrutura de pastas (CASE-SENSITIVE)
$requiredFolders = [
    'app/Core',           // Deve ser Core (C maiúsculo)
    'app/Controllers',    // Deve ser Controllers (C maiúsculo)
    'app/Models',         // Deve ser Models (M maiúsculo)
    'app/Views',          // Deve ser Views (V maiúsculo)
    'app/Views/public',
    'config',
    'public',
];

echo "📁 VERIFICANDO PASTAS:\n";
foreach ($requiredFolders as $folder) {
    $path = $baseDir . '/' . $folder;
    if (is_dir($path)) {
        echo "  ✅ $folder\n";
        $success[] = $folder;
    } else {
        echo "  ❌ $folder (NÃO ENCONTRADA)\n";
        $errors[] = "Pasta '$folder' não encontrada";
    }
}

echo "\n";

// Verificar arquivos críticos
$requiredFiles = [
    'app/Core/Router.php',
    'app/Core/Database.php',
    'app/Core/Auth.php',
    'app/Core/Url.php',
    'app/Controllers/PublicController.php',
    'app/Controllers/AdminController.php',
    'app/Controllers/ClientController.php',
    'app/Controllers/VendorController.php',
    'app/helpers.php',
    'config/config.php',
    'public/index.php',
    'public/.htaccess',
    '.htaccess',
    'index.php',
    'migration_leads.sql',
];

echo "📄 VERIFICANDO ARQUIVOS:\n";
foreach ($requiredFiles as $file) {
    $path = $baseDir . '/' . $file;
    if (file_exists($path)) {
        echo "  ✅ $file\n";
        $success[] = $file;
    } else {
        echo "  ❌ $file (NÃO ENCONTRADO)\n";
        $errors[] = "Arquivo '$file' não encontrado";
    }
}

echo "\n";

// Verificar case-sensitive crítico
echo "🔤 VERIFICANDO CASE-SENSITIVE (IMPORTANTE PARA LINUX):\n";

$caseSensitiveChecks = [
    ['app/core', 'app/Core', 'Pasta deve ser "Core" (C maiúsculo)'],
    ['app/controllers', 'app/Controllers', 'Pasta deve ser "Controllers" (C maiúsculo)'],
    ['app/models', 'app/Models', 'Pasta deve ser "Models" (M maiúsculo)'],
    ['app/views', 'app/Views', 'Pasta deve ser "Views" (V maiúsculo)'],
];

foreach ($caseSensitiveChecks as $check) {
    [$wrong, $correct, $message] = $check;

    if (is_dir($baseDir . '/' . $wrong)) {
        echo "  ⚠️  ERRO: Encontrada '$wrong' - $message\n";
        $errors[] = $message;
    } elseif (is_dir($baseDir . '/' . $correct)) {
        echo "  ✅ $correct (correto)\n";
    }
}

echo "\n";

// Verificar namespaces nos arquivos
echo "🏷️  VERIFICANDO NAMESPACES:\n";

$namespaceChecks = [
    'app/Core/Router.php' => 'namespace App\Core;',
    'app/Controllers/PublicController.php' => 'namespace App\Controllers;',
    'app/Models/User.php' => 'namespace App\Models;',
];

foreach ($namespaceChecks as $file => $expectedNamespace) {
    $path = $baseDir . '/' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        if (strpos($content, $expectedNamespace) !== false) {
            echo "  ✅ $file ($expectedNamespace)\n";
        } else {
            echo "  ⚠️  $file (namespace pode estar incorreto)\n";
            $warnings[] = "Verifique namespace em $file";
        }
    }
}

echo "\n";

// Resumo
echo str_repeat("=", 60) . "\n";
echo "📊 RESUMO:\n";
echo str_repeat("=", 60) . "\n";

if (empty($errors)) {
    echo "✅ Tudo OK! Estrutura correta para upload.\n";
    echo "\n";
    echo "📤 PRÓXIMOS PASSOS:\n";
    echo "1. Delete a pasta 'app/' do servidor\n";
    echo "2. Faça upload da pasta 'app/' completa\n";
    echo "3. Verifique se config/config.php foi enviado\n";
    echo "4. Teste: https://cleacasamentos.com.br/\n";
} else {
    echo "❌ ENCONTRADOS " . count($errors) . " ERROS:\n";
    foreach ($errors as $error) {
        echo "  • $error\n";
    }
    echo "\n";
    echo "⚠️  CORRIJA OS ERROS ANTES DE FAZER UPLOAD!\n";
}

if (!empty($warnings)) {
    echo "\n";
    echo "⚠️  AVISOS (" . count($warnings) . "):\n";
    foreach ($warnings as $warning) {
        echo "  • $warning\n";
    }
}

echo "\n";
echo str_repeat("=", 60) . "\n";
echo "✅ Total de itens OK: " . count($success) . "\n";
echo "⚠️  Total de avisos: " . count($warnings) . "\n";
echo "❌ Total de erros: " . count($errors) . "\n";
echo str_repeat("=", 60) . "\n";

// Listar arquivos para upload
if (empty($errors)) {
    echo "\n";
    echo "📦 LISTA DE PASTAS PARA UPLOAD:\n";
    echo str_repeat("=", 60) . "\n";
    echo "1. app/              (COMPLETA - DELETE a antiga primeiro)\n";
    echo "2. config/           (SE NÃO EXISTIR NO SERVIDOR)\n";
    echo "3. public/           (SE NÃO EXISTIR NO SERVIDOR)\n";
    echo "4. .htaccess         (na raiz - /public_html/)\n";
    echo "5. index.php         (na raiz - /public_html/)\n";
    echo str_repeat("=", 60) . "\n";
}

echo "\n✨ Verificação concluída!\n";
