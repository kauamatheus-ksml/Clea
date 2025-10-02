<?php
/**
 * Script para atualizar links nas views públicas
 * Execute uma vez para corrigir todos os links
 */

$files = [
    __DIR__ . '/app/Views/public/home.php',
    __DIR__ . '/app/Views/public/vendors.php',
    __DIR__ . '/app/Views/public/vendor-detail.php',
    __DIR__ . '/app/Views/public/about.php',
    __DIR__ . '/app/Views/public/contact.php',
    __DIR__ . '/app/Views/public/register.php'
];

$replacements = [
    // Links do header
    'href="/"' => 'href="<?= url(\'/\') ?>"',
    'href="/vendors"' => 'href="<?= url(\'vendors\') ?>"',
    'href="/about"' => 'href="<?= url(\'about\') ?>"',
    'href="/contact"' => 'href="<?= url(\'contact\') ?>"',
    'href="/login"' => 'href="<?= url(\'login.php\') ?>"',
    'href="/register"' => 'href="<?= url(\'register\') ?>"',

    // Forms
    'action="/contact"' => 'action="<?= url(\'contact\') ?>"',
    'action="/register"' => 'action="<?= url(\'register\') ?>"',

    // Links vendor detail
    'href="/vendor-detail?id=' => 'href="<?= url(\'vendor-detail?id=',
    '<?= $vendor[\'id\'] ?>"' => '\' . $vendor[\'id\']) ?>"',
];

foreach ($files as $file) {
    if (!file_exists($file)) {
        echo "❌ Arquivo não encontrado: $file\n";
        continue;
    }

    $content = file_get_contents($file);
    $originalContent = $content;

    foreach ($replacements as $search => $replace) {
        $content = str_replace($search, $replace, $content);
    }

    if ($content !== $originalContent) {
        file_put_contents($file, $content);
        echo "✅ Atualizado: " . basename($file) . "\n";
    } else {
        echo "ℹ️  Sem alterações: " . basename($file) . "\n";
    }
}

echo "\n✨ Processo concluído!\n";
echo "\n📝 Próximos passos:\n";
echo "1. Suba os arquivos atualizados para o servidor\n";
echo "2. Execute a migração: mysql -u u383946504_cleacasamentos -p u383946504_cleacasamentos < migration_leads.sql\n";
echo "3. Acesse https://cleacasamentos.com.br/ para testar\n";
