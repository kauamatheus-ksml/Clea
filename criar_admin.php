<?php
require_once('config/config.php');

echo "<h2>Script de Criação de Usuário Admin - Clea Casamentos</h2>\n";

try {
    // Connect to database
    $db = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    echo "<p>✅ Conexão com banco de dados estabelecida com sucesso!</p>\n";

    // Check if admin already exists
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'admin'");
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result['count'] > 0) {
        echo "<p>ℹ️ Já existe(m) " . $result['count'] . " usuário(s) admin no sistema.</p>\n";

        // Show existing admins
        $stmt = $db->prepare("SELECT name, email, created_at FROM users WHERE role = 'admin' ORDER BY created_at");
        $stmt->execute();
        $admins = $stmt->fetchAll();

        echo "<h3>Administradores Existentes:</h3>\n";
        echo "<ul>\n";
        foreach ($admins as $admin) {
            echo "<li><strong>" . htmlspecialchars($admin['name']) . "</strong> - " .
                 htmlspecialchars($admin['email']) . " (criado em " .
                 date('d/m/Y H:i', strtotime($admin['created_at'])) . ")</li>\n";
        }
        echo "</ul>\n";

    } else {
        echo "<p>⚠️ Nenhum usuário admin encontrado. Criando usuário admin padrão...</p>\n";

        // Create default admin user
        $admin_data = [
            'name' => 'Administrador Clea',
            'email' => 'admin@cleacasamentos.com.br',
            'password' => 'admin123', // Change this in production!
            'phone' => null,
            'role' => 'admin'
        ];

        $password_hash = password_hash($admin_data['password'], PASSWORD_DEFAULT);

        $stmt = $db->prepare("
            INSERT INTO users (name, email, password_hash, phone, role, is_active, created_at)
            VALUES (?, ?, ?, ?, ?, 1, NOW())
        ");

        $stmt->execute([
            $admin_data['name'],
            $admin_data['email'],
            $password_hash,
            $admin_data['phone'],
            $admin_data['role']
        ]);

        echo "<p>✅ Usuário admin criado com sucesso!</p>\n";
        echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 8px; margin: 10px 0;'>\n";
        echo "<h3>🔐 Dados de Acesso do Admin:</h3>\n";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($admin_data['email']) . "</p>\n";
        echo "<p><strong>Senha:</strong> " . htmlspecialchars($admin_data['password']) . "</p>\n";
        echo "<p style='color: #d63031;'><strong>⚠️ IMPORTANTE:</strong> Altere esta senha imediatamente após o primeiro login!</p>\n";
        echo "</div>\n";
    }

    // Show system statistics
    echo "<h3>📊 Estatísticas do Sistema:</h3>\n";

    $stats = [];

    // Count users by role
    $stmt = $db->query("SELECT role, COUNT(*) as count FROM users WHERE is_active = 1 GROUP BY role");
    $userStats = $stmt->fetchAll();

    echo "<ul>\n";
    foreach ($userStats as $stat) {
        $roleNames = [
            'admin' => 'Administradores',
            'client' => 'Clientes',
            'vendor' => 'Fornecedores'
        ];
        echo "<li>" . ($roleNames[$stat['role']] ?? ucfirst($stat['role'])) . ": " . $stat['count'] . "</li>\n";
    }

    // Other statistics
    $stmt = $db->query("SELECT COUNT(*) as count FROM weddings");
    $weddingCount = $stmt->fetch()['count'];
    echo "<li>Casamentos cadastrados: " . $weddingCount . "</li>\n";

    $stmt = $db->query("SELECT COUNT(*) as count FROM vendor_profiles WHERE is_approved = 1");
    $approvedVendors = $stmt->fetch()['count'];
    echo "<li>Fornecedores aprovados: " . $approvedVendors . "</li>\n";

    $stmt = $db->query("SELECT COUNT(*) as count FROM vendor_profiles WHERE is_approved = 0");
    $pendingVendors = $stmt->fetch()['count'];
    echo "<li>Fornecedores pendentes de aprovação: " . $pendingVendors . "</li>\n";

    echo "</ul>\n";

    echo "<hr style='margin: 20px 0;'>\n";
    echo "<p><a href='public/login.php' style='background: #652929; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🚀 Acessar Sistema</a></p>\n";
    echo "<p><a href='public/index.php' style='background: #f2abb1; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px;'>🌐 Ver Site Público</a></p>\n";

} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Erro de conexão: " . htmlspecialchars($e->getMessage()) . "</p>\n";
    echo "<p>Verifique as configurações do banco de dados em config/config.php</p>\n";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro: " . htmlspecialchars($e->getMessage()) . "</p>\n";
}

echo "<hr style='margin: 20px 0;'>\n";
echo "<p><small>💡 <strong>Dica:</strong> Este arquivo pode ser removido após a configuração inicial do sistema.</small></p>\n";
?>