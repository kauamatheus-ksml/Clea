<?php
// Script para criar dados de teste no sistema Clea Casamentos

// Definir ROOT_PATH
define('ROOT_PATH', __DIR__);

// Autoloader para as classes do projeto
spl_autoload_register(function ($class) {
    $class = str_replace('App\\', '', $class);
    $file = ROOT_PATH . '/app/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Carregar configuração do banco
require_once ROOT_PATH . '/config/config.php';

use App\Core\Database;

try {
    $db = Database::getInstance()->getConnection();
    echo "Conectado ao banco com sucesso!\n";

    // Criar usuário cliente de teste (noivos)
    $stmt = $db->prepare("
        INSERT INTO users (name, email, password_hash, role, is_active, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)
    ");

    $password = password_hash('123456', PASSWORD_DEFAULT);
    $stmt->execute(['Marina Silva', 'marina@teste.com', $password, 'client', 1]);
    $clientUserId = $db->lastInsertId() ?: $db->query("SELECT id FROM users WHERE email = 'marina@teste.com'")->fetch()['id'];

    echo "Usuário cliente criado: ID $clientUserId\n";

    // Criar casamento de teste
    $weddingDate = date('Y-m-d H:i:s', strtotime('+6 months')); // 6 meses no futuro
    $stmt = $db->prepare("
        INSERT INTO weddings (client_user_id, partner_name, wedding_date, location_details, estimated_guests, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)
    ");

    $stmt->execute([
        $clientUserId,
        'Frederico Santos',
        $weddingDate,
        'Quinta da Baronesa - São Paulo/SP',
        120
    ]);
    $weddingId = $db->lastInsertId() ?: $db->query("SELECT id FROM weddings WHERE client_user_id = $clientUserId")->fetch()['id'];

    echo "Casamento criado: ID $weddingId, Data: $weddingDate\n";

    // Criar usuários fornecedores de teste
    $vendors = [
        ['Foto Studio Premium', 'foto@studio.com', 'fotografia', 'São Paulo', 'SP', 'Especialistas em fotografia de casamento minimalista'],
        ['Buffet Elegance', 'contato@elegance.com', 'buffet', 'São Paulo', 'SP', 'Gastronomia refinada para eventos especiais'],
        ['Cerimonial Bella Vista', 'cerimonial@bella.com', 'cerimonial', 'São Paulo', 'SP', 'Cerimonialistas especializados em casamentos'],
        ['Quinta da Baronesa', 'quinta@baronesa.com', 'espaco', 'São Paulo', 'SP', 'Espaço para eventos e casamentos']
    ];

    foreach ($vendors as $vendor) {
        // Criar usuário fornecedor
        $stmt = $db->prepare("
            INSERT INTO users (name, email, password_hash, role, is_active, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)
        ");

        $stmt->execute([$vendor[0], $vendor[1], $password, 'vendor', 1]);
        $vendorUserId = $db->lastInsertId() ?: $db->query("SELECT id FROM users WHERE email = '{$vendor[1]}'")->fetch()['id'];

        // Criar perfil do fornecedor
        $stmt = $db->prepare("
            INSERT INTO vendor_profiles (user_id, business_name, category, city, state, description, is_approved)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE user_id=VALUES(user_id)
        ");

        $stmt->execute([
            $vendorUserId,
            $vendor[0],
            $vendor[2],
            $vendor[3],
            $vendor[4],
            $vendor[5],
            1
        ]);

        echo "Fornecedor criado: {$vendor[0]} (ID: $vendorUserId)\n";
    }

    // Criar contratos de teste
    $contracts = [
        ['Foto Studio Premium', 3500.00, 'active'],
        ['Buffet Elegance', 12000.00, 'active'],
        ['DJ Music Events', 2500.00, 'pending_signature']
    ];

    foreach ($contracts as $contract) {
        // Buscar ID do fornecedor
        $stmt = $db->prepare("SELECT u.id FROM users u JOIN vendor_profiles vp ON u.id = vp.user_id WHERE vp.business_name = ?");
        $stmt->execute([$contract[0]]);
        $vendorUserId = $stmt->fetch()['id'];

        if ($vendorUserId) {
            $stmt = $db->prepare("
                INSERT INTO contracts (wedding_id, vendor_user_id, service_description, total_value, commission_rate, status, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE total_value=VALUES(total_value)
            ");

            $stmt->execute([
                $weddingId,
                $vendorUserId,
                "Serviços de {$contract[0]} para o casamento",
                $contract[1],
                0.20,
                $contract[2]
            ]);

            echo "Contrato criado: {$contract[0]} - R$ {$contract[1]} ({$contract[2]})\n";
        }
    }

    // Criar alguns convidados de teste
    $guests = [
        ['João Silva', 'confirmed', 1],
        ['Maria Oliveira', 'confirmed', 1],
        ['Pedro Santos', 'pending', 2],
        ['Ana Costa', 'confirmed', 2],
        ['Carlos Lima', 'confirmed', 3]
    ];

    foreach ($guests as $guest) {
        $stmt = $db->prepare("
            INSERT INTO guests (wedding_id, name, confirmation_status, seating_table)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE confirmation_status=VALUES(confirmation_status)
        ");

        $stmt->execute([
            $weddingId,
            $guest[0],
            $guest[1],
            $guest[2]
        ]);
    }

    echo "Convidados de teste criados!\n";

    // Criar algumas mensagens de teste
    $vendors = $db->query("SELECT u.id FROM users u WHERE u.role = 'vendor' LIMIT 2")->fetchAll();

    foreach ($vendors as $vendor) {
        $stmt = $db->prepare("
            INSERT INTO messages (wedding_id, sender_user_id, receiver_user_id, message_content, sent_at)
            VALUES (?, ?, ?, ?, NOW())
        ");

        $stmt->execute([
            $weddingId,
            $vendor['id'],
            $clientUserId,
            "Olá! Estamos ansiosos para trabalhar no seu casamento. Quando podemos agendar uma reunião?"
        ]);
    }

    echo "Mensagens de teste criadas!\n";

    echo "\n=== DADOS DE TESTE CRIADOS COM SUCESSO! ===\n";
    echo "Login do cliente:\n";
    echo "Email: marina@teste.com\n";
    echo "Senha: 123456\n";
    echo "Data do casamento: " . date('d/m/Y', strtotime($weddingDate)) . "\n";
    echo "Acesse: http://localhost/public/login.php\n";

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    exit(1);
}
?>