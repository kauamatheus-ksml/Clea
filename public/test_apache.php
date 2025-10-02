<?php
// Teste de configuração do Apache

echo "<h1>Teste de Configuração Apache - Clea Casamentos</h1>";

// Verificar módulos Apache
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "<h2>Módulos Apache Carregados:</h2>";
    echo "<ul>";

    $important_modules = ['mod_rewrite', 'mod_headers', 'mod_deflate', 'mod_expires'];
    foreach ($important_modules as $module) {
        $status = in_array($module, $modules) ? '✅ ATIVO' : '❌ INATIVO';
        echo "<li><strong>$module:</strong> $status</li>";
    }
    echo "</ul>";
} else {
    echo "<p>❌ Função apache_get_modules() não disponível</p>";
}

// Verificar .htaccess
$htaccess_path = __DIR__ . '/.htaccess';
if (file_exists($htaccess_path)) {
    echo "<h2>Arquivo .htaccess:</h2>";
    echo "<p>✅ Encontrado em: $htaccess_path</p>";
    echo "<pre>" . htmlspecialchars(file_get_contents($htaccess_path)) . "</pre>";
} else {
    echo "<p>❌ Arquivo .htaccess não encontrado</p>";
}

// Teste de URL Rewriting
echo "<h2>Teste de URL Rewriting:</h2>";
echo "<p>URL atual: " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p>Parâmetro 'url': " . ($_GET['url'] ?? 'não definido') . "</p>";

// Links de teste
echo "<h2>Links de Teste:</h2>";
echo "<ul>";
echo "<li><a href='app.php?url=/vendor/dashboard'>Dashboard Vendor (direto)</a></li>";
echo "<li><a href='vendor/dashboard'>Dashboard Vendor (rewrite)</a></li>";
echo "<li><a href='login.php'>Login</a></li>";
echo "<li><a href='css/dashboard.css'>CSS Dashboard</a></li>";
echo "</ul>";

// Informações do PHP e servidor
echo "<h2>Informações do Servidor:</h2>";
echo "<p><strong>Servidor:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p><strong>PHP:</strong> " . PHP_VERSION . "</p>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p><strong>Script Name:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";

// Verificar se sessão está funcionando
session_start();
echo "<h2>Teste de Sessão:</h2>";
if (isset($_SESSION['test'])) {
    echo "<p>✅ Sessão funcionando - Contador: " . ++$_SESSION['test'] . "</p>";
} else {
    $_SESSION['test'] = 1;
    echo "<p>✅ Sessão iniciada - Contador: 1</p>";
}

// Base URL detection
echo "<h2>Base URL Detection:</h2>";
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$base_url = $protocol . '://' . $host;
echo "<p><strong>Base URL:</strong> $base_url</p>";

?>
<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h1 { color: #652929; }
h2 { color: #f2abb1; border-bottom: 1px solid #f2abb1; padding-bottom: 5px; }
pre { background: #f5f5f5; padding: 10px; border-radius: 5px; overflow-x: auto; }
ul { margin: 10px 0; }
li { margin: 5px 0; }
a { color: #652929; text-decoration: none; }
a:hover { text-decoration: underline; }
</style>