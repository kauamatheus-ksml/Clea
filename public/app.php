<?php
session_start();

// Define uma constante para o diretório raiz do projeto
define('ROOT_PATH', dirname(__DIR__));

// Carregar configurações
require_once ROOT_PATH . '/config/config.php';

// Autoloader para as classes do projeto
spl_autoload_register(function ($class) {
    $class = str_replace('App\\', '', $class);
    $file = ROOT_PATH . '/app/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

use App\Core\Router;
use App\Core\Auth;

// Verificar se o usuário está logado
if (!Auth::checkLogin()) {
    header('Location: login.php');
    exit;
}

// Instanciar o roteador
$router = new Router();

// Rotas Admin (FR-5)
$router->get('/admin/dashboard', [App\Controllers\AdminController::class, 'dashboard']);
$router->get('/admin/users', [App\Controllers\AdminController::class, 'users']);
$router->get('/admin/vendors', [App\Controllers\AdminController::class, 'vendors']);
$router->get('/admin/financial', [App\Controllers\AdminController::class, 'financial']);
$router->get('/admin/contracts', [App\Controllers\AdminController::class, 'contracts']);
$router->get('/admin/messages', [App\Controllers\AdminController::class, 'messages']);

// Rotas Vendor (FR-4)
$router->get('/vendor/dashboard', [App\Controllers\VendorController::class, 'dashboard']);
$router->get('/vendor/events', [App\Controllers\VendorController::class, 'events']);
$router->get('/vendor/financial', [App\Controllers\VendorController::class, 'financial']);
$router->get('/vendor/messages', [App\Controllers\VendorController::class, 'messages']);
$router->get('/vendor/profile', [App\Controllers\VendorController::class, 'profile']);

// Rotas Client (FR-3)
$router->get('/client/dashboard', [App\Controllers\ClientController::class, 'dashboard']);
$router->get('/client/wedding', [App\Controllers\ClientController::class, 'wedding']);
$router->get('/client/vendors', [App\Controllers\ClientController::class, 'vendors']);
$router->get('/client/contracts', [App\Controllers\ClientController::class, 'contracts']);
$router->get('/client/financial', [App\Controllers\ClientController::class, 'financial']);
$router->get('/client/guests', [App\Controllers\ClientController::class, 'guests']);
$router->get('/client/messages', [App\Controllers\ClientController::class, 'messages']);

// Rota raiz - redireciona para o dashboard apropriado
$router->get('/', function() {
    Auth::redirectToDashboard();
});

// Despachar a rota
$url = $_GET['url'] ?? '/';
$router->dispatch($url);
?>