<?php
session_start();
require_once('../../config/config.php');
require_once('../auth.php');

// Check if user is logged in and is admin
Auth::checkRole(['admin']);

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
    die("Erro de conexão: " . $e->getMessage());
}

// Get statistics
$stats = [];

// Total users by role
$stmt = $db->query("SELECT role, COUNT(*) as count FROM users WHERE is_active = 1 GROUP BY role");
$userStats = $stmt->fetchAll();
foreach ($userStats as $stat) {
    $stats['users'][$stat['role']] = $stat['count'];
}

// Total weddings
$stmt = $db->query("SELECT COUNT(*) as count FROM weddings");
$stats['weddings'] = $stmt->fetch()['count'];

// Active contracts
$stmt = $db->query("SELECT COUNT(*) as count FROM contracts WHERE status = 'active'");
$stats['active_contracts'] = $stmt->fetch()['count'];

// Pending vendor approvals
$stmt = $db->query("SELECT COUNT(*) as count FROM vendor_profiles WHERE is_approved = 0");
$stats['pending_vendors'] = $stmt->fetch()['count'];

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Dashboard Admin - Clea Casamentos</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;1,400&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet"/>
    <style>
        :root {
            --primary-light: #fff3e0;
            --primary-medium: #f2abb1;
            --primary-dark: #652929;
            --text-primary: #652929;
            --text-secondary: #8b4444;
            --background: #fff3e0;
            --card-bg: #fefcf8;
            --sidebar-bg: #652929;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: var(--sidebar-bg);
            color: white;
            padding: 20px 0;
        }

        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h2 {
            font-family: 'Lora', serif;
            font-size: 24px;
        }

        .sidebar-header p {
            font-size: 14px;
            opacity: 0.8;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .sidebar-menu a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        .header {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(101, 41, 41, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-family: 'Lora', serif;
            color: var(--text-primary);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logout-btn {
            background: linear-gradient(135deg, var(--primary-medium), var(--primary-dark));
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(101, 41, 41, 0.08);
            border: 1px solid rgba(242, 171, 177, 0.2);
        }

        .stat-card h3 {
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 4px;
        }

        .stat-card .label {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .content-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(101, 41, 41, 0.08);
            border: 1px solid rgba(242, 171, 177, 0.2);
        }

        .content-section h2 {
            font-family: 'Lora', serif;
            color: var(--text-primary);
            margin-bottom: 20px;
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .action-btn {
            display: block;
            background: linear-gradient(135deg, var(--primary-light), var(--card-bg));
            border: 2px solid var(--primary-medium);
            color: var(--text-primary);
            padding: 16px;
            border-radius: 8px;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s;
            font-weight: 500;
        }

        .action-btn:hover {
            background: var(--primary-medium);
            color: white;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 10px 0;
            }

            .sidebar-menu {
                display: flex;
                overflow-x: auto;
                padding: 10px;
            }

            .sidebar-menu a {
                white-space: nowrap;
                margin-right: 10px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Clea Admin</h2>
                <p>Painel Administrativo</p>
            </div>
            <div class="sidebar-menu">
                <a href="dashboard.php" class="active">Dashboard</a>
                <a href="users.php">Usuários</a>
                <a href="vendors.php">Fornecedores</a>
                <a href="weddings.php">Casamentos</a>
                <a href="contracts.php">Contratos</a>
                <a href="financial.php">Financeiro</a>
                <a href="messages.php">Mensagens</a>
                <a href="settings.php">Configurações</a>
            </div>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>Dashboard</h1>
                <div class="user-info">
                    <span>Olá, <?= htmlspecialchars($_SESSION['user_name']) ?>!</span>
                    <a href="../auth.php" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sair</a>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>TOTAL CLIENTES</h3>
                    <div class="number"><?= $stats['users']['client'] ?? 0 ?></div>
                    <div class="label">Casais cadastrados</div>
                </div>

                <div class="stat-card">
                    <h3>FORNECEDORES ATIVOS</h3>
                    <div class="number"><?= $stats['users']['vendor'] ?? 0 ?></div>
                    <div class="label">Parceiros aprovados</div>
                </div>

                <div class="stat-card">
                    <h3>CASAMENTOS</h3>
                    <div class="number"><?= $stats['weddings'] ?></div>
                    <div class="label">Total de eventos</div>
                </div>

                <div class="stat-card">
                    <h3>CONTRATOS ATIVOS</h3>
                    <div class="number"><?= $stats['active_contracts'] ?></div>
                    <div class="label">Em andamento</div>
                </div>
            </div>

            <div class="content-section">
                <h2>Ações Rápidas</h2>
                <div class="quick-actions">
                    <a href="users.php?action=new" class="action-btn">Cadastrar Usuário</a>
                    <a href="vendors.php?pending=1" class="action-btn">
                        Aprovar Fornecedores
                        <?php if ($stats['pending_vendors'] > 0): ?>
                            (<?= $stats['pending_vendors'] ?>)
                        <?php endif; ?>
                    </a>
                    <a href="weddings.php?action=new" class="action-btn">Novo Casamento</a>
                    <a href="financial.php" class="action-btn">Relatório Financeiro</a>
                    <a href="messages.php" class="action-btn">Ver Mensagens</a>
                    <a href="../index.php" class="action-btn">Ver Site Público</a>
                </div>
            </div>
        </div>
    </div>

    <form id="logout-form" action="../auth.php" method="POST" style="display: none;">
        <input type="hidden" name="action" value="logout">
    </form>
</body>
</html>