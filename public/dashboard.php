<?php
session_start();
require_once('../config/config.php');

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Redirect to appropriate dashboard based on role
switch ($_SESSION['user_role']) {
    case 'admin':
        header('Location: admin/dashboard.php');
        break;
    case 'vendor':
        header('Location: vendor/dashboard.php');
        break;
    case 'client':
        header('Location: client/dashboard.php');
        break;
    default:
        // Unknown role, logout and redirect to login
        session_destroy();
        header('Location: login.php');
}
exit;
?>