<?php
// Script para criar um usuário fornecedor de teste

// Definir ROOT_PATH
define('ROOT_PATH', __DIR__);

// Carregar configurações
require_once ROOT_PATH . '/config/config.php';

// Autoloader para as classes do projeto
spl_autoload_register(function ($class) {
    $class = str_replace('App\\\\', '', $class);
    $file = ROOT_PATH . '/app/' . str_replace('\\\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    echo "Conectado ao banco com sucesso!\n";

    // Criar usuário fornecedor de teste
    $stmt = $db->prepare("
        INSERT INTO users (name, email, password_hash, role, is_active, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)
    ");

    $password = password_hash('123456', PASSWORD_DEFAULT);
    $stmt->execute(['João Fotógrafo', 'joao@foto.com', $password, 'vendor', 1]);
    $vendorUserId = $db->lastInsertId() ?: $db->query("SELECT id FROM users WHERE email = 'joao@foto.com'")->fetch()['id'];

    echo "Usuário fornecedor criado: ID $vendorUserId\n";

    // Criar perfil do fornecedor
    $stmt = $db->prepare("
        INSERT INTO vendor_profiles (user_id, business_name, category, city, state, description, is_approved)
        VALUES (?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE user_id=VALUES(user_id)
    ");

    $stmt->execute([
        $vendorUserId,
        'João Fotógrafo Studio',
        'fotografia',
        'São Paulo',
        'SP',
        'Especialista em fotografia de casamento com mais de 10 anos de experiência',
        1
    ]);

    echo "Perfil do fornecedor criado!\n";

    echo "\n=== FORNECEDOR DE TESTE CRIADO COM SUCESSO! ===\n";
    echo "Login do fornecedor:\n";
    echo "Email: joao@foto.com\n";
    echo "Senha: 123456\n";
    echo "Acesse: http://localhost/public/login.php\n";

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    exit(1);
}
?>