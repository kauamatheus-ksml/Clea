<?php
session_start();
require_once('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

// Get database connection
try {
    $db = new PDO(
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
    $_SESSION['error_message'] = 'Erro interno do servidor. Tente novamente mais tarde.';
    header('Location: register.php');
    exit;
}

// Validate and sanitize input
$name = trim($_POST['name']);
$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$phone = trim($_POST['phone']);
$role = $_POST['role'];

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = 'Nome é obrigatório.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email inválido.';
}

if (strlen($password) < 6) {
    $errors[] = 'A senha deve ter pelo menos 6 caracteres.';
}

if ($password !== $confirm_password) {
    $errors[] = 'As senhas não coincidem.';
}

if (!in_array($role, ['client', 'vendor'])) {
    $errors[] = 'Tipo de conta inválido.';
}

// Check if email already exists
try {
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = 'Este email já está cadastrado.';
    }
} catch (PDOException $e) {
    error_log("Erro ao verificar email: " . $e->getMessage());
    $errors[] = 'Erro interno. Tente novamente.';
}

// Vendor-specific validation
if ($role === 'vendor') {
    $business_name = trim($_POST['business_name']);
    $category = $_POST['category'];
    $description = trim($_POST['description']);
    $city = trim($_POST['city']) ?: 'Fortaleza';
    $state = strtoupper(trim($_POST['state'])) ?: 'CE';

    if (empty($business_name)) {
        $errors[] = 'Nome da empresa é obrigatório para fornecedores.';
    }

    if (!in_array($category, ['espaco', 'fotografia', 'cerimonial', 'buffet', 'decoracao', 'trajes'])) {
        $errors[] = 'Categoria de fornecedor inválida.';
    }
}

// If there are errors, return to form
if (!empty($errors)) {
    $_SESSION['error_message'] = implode('<br>', $errors);
    header('Location: register.php');
    exit;
}

// Create user account
try {
    $db->beginTransaction();

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $db->prepare("
        INSERT INTO users (name, email, password_hash, phone, role, is_active, created_at)
        VALUES (?, ?, ?, ?, ?, 1, NOW())
    ");
    $stmt->execute([$name, $email, $password_hash, $phone, $role]);

    $user_id = $db->lastInsertId();

    // If vendor, create vendor profile
    if ($role === 'vendor') {
        $stmt = $db->prepare("
            INSERT INTO vendor_profiles (user_id, business_name, description, category, city, state, is_approved)
            VALUES (?, ?, ?, ?, ?, ?, 0)
        ");
        $stmt->execute([$user_id, $business_name, $description, $category, $city, $state]);
    }

    $db->commit();

    // Success message
    if ($role === 'vendor') {
        $_SESSION['success_message'] = 'Conta criada com sucesso! Seu perfil será analisado pela equipe Clea em até 48 horas.';
    } else {
        $_SESSION['success_message'] = 'Conta criada com sucesso! Você já pode fazer login.';
    }

    header('Location: login.php');
    exit;

} catch (PDOException $e) {
    $db->rollBack();
    error_log("Erro ao criar conta: " . $e->getMessage());
    $_SESSION['error_message'] = 'Erro ao criar conta. Tente novamente.';
    header('Location: register.php');
    exit;
}
?>