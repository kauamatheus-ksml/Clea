<?php
// SCRIPT DE CORREÇÃO AUTOMÁTICA - EXECUTE APENAS UMA VEZ!

echo "<h2>🔧 Corrigindo pastas automaticamente...</h2>";

$appDir = dirname(__DIR__) . '/app';

// Pastas que precisam ter letra maiúscula
$folders = ['core' => 'Core', 'controllers' => 'Controllers', 'models' => 'Models', 'views' => 'Views'];

$fixed = 0;
foreach ($folders as $lower => $upper) {
    $lowerPath = "$appDir/$lower";
    $upperPath = "$appDir/$upper";

    // Verifica se existe a pasta em minúscula
    if (is_dir($lowerPath) && !is_dir($upperPath)) {
        echo "<p>📁 Corrigindo: $lower → $upper</p>";

        // Renomeia usando pasta temporária (solução para case-insensitive)
        $tempPath = "$appDir/{$upper}_temp_" . time();

        if (@rename($lowerPath, $tempPath)) {
            if (@rename($tempPath, $upperPath)) {
                echo "<p style='color:green'>✅ Sucesso: $upper</p>";
                $fixed++;
            } else {
                echo "<p style='color:red'>❌ Erro na segunda renomeação: $upper</p>";
                @rename($tempPath, $lowerPath); // Reverte
            }
        } else {
            echo "<p style='color:red'>❌ Erro ao renomear: $lower</p>";
        }
    } elseif (is_dir($upperPath)) {
        echo "<p style='color:blue'>ℹ️ Já correto: $upper</p>";
        $fixed++;
    }
}

echo "<hr>";
echo "<h3>📊 Resultado:</h3>";
echo "<p><strong>$fixed de 4 pastas corrigidas!</strong></p>";

if ($fixed === 4) {
    echo "<p style='color:green; font-size:20px'>✅ <strong>TUDO PRONTO!</strong></p>";
    echo "<p>🌐 Agora acesse: <a href='https://cleacasamentos.com.br/'>https://cleacasamentos.com.br/</a></p>";
    echo "<hr>";
    echo "<p style='color:orange'>⚠️ <strong>IMPORTANTE:</strong> Depois que o site funcionar, DELETE este arquivo (fix.php) por segurança!</p>";
} else {
    echo "<p style='color:red'>❌ Algo deu errado. Verifique as permissões do servidor.</p>";
}
?>
