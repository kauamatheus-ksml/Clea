<?php
session_start();

// Define uma constante para o diretório raiz do projeto
define('ROOT_PATH', dirname(__DIR__));

// Carregar configurações
require_once ROOT_PATH . '/config/config.php';

// Carregar funções helper
require_once ROOT_PATH . '/app/helpers.php';

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

// Instanciar o roteador
$router = new Router();

// === ROTAS PÚBLICAS ===
$router->get('/', [App\Controllers\PublicController::class, 'home']);
$router->get('/vendors', [App\Controllers\PublicController::class, 'vendors']);
$router->get('/vendor-detail', function() {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $controller = new App\Controllers\PublicController();
        $controller->vendorDetail($id);
    } else {
        header('Location: /vendors');
    }
});
$router->get('/about', [App\Controllers\PublicController::class, 'about']);
$router->get('/contact', [App\Controllers\PublicController::class, 'contact']);
$router->post('/contact', [App\Controllers\PublicController::class, 'submitContact']);
$router->get('/register', [App\Controllers\PublicController::class, 'register']);
$router->post('/register', [App\Controllers\PublicController::class, 'submitRegister']);

// === ROTAS DE AUTENTICAÇÃO ===
$router->get('/login', [App\Controllers\AuthController::class, 'login']);
$router->post('/authenticate', [App\Controllers\AuthController::class, 'authenticate']);
$router->get('/logout', [App\Controllers\AuthController::class, 'logout']);

// === ROTAS AUTENTICADAS ===

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

// Despachar a rota
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($url);
?>
