<?php

namespace App\Controllers;

use App\Core\Database;

class AuthController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Exibir formulário de login
    public function login()
    {
        // Se já estiver logado, redirecionar
        if (isset($_SESSION['user_id'])) {
            $this->redirectToDashboard($_SESSION['user_role']);
        }

        require_once dirname(__DIR__) . '/Views/auth/login.php';
    }

    // Processar login
    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('login'));
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Validação
        if (empty($email) || empty($password)) {
            $_SESSION['error_message'] = 'Preencha todos os campos.';
            header('Location: ' . url('login'));
            exit;
        }

        // Buscar usuário
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Verificar senha
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $_SESSION['error_message'] = 'Email ou senha incorretos.';
            header('Location: ' . url('login'));
            exit;
        }

        // Login bem-sucedido
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['logged_in'] = true;

        $this->redirectToDashboard($user['role']);
    }

    // Logout
    public function logout()
    {
        session_destroy();
        $_SESSION = [];
        header('Location: ' . url('/'));
        exit;
    }

    private function redirectToDashboard($role)
    {
        switch ($role) {
            case 'admin':
                header('Location: ' . url('admin/dashboard'));
                break;
            case 'vendor':
                header('Location: ' . url('vendor/dashboard'));
                break;
            case 'client':
                header('Location: ' . url('client/dashboard'));
                break;
            default:
                header('Location: ' . url('/'));
        }
        exit;
    }
}
