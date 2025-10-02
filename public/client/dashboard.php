<?php
session_start();
require_once('../../config/config.php');
require_once('../auth.php');

// Check if user is logged in and is client
Auth::checkRole(['client']);

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

// Get client wedding
$stmt = $db->prepare("SELECT * FROM weddings WHERE client_user_id = ? ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$_SESSION['user_id']]);
$wedding = $stmt->fetch();

// Get contracts for this wedding
$contracts = [];
$stats = ['total_value' => 0, 'paid_value' => 0, 'pending_contracts' => 0];

if ($wedding) {
    $stmt = $db->prepare("
        SELECT c.*, vp.business_name, vp.category, u.name as vendor_name
        FROM contracts c
        JOIN users u ON c.vendor_user_id = u.id
        LEFT JOIN vendor_profiles vp ON u.id = vp.user_id
        WHERE c.wedding_id = ?
        ORDER BY c.created_at ASC
    ");
    $stmt->execute([$wedding['id']]);
    $contracts = $stmt->fetchAll();

    // Calculate statistics
    foreach ($contracts as $contract) {
        $stats['total_value'] += $contract['total_value'];
        if ($contract['status'] === 'pending_signature') {
            $stats['pending_contracts']++;
        }
    }

    // Calculate days until wedding
    $wedding_date = new DateTime($wedding['wedding_date']);
    $today = new DateTime();
    $days_until = $today->diff($wedding_date)->days;
    if ($wedding_date < $today) {
        $days_until = 0;
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Dashboard Cliente - Clea Casamentos</title>
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

        .countdown-card {
            background: linear-gradient(135deg, var(--primary-medium), var(--primary-dark));
            color: white;
            border-radius: 12px;
            padding: 32px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 8px 24px rgba(242, 171, 177, 0.3);
        }

        .countdown-card h2 {
            font-family: 'Lora', serif;
            font-size: 24px;
            margin-bottom: 16px;
        }

        .countdown-number {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .countdown-label {
            font-size: 16px;
            opacity: 0.9;
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

        .wedding-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .info-item {
            text-align: center;
        }

        .info-item h4 {
            color: var(--text-secondary);
            font-size: 14px;
            margin-bottom: 8px;
        }

        .info-item p {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-dark);
        }

        .contracts-list {
            display: grid;
            gap: 16px;
        }

        .contract-item {
            background: var(--primary-light);
            border-radius: 8px;
            padding: 20px;
            border: 1px solid rgba(242, 171, 177, 0.3);
        }

        .contract-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .contract-vendor {
            font-weight: 600;
            color: var(--primary-dark);
        }

        .contract-category {
            background-color: var(--primary-medium);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .contract-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-dark);
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

        .welcome-message {
            background: linear-gradient(135deg, var(--primary-light), var(--card-bg));
            border: 2px solid var(--primary-medium);
            border-radius: 12px;
            padding: 32px;
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-message h2 {
            font-family: 'Lora', serif;
            color: var(--primary-dark);
            margin-bottom: 16px;
        }

        .welcome-message p {
            color: var(--text-secondary);
            margin-bottom: 20px;
        }

        .cta-btn {
            background: linear-gradient(135deg, var(--primary-medium), var(--primary-dark));
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .cta-btn:hover {
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
                <h2>Meu Casamento</h2>
                <p>Painel do Cliente</p>
            </div>
            <div class="sidebar-menu">
                <a href="dashboard.php" class="active">Dashboard</a>
                <a href="wedding.php">Meu Evento</a>
                <a href="vendors.php">Fornecedores</a>
                <a href="contracts.php">Contratos</a>
                <a href="financial.php">Financeiro</a>
                <a href="guests.php">Convidados</a>
                <a href="messages.php">Mensagens</a>
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

            <?php if (!$wedding): ?>
            <div class="welcome-message">
                <h2>Bem-vindo à Clea Casamentos!</h2>
                <p>Ainda não temos informações sobre seu casamento. Vamos começar a planejar seu grande dia?</p>
                <a href="wedding.php?action=create" class="cta-btn">Cadastrar Meu Casamento</a>
            </div>
            <?php else: ?>

            <div class="countdown-card">
                <h2><?= htmlspecialchars($wedding['partner_name'] ? $_SESSION['user_name'] . ' & ' . $wedding['partner_name'] : 'Seu Grande Dia') ?></h2>
                <div class="countdown-number"><?= $days_until ?></div>
                <div class="countdown-label">dias para o casamento</div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3>VALOR TOTAL</h3>
                    <div class="number">R$ <?= number_format($stats['total_value'], 0, ',', '.') ?></div>
                    <div class="label">Investimento total</div>
                </div>

                <div class="stat-card">
                    <h3>FORNECEDORES</h3>
                    <div class="number"><?= count($contracts) ?></div>
                    <div class="label">Contratados</div>
                </div>

                <div class="stat-card">
                    <h3>CONTRATOS PENDENTES</h3>
                    <div class="number"><?= $stats['pending_contracts'] ?></div>
                    <div class="label">Aguardando assinatura</div>
                </div>

                <div class="stat-card">
                    <h3>CONVIDADOS</h3>
                    <div class="number"><?= $wedding['estimated_guests'] ?? 0 ?></div>
                    <div class="label">Estimativa atual</div>
                </div>
            </div>

            <div class="content-section">
                <h2>Informações do Evento</h2>
                <div class="wedding-info">
                    <div class="info-item">
                        <h4>DATA</h4>
                        <p><?= date('d/m/Y', strtotime($wedding['wedding_date'])) ?></p>
                    </div>
                    <div class="info-item">
                        <h4>HORÁRIO</h4>
                        <p><?= date('H:i', strtotime($wedding['wedding_date'])) ?></p>
                    </div>
                    <div class="info-item">
                        <h4>STATUS</h4>
                        <p><?= ucfirst($wedding['status']) ?></p>
                    </div>
                    <div class="info-item">
                        <h4>LOCAL</h4>
                        <p><?= htmlspecialchars($wedding['location_details'] ?? 'A definir') ?></p>
                    </div>
                </div>
            </div>

            <?php if (!empty($contracts)): ?>
            <div class="content-section">
                <h2>Fornecedores Contratados</h2>
                <div class="contracts-list">
                    <?php foreach ($contracts as $contract): ?>
                    <div class="contract-item">
                        <div class="contract-header">
                            <div>
                                <div class="contract-vendor"><?= htmlspecialchars($contract['business_name'] ?? $contract['vendor_name']) ?></div>
                                <span class="contract-category"><?= ucfirst($contract['category'] ?? 'Serviço') ?></span>
                            </div>
                            <span class="status-badge status-<?= $contract['status'] ?>">
                                <?= ucfirst(str_replace('_', ' ', $contract['status'])) ?>
                            </span>
                        </div>
                        <div class="contract-value">R$ <?= number_format($contract['total_value'], 2, ',', '.') ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>

    <form id="logout-form" action="../auth.php" method="POST" style="display: none;">
        <input type="hidden" name="action" value="logout">
    </form>
</body>
</html>