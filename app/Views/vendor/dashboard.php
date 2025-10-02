<?php
require_once ROOT_PATH . '/app/core/Auth.php';
use App\Core\Auth;

if (!Auth::isLoggedIn() || Auth::getUserRole() !== 'vendor') {
    header('Location: /public/login.php');
    exit();
}

$user = Auth::getUser();
$vendorProfile = $data['vendorProfile'] ?? null;
$upcomingEvents = $data['upcomingEvents'] ?? [];
$stats = $data['stats'] ?? [];
$recentMessages = $data['recentMessages'] ?? [];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Fornecedor - Clea Casamentos</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --white: #ffffff;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-900: #111827;
            --primary: #f2abb1;
            --primary-dark: #e89aa1;
            --primary-light: #f8d7da;
            --secondary: #652929;
            --success: #10b981;
            --warning: #f59e0b;
            --info: #3b82f6;
            --info-light: #dbeafe;
            --info-dark: #1e40af;
            --vendor-primary: #2563eb;
            --vendor-secondary: #1e40af;
            --vendor-light: #dbeafe;
            --vendor-success: #059669;
            --vendor-warning: #d97706;
        }

        .vendor-card {
            background: linear-gradient(135deg, var(--vendor-primary), var(--vendor-secondary));
        }

        .vendor-badge {
            background: var(--vendor-light);
            color: var(--vendor-primary);
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .revenue-card {
            background: linear-gradient(135deg, var(--vendor-success), #047857);
            color: white;
        }

        .pending-card {
            background: linear-gradient(135deg, var(--vendor-warning), #b45309);
            color: white;
        }

        .event-timeline {
            position: relative;
            margin-left: 20px;
        }

        .event-timeline::before {
            content: '';
            position: absolute;
            left: -10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--vendor-light);
        }

        .timeline-item {
            position: relative;
            padding: 15px 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -15px;
            top: 20px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--vendor-primary);
            border: 2px solid var(--white);
        }

        .timeline-date {
            font-size: 12px;
            color: var(--vendor-primary);
            font-weight: 600;
            margin-bottom: 5px;
        }

        .timeline-content h4 {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: var(--gray-900);
        }

        .timeline-details {
            font-size: 13px;
            color: var(--gray-600);
            margin-bottom: 8px;
        }

        .timeline-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
        }

        .client-info {
            color: var(--gray-700);
        }

        .event-value {
            font-weight: 600;
            color: var(--vendor-success);
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .quick-action {
            padding: 15px;
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--gray-200);
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .quick-action:hover {
            border-color: var(--vendor-primary);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
        }

        .quick-action i {
            font-size: 24px;
            color: var(--vendor-primary);
            margin-bottom: 8px;
        }

        .quick-action-title {
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 4px;
        }

        .quick-action-desc {
            font-size: 12px;
            color: var(--gray-600);
        }

        .performance-chart {
            height: 300px;
            background: var(--white);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .messages-preview {
            max-height: 300px;
            overflow-y: auto;
        }

        .message-item {
            display: flex;
            align-items: center;
            padding: 12px;
            border-bottom: 1px solid var(--gray-100);
            cursor: pointer;
            transition: background 0.2s;
        }

        .message-item:hover {
            background: var(--gray-50);
        }

        .message-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--vendor-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: var(--vendor-primary);
            font-weight: 600;
        }

        .message-content {
            flex: 1;
        }

        .message-sender {
            font-weight: 600;
            font-size: 14px;
            color: var(--gray-900);
            margin-bottom: 2px;
        }

        .message-preview {
            font-size: 13px;
            color: var(--gray-600);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .message-time {
            font-size: 12px;
            color: var(--gray-500);
        }

        /* Dashboard grid styles */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid var(--gray-200);
            background: var(--gray-50);
        }

        .card-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-content {
            padding: 20px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .btn-outline {
            border: 1px solid var(--gray-300);
            color: var(--gray-700);
            background: var(--white);
        }

        .btn-outline:hover {
            background: var(--gray-50);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--gray-200);
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .stat-content h3 {
            margin: 0 0 5px 0;
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .stat-content p {
            margin: 0;
            font-size: 14px;
            color: var(--gray-600);
        }

        .sidebar-divider {
            height: 1px;
            background: var(--gray-200);
            margin: 15px 0;
        }

        /* Base dashboard styles */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            background: var(--gray-50);
        }

        .sidebar {
            width: 280px;
            background: var(--white);
            border-right: 1px solid var(--gray-200);
            flex-shrink: 0;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--gray-200);
            text-align: center;
        }

        .sidebar-header h1 {
            margin: 0 0 10px 0;
            font-size: 24px;
            color: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: var(--gray-700);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            border-right: 3px solid transparent;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: var(--primary-light);
            color: var(--primary);
            border-right-color: var(--primary);
        }

        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .content-header {
            margin-bottom: 30px;
        }

        .content-header h1 {
            margin: 0 0 8px 0;
            font-size: 28px;
            font-weight: 700;
            color: var(--gray-900);
        }

        .content-header p {
            margin: 0;
            color: var(--gray-600);
            font-size: 16px;
        }

        .vendor-badge {
            background: var(--vendor-light);
            color: var(--vendor-primary);
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--gray-500);
        }

        .empty-state i {
            font-size: 48px;
            color: var(--gray-300);
            margin-bottom: 15px;
        }

        .empty-state h3 {
            margin: 0 0 8px 0;
            color: var(--gray-700);
        }

        .empty-state p {
            margin: 0;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1><i class="fas fa-store"></i> Clea</h1>
                <div class="vendor-badge"><?= ucfirst($vendorProfile['category'] ?? 'Fornecedor') ?></div>
            </div>
            <nav class="sidebar-nav">
                <a href="app.php?url=/vendor/dashboard" class="active"><i class="fas fa-chart-bar"></i> Dashboard</a>
                <a href="app.php?url=/vendor/events"><i class="fas fa-calendar-alt"></i> Meus Eventos</a>
                <a href="app.php?url=/vendor/financial"><i class="fas fa-dollar-sign"></i> Financeiro</a>
                <a href="app.php?url=/vendor/messages"><i class="fas fa-comments"></i> Mensagens</a>
                <a href="app.php?url=/vendor/profile"><i class="fas fa-user-edit"></i> Meu Perfil</a>
                <div class="sidebar-divider"></div>
                <a href="auth.php?action=logout"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <h1>Dashboard</h1>
                <p>Bem-vindo de volta, <strong><?= htmlspecialchars($vendorProfile['business_name'] ?? $user['name']) ?></strong>!</p>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <div class="quick-action" onclick="location.href='app.php?url=/vendor/events'">
                    <i class="fas fa-calendar-check"></i>
                    <div class="quick-action-title">Ver Eventos</div>
                    <div class="quick-action-desc">Gerencie seus casamentos</div>
                </div>
                <div class="quick-action" onclick="location.href='app.php?url=/vendor/messages'">
                    <i class="fas fa-envelope"></i>
                    <div class="quick-action-title">Mensagens</div>
                    <div class="quick-action-desc">Converse com clientes</div>
                </div>
                <div class="quick-action" onclick="location.href='app.php?url=/vendor/financial'">
                    <i class="fas fa-chart-line"></i>
                    <div class="quick-action-title">Relatórios</div>
                    <div class="quick-action-desc">Acompanhe receitas</div>
                </div>
                <div class="quick-action" onclick="location.href='app.php?url=/vendor/profile'">
                    <i class="fas fa-edit"></i>
                    <div class="quick-action-title">Editar Perfil</div>
                    <div class="quick-action-desc">Atualize informações</div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card vendor-card">
                    <div class="stat-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= $stats['total_contracts'] ?? 0 ?></h3>
                        <p>Contratos Totais</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: var(--success);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= $stats['active_contracts'] ?? 0 ?></h3>
                        <p>Contratos Ativos</p>
                    </div>
                </div>

                <div class="stat-card revenue-card">
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-content">
                        <h3>R$ <?= number_format($stats['total_revenue'] ?? 0, 2, ',', '.') ?></h3>
                        <p>Receita Total</p>
                    </div>
                </div>

                <div class="stat-card pending-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <h3>R$ <?= number_format($stats['pending_revenue'] ?? 0, 2, ',', '.') ?></h3>
                        <p>Receita Pendente</p>
                    </div>
                </div>
            </div>

            <div class="dashboard-grid">
                <!-- Próximos Eventos -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-calendar-alt"></i> Próximos Eventos</h2>
                        <a href="app.php?url=/vendor/events" class="btn btn-outline">Ver Todos</a>
                    </div>
                    <div class="card-content">
                        <?php if (empty($upcomingEvents)): ?>
                            <div class="empty-state">
                                <i class="fas fa-calendar-times"></i>
                                <h3>Nenhum evento próximo</h3>
                                <p>Seus próximos eventos aparecerão aqui</p>
                            </div>
                        <?php else: ?>
                            <div class="event-timeline">
                                <?php foreach (array_slice($upcomingEvents, 0, 5) as $event): ?>
                                <div class="timeline-item">
                                    <div class="timeline-date">
                                        <?= date('d/m/Y', strtotime($event['wedding_date'])) ?>
                                    </div>
                                    <div class="timeline-content">
                                        <h4><?= htmlspecialchars($event['client_name']) ?> & <?= htmlspecialchars($event['partner_name']) ?></h4>
                                        <div class="timeline-details">
                                            <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($event['location_details']) ?>
                                        </div>
                                        <div class="timeline-meta">
                                            <span class="client-info">
                                                <i class="fas fa-users"></i> <?= $event['estimated_guests'] ?? 'N/A' ?> convidados
                                            </span>
                                            <span class="event-value">
                                                R$ <?= number_format($event['total_value'] * (1 - $event['commission_rate']), 2, ',', '.') ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Mensagens Recentes -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-comments"></i> Mensagens Recentes</h2>
                        <a href="app.php?url=/vendor/messages" class="btn btn-outline">Ver Todas</a>
                    </div>
                    <div class="card-content">
                        <?php if (empty($recentMessages)): ?>
                            <div class="empty-state">
                                <i class="fas fa-comment-slash"></i>
                                <h3>Nenhuma mensagem</h3>
                                <p>Suas conversas aparecerão aqui</p>
                            </div>
                        <?php else: ?>
                            <div class="messages-preview">
                                <?php foreach (array_slice($recentMessages, 0, 5) as $message): ?>
                                <div class="message-item" onclick="location.href='app.php?url=/vendor/messages'">
                                    <div class="message-avatar">
                                        <?= strtoupper(substr($message['other_user_name'], 0, 2)) ?>
                                    </div>
                                    <div class="message-content">
                                        <div class="message-sender"><?= htmlspecialchars($message['other_user_name']) ?></div>
                                        <div class="message-preview"><?= htmlspecialchars(substr($message['message_content'], 0, 60)) ?>...</div>
                                    </div>
                                    <div class="message-time">
                                        <?= date('H:i', strtotime($message['sent_at'])) ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Performance Chart -->
            <div class="performance-chart">
                <h3 style="margin-bottom: 20px;"><i class="fas fa-chart-area"></i> Performance Mensal</h3>
                <canvas id="performanceChart"></canvas>
            </div>
        </main>
    </div>

    <script>
        // Performance Chart
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                datasets: [{
                    label: 'Receita Mensal (R$)',
                    data: [
                        <?= $stats['monthly_revenue'][5]['revenue'] ?? 0 ?>,
                        <?= $stats['monthly_revenue'][4]['revenue'] ?? 0 ?>,
                        <?= $stats['monthly_revenue'][3]['revenue'] ?? 0 ?>,
                        <?= $stats['monthly_revenue'][2]['revenue'] ?? 0 ?>,
                        <?= $stats['monthly_revenue'][1]['revenue'] ?? 0 ?>,
                        <?= $stats['monthly_revenue'][0]['revenue'] ?? 0 ?>
                    ],
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR');
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Auto-refresh dashboard data every 5 minutes
        setInterval(() => {
            location.reload();
        }, 300000);
    </script>
</body>
</html>