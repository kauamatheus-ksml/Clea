<?php

namespace App\Core;

class Auth
{
    public static function checkLogin(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public static function requireLogin(): void
    {
        if (!self::checkLogin()) {
            header('Location: /public/login.php');
            exit;
        }
    }

    public static function checkRole(array $allowedRoles): void
    {
        self::requireLogin();

        if (!in_array($_SESSION['user_role'], $allowedRoles)) {
            header('Location: /public/dashboard.php');
            exit;
        }
    }

    public static function getCurrentUser(): ?array
    {
        if (!self::checkLogin()) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'role' => $_SESSION['user_role'],
            'created_at' => $_SESSION['user_created_at'] ?? date('Y-m-d H:i:s'),
            'phone' => $_SESSION['user_phone'] ?? null
        ];
    }

    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: /public/login.php');
        exit;
    }

    public static function redirectToDashboard(): void
    {
        if (!self::checkLogin()) {
            header('Location: login.php');
            exit;
        }

        switch ($_SESSION['user_role']) {
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
                header('Location: login.php');
        }
        exit;
    }

    // Métodos de compatibilidade para as novas views
    public static function isLoggedIn(): bool
    {
        return self::checkLogin();
    }

    public static function getUser(): ?array
    {
        return self::getCurrentUser();
    }

    public static function getUserRole(): ?string
    {
        if (!self::checkLogin()) {
            return null;
        }
        return $_SESSION['user_role'] ?? null;
    }
}
?>