<?php

// Define uma constante para o diretório raiz do projeto para facilitar os includes
define('ROOT_PATH', dirname(__DIR__));

// Exibe todos os erros durante o desenvolvimento (remover em produção)
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Iniciando Aplicação...</h1>";

// Inclui os arquivos principais da aplicação
require_once ROOT_PATH . '/app/core/Database.php';
require_once ROOT_PATH . '/app/core/Router.php';

echo "<p>Arquivos Core carregados.</p>";

// Instancia o roteador para começar a tratar a requisição
$router = new Router();

?>