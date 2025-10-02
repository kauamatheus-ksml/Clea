<?php
session_start();
require_once('../../config/config.php');
require_once('../auth.php');

// Check if user is logged in and is vendor
Auth::checkRole(['vendor']);

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

// Get vendor profile
$stmt = $db->prepare("SELECT * FROM vendor_profiles WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$vendor_profile = $stmt->fetch();

// Get vendor contracts
$stmt = $db->prepare("
    SELECT c.*, w.wedding_date, w.partner_name, u.name as client_name
    FROM contracts c
    JOIN weddings w ON c.wedding_id = w.id
    JOIN users u ON w.client_user_id = u.id
    WHERE c.vendor_user_id = ?
    ORDER BY w.wedding_date ASC
");
$stmt->execute([$_SESSION['user_id']]);
$contracts = $stmt->fetchAll();

// Get statistics
$stats = [];
$stats['total_contracts'] = count($contracts);
$stats['active_contracts'] = count(array_filter($contracts, fn($c) => $c['status'] === 'active'));
$stats['pending_contracts'] = count(array_filter($contracts, fn($c) => $c['status'] === 'pending_signature'));

// Calculate total revenue
$total_revenue = 0;
foreach ($contracts as $contract) {
    if ($contract['status'] === 'active' || $contract['status'] === 'completed') {
        $total_revenue += $contract['total_value'] * (1 - $contract['commission_rate']);
    }
}
$stats['total_revenue'] = $total_revenue;

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Dashboard Fornecedor - Clea Casamentos</title>
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
            font-size: 20px;
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
            margin-bottom: 20px;
        }

        .content-section h2 {
            font-family: 'Lora', serif;
            color: var(--text-primary);
            margin-bottom: 20px;
        }

        .contracts-table {
            width: 100%;
            border-collapse: collapse;
        }

        .contracts-table th,
        .contracts-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid rgba(242, 171, 177, 0.2);
        }

        .contracts-table th {
            background-color: var(--primary-light);
            font-weight: 600;
            color: var(--text-primary);
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background-color: #cce5ff;
            color: #004085;
        }

        .approval-notice {
            background: linear-gradient(135deg, #ffeaa7, #fdcb6e);
            border: 1px solid #e17055;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        .approval-notice h3 {
            color: #2d3436;
            margin-bottom: 8px;
        }

        .approval-notice p {
            color: #636e72;
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
                <h2><?= $vendor_profile ? htmlspecialchars($vendor_profile['business_name']) : 'Meu Negócio' ?></h2>
                <p>Painel do Fornecedor</p>
            </div>
            <div class="sidebar-menu">
                <a href="dashboard.php" class="active">Dashboard</a>
                <a href="profile.php">Meu Perfil</a>
                <a href="contracts.php">Contratos</a>
                <a href="financial.php">Financeiro</a>
                <a href="messages.php">Mensagens</a>
                <a href="calendar.php">Calendário</a>
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

            <?php if (!$vendor_profile || !$vendor_profile['is_approved']): ?>
            <div class="approval-notice">
                <h3>Perfil em Análise</h3>
                <p>Seu perfil está sendo analisado pela equipe Clea. Você receberá um email quando for aprovado.</p>
            </div>
            <?php endif; ?>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>CONTRATOS TOTAIS</h3>
                    <div class="number"><?= $stats['total_contracts'] ?></div>
                    <div class="label">Todos os contratos</div>
                </div>

                <div class="stat-card">
                    <h3>CONTRATOS ATIVOS</h3>
                    <div class="number"><?= $stats['active_contracts'] ?></div>
                    <div class="label">Em andamento</div>
                </div>

                <div class="stat-card">
                    <h3>PENDENTES</h3>
                    <div class="number"><?= $stats['pending_contracts'] ?></div>
                    <div class="label">Aguardando assinatura</div>
                </div>

                <div class="stat-card">
                    <h3>RECEITA TOTAL</h3>
                    <div class="number">R$ <?= number_format($stats['total_revenue'], 2, ',', '.') ?></div>
                    <div class="label">Valor a receber</div>
                </div>
            </div>

            <div class="content-section">
                <h2>Próximos Eventos</h2>
                <?php if (empty($contracts)): ?>
                    <p>Nenhum contrato encontrado.</p>
                <?php else: ?>
                    <table class="contracts-table">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Data do Evento</th>
                                <th>Valor</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($contracts, 0, 5) as $contract): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($contract['client_name']) ?></strong><br>
                                    <small><?= htmlspecialchars($contract['partner_name'] ?? 'N/A') ?></small>
                                </td>
                                <td><?= date('d/m/Y', strtotime($contract['wedding_date'])) ?></td>
                                <td>R$ <?= number_format($contract['total_value'], 2, ',', '.') ?></td>
                                <td>
                                    <span class="status-badge status-<?= $contract['status'] ?>">
                                        <?= ucfirst(str_replace('_', ' ', $contract['status'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="contracts.php?id=<?= $contract['id'] ?>">Ver detalhes</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php if (count($contracts) > 5): ?>
                        <p style="margin-top: 16px;">
                            <a href="contracts.php">Ver todos os contratos (<?= count($contracts) ?>)</a>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <form id="logout-form" action="../auth.php" method="POST" style="display: none;">
        <input type="hidden" name="action" value="logout">
    </form>
</body>
</html>