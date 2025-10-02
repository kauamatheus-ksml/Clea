<?php
require_once ROOT_PATH . '/app/core/Auth.php';
use App\Core\Auth;

if (!Auth::isLoggedIn() || Auth::getUserRole() !== 'vendor') {
    header('Location: /public/login.php');
    exit();
}

$user = Auth::getUser();
$financialData = $data['financialData'] ?? [];
$vendorProfile = $data['vendorProfile'] ?? null;
$upcomingPayments = $financialData['upcoming_payments'] ?? [];
$paymentHistory = $financialData['payment_history'] ?? [];
$monthlyRevenue = $financialData['monthly_revenue'] ?? [];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financeiro - Fornecedor - Clea Casamentos</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .financial-tabs {
            display: flex;
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--gray-200);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .financial-tab {
            flex: 1;
            padding: 15px 20px;
            text-align: center;
            cursor: pointer;
            border-right: 1px solid var(--gray-200);
            transition: all 0.2s;
            background: var(--gray-50);
            color: var(--gray-600);
        }

        .financial-tab:last-child {
            border-right: none;
        }

        .financial-tab.active {
            background: var(--primary);
            color: white;
        }

        .financial-tab:hover:not(.active) {
            background: var(--gray-100);
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .payment-timeline {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--gray-200);
        }

        .timeline-header {
            padding: 20px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .timeline-body {
            max-height: 500px;
            overflow-y: auto;
        }

        .payment-item {
            display: flex;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid var(--gray-100);
            transition: background 0.2s;
        }

        .payment-item:hover {
            background: var(--gray-50);
        }

        .payment-item:last-child {
            border-bottom: none;
        }

        .payment-date {
            min-width: 120px;
        }

        .payment-date-day {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
        }

        .payment-date-month {
            font-size: 12px;
            color: var(--gray-600);
            text-transform: uppercase;
        }

        .payment-info {
            flex: 1;
            margin-left: 20px;
        }

        .payment-client {
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 4px;
        }

        .payment-description {
            font-size: 14px;
            color: var(--gray-600);
            margin-bottom: 5px;
        }

        .payment-commission {
            font-size: 12px;
            color: var(--gray-500);
        }

        .payment-amount {
            text-align: right;
            min-width: 100px;
        }

        .payment-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--success);
        }

        .payment-gross {
            font-size: 12px;
            color: var(--gray-500);
        }

        .financial-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .summary-card {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--gray-200);
            padding: 20px;
            text-align: center;
        }

        .summary-card.primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
        }

        .summary-card.success {
            background: linear-gradient(135deg, var(--success), #047857);
            color: white;
            border: none;
        }

        .summary-card.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            border: none;
        }

        .summary-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .summary-label {
            font-size: 14px;
            opacity: 0.9;
        }

        .chart-container {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--gray-200);
            padding: 20px;
            margin-bottom: 30px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-900);
        }

        .chart-period {
            font-size: 14px;
            color: var(--gray-600);
        }

        .revenue-chart {
            height: 300px;
        }

        .history-table {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--gray-200);
            overflow: hidden;
        }

        .table-header {
            background: var(--gray-50);
            padding: 15px 20px;
            border-bottom: 1px solid var(--gray-200);
            font-weight: 600;
            color: var(--gray-900);
        }

        .table-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 120px;
            gap: 20px;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid var(--gray-100);
        }

        .table-row:hover {
            background: var(--gray-50);
        }

        .table-row:last-child {
            border-bottom: none;
        }

        .table-cell {
            font-size: 14px;
        }

        .table-date {
            color: var(--gray-600);
        }

        .table-client {
            font-weight: 500;
            color: var(--gray-900);
        }

        .table-amount {
            font-weight: 600;
            color: var(--success);
            text-align: right;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray-500);
        }

        .empty-state i {
            font-size: 64px;
            color: var(--gray-300);
            margin-bottom: 20px;
        }

        .filter-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 8px 12px;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            font-size: 14px;
            min-width: 120px;
        }

        .export-btn {
            padding: 8px 16px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .export-btn:hover {
            background: var(--primary-dark);
        }

        .commission-info {
            background: var(--info-light);
            border: 1px solid var(--info);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .commission-title {
            font-weight: 600;
            color: var(--info-dark);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .commission-text {
            font-size: 14px;
            color: var(--gray-700);
            line-height: 1.5;
        }

        @media (max-width: 768px) {
            .financial-tabs {
                flex-direction: column;
            }

            .financial-tab {
                border-right: none;
                border-bottom: 1px solid var(--gray-200);
            }

            .financial-tab:last-child {
                border-bottom: none;
            }

            .financial-summary {
                grid-template-columns: 1fr;
            }

            .table-row {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .payment-item {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }

            .payment-info {
                margin: 15px 0;
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
                <div class="vendor-badge" style="background: #dbeafe; color: #2563eb; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                    <?= ucfirst($vendorProfile['category'] ?? 'Fornecedor') ?>
                </div>
            </div>
            <nav class="sidebar-nav">
                <a href="app.php?url=/vendor/dashboard"><i class="fas fa-chart-bar"></i> Dashboard</a>
                <a href="app.php?url=/vendor/events"><i class="fas fa-calendar-alt"></i> Meus Eventos</a>
                <a href="app.php?url=/vendor/financial" class="active"><i class="fas fa-dollar-sign"></i> Financeiro</a>
                <a href="app.php?url=/vendor/messages"><i class="fas fa-comments"></i> Mensagens</a>
                <a href="app.php?url=/vendor/profile"><i class="fas fa-user-edit"></i> Meu Perfil</a>
                <div class="sidebar-divider"></div>
                <a href="auth.php?action=logout"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <h1><i class="fas fa-dollar-sign"></i> Financeiro</h1>
                <p>Gerencie suas receitas e comissões</p>
            </div>

            <!-- Commission Info -->
            <div class="commission-info">
                <div class="commission-title">
                    <i class="fas fa-info-circle"></i>
                    Como funcionam as comissões da Clea
                </div>
                <div class="commission-text">
                    A Clea cobra uma comissão de <strong>20%</strong> sobre o valor total dos contratos.
                    Você recebe <strong>80%</strong> do valor após a conclusão do evento.
                    Os valores mostrados abaixo já são líquidos (descontada a comissão).
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="financial-summary">
                <div class="summary-card primary">
                    <div class="summary-value">
                        R$ <?= number_format(array_sum(array_column($upcomingPayments, 'net_amount')), 2, ',', '.') ?>
                    </div>
                    <div class="summary-label">Receita Pendente</div>
                </div>

                <div class="summary-card success">
                    <div class="summary-value">
                        R$ <?= number_format(array_sum(array_column($paymentHistory, 'net_amount')), 2, ',', '.') ?>
                    </div>
                    <div class="summary-label">Receita Recebida</div>
                </div>

                <div class="summary-card warning">
                    <div class="summary-value">
                        <?= count($upcomingPayments) ?>
                    </div>
                    <div class="summary-label">Eventos Pendentes</div>
                </div>

                <div class="summary-card">
                    <div class="summary-value" style="color: var(--gray-900);">
                        R$ <?= number_format(array_sum(array_column($monthlyRevenue, 'revenue')), 2, ',', '.') ?>
                    </div>
                    <div class="summary-label" style="color: var(--gray-600);">Receita Total</div>
                </div>
            </div>

            <!-- Revenue Chart -->
            <div class="chart-container">
                <div class="chart-header">
                    <div class="chart-title">Evolução da Receita</div>
                    <div class="chart-period">Últimos 12 meses</div>
                </div>
                <div class="revenue-chart">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Financial Tabs -->
            <div class="financial-tabs">
                <div class="financial-tab active" onclick="switchTab('upcoming')">
                    <i class="fas fa-clock"></i> Pagamentos Pendentes
                </div>
                <div class="financial-tab" onclick="switchTab('history')">
                    <i class="fas fa-history"></i> Histórico de Pagamentos
                </div>
            </div>

            <!-- Upcoming Payments Tab -->
            <div id="upcoming-tab" class="tab-content active">
                <div class="filter-bar">
                    <select class="filter-select" id="upcomingFilter">
                        <option value="">Todos os Eventos</option>
                        <option value="this-month">Este Mês</option>
                        <option value="next-month">Próximo Mês</option>
                        <option value="next-3-months">Próximos 3 Meses</option>
                    </select>
                    <button class="export-btn" onclick="exportUpcomingPayments()">
                        <i class="fas fa-download"></i> Exportar
                    </button>
                </div>

                <?php if (empty($upcomingPayments)): ?>
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <h3>Nenhum pagamento pendente</h3>
                        <p>Você não possui eventos com pagamentos pendentes no momento.</p>
                    </div>
                <?php else: ?>
                    <div class="payment-timeline">
                        <div class="timeline-header">
                            <h3>Cronograma de Recebimentos</h3>
                        </div>
                        <div class="timeline-body">
                            <?php foreach ($upcomingPayments as $payment): ?>
                            <div class="payment-item" data-date="<?= $payment['wedding_date'] ?>">
                                <div class="payment-date">
                                    <div class="payment-date-day">
                                        <?= date('d', strtotime($payment['wedding_date'])) ?>
                                    </div>
                                    <div class="payment-date-month">
                                        <?= strftime('%b %Y', strtotime($payment['wedding_date'])) ?>
                                    </div>
                                </div>
                                <div class="payment-info">
                                    <div class="payment-client">
                                        <?= htmlspecialchars($payment['client_name']) ?>
                                    </div>
                                    <div class="payment-description">
                                        <?= htmlspecialchars($payment['service_description']) ?>
                                    </div>
                                    <div class="payment-commission">
                                        Valor bruto: R$ <?= number_format($payment['total_value'], 2, ',', '.') ?>
                                        | Comissão: <?= ($payment['commission_rate'] * 100) ?>%
                                    </div>
                                </div>
                                <div class="payment-amount">
                                    <div class="payment-value">
                                        R$ <?= number_format($payment['net_amount'], 2, ',', '.') ?>
                                    </div>
                                    <div class="payment-gross">
                                        Líquido
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Payment History Tab -->
            <div id="history-tab" class="tab-content">
                <div class="filter-bar">
                    <select class="filter-select" id="historyFilter">
                        <option value="">Todo o Período</option>
                        <option value="this-year">Este Ano</option>
                        <option value="last-year">Ano Passado</option>
                        <option value="last-6-months">Últimos 6 Meses</option>
                    </select>
                    <button class="export-btn" onclick="exportPaymentHistory()">
                        <i class="fas fa-download"></i> Exportar
                    </button>
                </div>

                <?php if (empty($paymentHistory)): ?>
                    <div class="empty-state">
                        <i class="fas fa-receipt"></i>
                        <h3>Nenhum pagamento realizado</h3>
                        <p>O histórico de pagamentos aparecerá aqui após os eventos serem concluídos.</p>
                    </div>
                <?php else: ?>
                    <div class="history-table">
                        <div class="table-header">
                            <div class="table-row">
                                <div class="table-cell">Data do Evento</div>
                                <div class="table-cell">Cliente</div>
                                <div class="table-cell">Serviço</div>
                                <div class="table-cell" style="text-align: right;">Valor Recebido</div>
                            </div>
                        </div>
                        <div class="table-body">
                            <?php foreach ($paymentHistory as $payment): ?>
                            <div class="table-row">
                                <div class="table-cell table-date">
                                    <?= date('d/m/Y', strtotime($payment['wedding_date'])) ?>
                                </div>
                                <div class="table-cell table-client">
                                    <?= htmlspecialchars($payment['client_name']) ?>
                                </div>
                                <div class="table-cell">
                                    <?= htmlspecialchars($payment['service_description']) ?>
                                </div>
                                <div class="table-cell table-amount">
                                    R$ <?= number_format($payment['net_amount'], 2, ',', '.') ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        // Tab functionality
        function switchTab(tabName) {
            // Update tab buttons
            document.querySelectorAll('.financial-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            event.target.classList.add('active');

            // Update tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(tabName + '-tab').classList.add('active');
        }

        // Revenue Chart
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const monthlyData = <?= json_encode(array_reverse($monthlyRevenue)) ?>;

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyData.map(item => {
                    const date = new Date(item.year, item.month - 1);
                    return date.toLocaleDateString('pt-BR', { month: 'short', year: 'numeric' });
                }),
                datasets: [{
                    label: 'Receita Mensal (R$)',
                    data: monthlyData.map(item => item.revenue),
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
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'R$ ' + context.parsed.y.toLocaleString('pt-BR', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }
                        }
                    }
                }
            }
        });

        // Filter functionality
        document.getElementById('upcomingFilter').addEventListener('change', function() {
            const filter = this.value;
            const items = document.querySelectorAll('#upcoming-tab .payment-item');
            const now = new Date();

            items.forEach(item => {
                const eventDate = new Date(item.dataset.date);
                let show = true;

                switch(filter) {
                    case 'this-month':
                        show = eventDate.getMonth() === now.getMonth() &&
                               eventDate.getFullYear() === now.getFullYear();
                        break;
                    case 'next-month':
                        const nextMonth = new Date(now.getFullYear(), now.getMonth() + 1);
                        show = eventDate.getMonth() === nextMonth.getMonth() &&
                               eventDate.getFullYear() === nextMonth.getFullYear();
                        break;
                    case 'next-3-months':
                        const threeMonthsLater = new Date();
                        threeMonthsLater.setMonth(threeMonthsLater.getMonth() + 3);
                        show = eventDate >= now && eventDate <= threeMonthsLater;
                        break;
                }

                item.style.display = show ? 'flex' : 'none';
            });
        });

        // Export functions
        function exportUpcomingPayments() {
            alert('Funcionalidade de exportação em desenvolvimento. Em breve você poderá exportar seus dados financeiros em PDF ou Excel.');
        }

        function exportPaymentHistory() {
            alert('Funcionalidade de exportação em desenvolvimento. Em breve você poderá exportar seu histórico financeiro.');
        }

        // Update page title with pending amount
        const pendingAmount = <?= array_sum(array_column($upcomingPayments, 'net_amount')) ?>;
        if (pendingAmount > 0) {
            document.title = `R$ ${pendingAmount.toLocaleString('pt-BR')} pendente - Financeiro - Clea`;
        }
    </script>
</body>
</html>