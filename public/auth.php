<?php
session_start();
require_once('../config/config.php');

class Auth {
    private $db;

    public function __construct() {
        try {
            $this->db = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            error_log("Erro de conexão: " . $e->getMessage());
            die("Erro interno do servidor. Tente novamente mais tarde.");
        }
    }

    public function login($email, $password) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                // Login successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['logged_in'] = true;

                // Redirect based on user role
                switch ($user['role']) {
                    case 'admin':
                        header('Location: app.php?url=/admin/dashboard');
                        break;
                    case 'vendor':
                        header('Location: app.php?url=/vendor/dashboard');
                        break;
                    case 'client':
                        header('Location: app.php?url=/client/dashboard');
                        break;
                    default:
                        header('Location: dashboard.php');
                }
                exit;
            } else {
                $_SESSION['error_message'] = 'Email ou senha incorretos.';
                header('Location: login.php');
                exit;
            }
        } catch (PDOException $e) {
            error_log("Erro no login: " . $e->getMessage());
            $_SESSION['error_message'] = 'Erro interno. Tente novamente.';
            header('Location: login.php');
            exit;
        }
    }

    public function logout() {
        session_destroy();
        header('Location: login.php');
        exit;
    }

    public static function checkLogin() {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header('Location: ../login.php');
            exit;
        }
    }

    public static function checkRole($allowedRoles) {
        self::checkLogin();

        if (!in_array($_SESSION['user_role'], $allowedRoles)) {
            $_SESSION['error_message'] = 'Acesso negado.';
            header('Location: ../login.php');
            exit;
        }
    }
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new Auth();

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'login':
                $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'];

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $_SESSION['error_message'] = 'Email inválido.';
                    header('Location: login.php');
                    exit;
                }

                if (empty($password)) {
                    $_SESSION['error_message'] = 'Senha é obrigatória.';
                    header('Location: login.php');
                    exit;
                }

                $auth->login($email, $password);
                break;

            case 'logout':
                $auth->logout();
                break;
        }
    }
}

// If accessed directly via GET, redirect to login
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header('Location: login.php');
    exit;
}
?>