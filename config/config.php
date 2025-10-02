<?php

// Configuração do Banco de Dados Remoto (Hostinger)
define('DB_HOST', 'srv406.hstgr.io'); // Ex: 123.456.789.10
define('DB_USER', 'u383946504_cleacasamentos');       // Ex: u123456789_clea
define('DB_PASS', 'Aaku_2004@');
define('DB_NAME', 'u383946504_cleacasamentos');         // Ex: u123456789_clea_db

// Configuração Base da Aplicação
define('APP_NAME', 'Clea Casamentos');

// Detectar ambiente e definir BASE_URL automaticamente
$isLocalhost = in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1', '::1']);
if ($isLocalhost) {
    // Ambiente de desenvolvimento
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    define('BASE_URL', $protocol . '://' . $host);
} else {
    // Ambiente de produção
    define('BASE_URL', 'https://www.cleacasamentos.com.br');
}

// Outras configurações (chaves de API, etc.)
define('STRIPE_API_KEY', 'sua_chave_stripe_aqui');
define('CLICKSIGN_API_KEY', 'sua_chave_clicksign_aqui');

?>