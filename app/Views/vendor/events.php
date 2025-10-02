<?php
require_once ROOT_PATH . '/app/core/Auth.php';
use App\Core\Auth;

if (!Auth::isLoggedIn() || Auth::getUserRole() !== 'vendor') {
    header('Location: /public/login.php');
    exit();
}

$user = Auth::getUser();
$events = $data['events'] ?? [];
$vendorProfile = $data['vendorProfile'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Eventos - Fornecedor - Clea Casamentos</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .events-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filters-bar {
            display: flex;
            gap: 15px;
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

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .event-card {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--gray-200);
            overflow: hidden;
            transition: all 0.2s;
            cursor: pointer;
        }

        .event-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border-color: var(--primary);
        }

        .event-card.past {
            opacity: 0.8;
        }

        .event-header {
            padding: 20px;
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border-bottom: 1px solid var(--gray-200);
        }

        .event-date {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .event-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 5px;
        }

        .event-location {
            color: var(--gray-600);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .event-body {
            padding: 20px;
        }

        .event-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .event-detail {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--gray-700);
        }

        .event-detail i {
            color: var(--primary);
            width: 16px;
        }

        .contract-info {
            background: var(--gray-50);
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid var(--primary);
            margin-bottom: 15px;
        }

        .contract-value {
            font-size: 18px;
            font-weight: 700;
            color: var(--success);
            margin-bottom: 5px;
        }

        .commission-info {
            font-size: 12px;
            color: var(--gray-600);
        }

        .event-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .action-btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .action-btn.primary {
            background: var(--primary);
            color: white;
        }

        .action-btn.primary:hover {
            background: var(--primary-dark);
        }

        .action-btn.outline {
            border: 1px solid var(--gray-300);
            color: var(--gray-700);
            background: var(--white);
        }

        .action-btn.outline:hover {
            background: var(--gray-50);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background: #dcfce7;
            color: #16a34a;
        }

        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-completed {
            background: #e0e7ff;
            color: #4f46e5;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #dc2626;
        }

        .other-vendors {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid var(--gray-200);
        }

        .other-vendors-title {
            font-size: 12px;
            color: var(--gray-600);
            margin-bottom: 8px;
            font-weight: 600;
        }

        .vendors-list {
            font-size: 13px;
            color: var(--gray-700);
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

        .empty-state h3 {
            margin-bottom: 10px;
            color: var(--gray-700);
        }

        .search-bar {
            position: relative;
            min-width: 200px;
        }

        .search-input {
            width: 100%;
            padding: 8px 12px 8px 35px;
            border: 1px solid var(--gray-300);
            border-radius: 6px;
            font-size: 14px;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray-400);
        }

        @media (max-width: 768px) {
            .events-header {
                flex-direction: column;
                align-items: stretch;
            }

            .filters-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .events-grid {
                grid-template-columns: 1fr;
            }

            .event-details {
                grid-template-columns: 1fr;
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
                <a href="app.php?url=/vendor/events" class="active"><i class="fas fa-calendar-alt"></i> Meus Eventos</a>
                <a href="app.php?url=/vendor/financial"><i class="fas fa-dollar-sign"></i> Financeiro</a>
                <a href="app.php?url=/vendor/messages"><i class="fas fa-comments"></i> Mensagens</a>
                <a href="app.php?url=/vendor/profile"><i class="fas fa-user-edit"></i> Meu Perfil</a>
                <div class="sidebar-divider"></div>
                <a href="auth.php?action=logout"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="events-header">
                <div>
                    <h1><i class="fas fa-calendar-alt"></i> Meus Eventos</h1>
                    <p><?= count($events) ?> evento(s) contratado(s)</p>
                </div>

                <div class="filters-bar">
                    <div class="search-bar">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" id="searchInput" placeholder="Buscar eventos...">
                    </div>

                    <select class="filter-select" id="statusFilter">
                        <option value="">Todos os Status</option>
                        <option value="active">Ativos</option>
                        <option value="pending_signature">Pendentes</option>
                        <option value="completed">Concluídos</option>
                        <option value="cancelled">Cancelados</option>
                    </select>

                    <select class="filter-select" id="dateFilter">
                        <option value="">Todas as Datas</option>
                        <option value="upcoming">Próximos</option>
                        <option value="past">Passados</option>
                        <option value="this-month">Este Mês</option>
                        <option value="next-month">Próximo Mês</option>
                    </select>
                </div>
            </div>

            <?php if (empty($events)): ?>
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h3>Nenhum evento encontrado</h3>
                    <p>Você ainda não possui eventos contratados.<br>
                       Aguarde novos clientes entrarem em contato!</p>
                </div>
            <?php else: ?>
                <div class="events-grid" id="eventsGrid">
                    <?php foreach ($events as $event): ?>
                        <?php
                        $isPast = strtotime($event['wedding_date']) < time();
                        $daysUntil = ceil((strtotime($event['wedding_date']) - time()) / (60 * 60 * 24));
                        $netValue = $event['total_value'] * (1 - $event['commission_rate']);
                        ?>
                        <div class="event-card <?= $isPast ? 'past' : '' ?>"
                             data-status="<?= $event['status'] ?>"
                             data-date="<?= $event['wedding_date'] ?>"
                             data-client="<?= strtolower($event['client_name']) ?>"
                             onclick="showEventDetails(<?= $event['id'] ?>)">

                            <div class="event-header">
                                <div class="event-date">
                                    <i class="fas fa-calendar"></i>
                                    <?= date('d/m/Y', strtotime($event['wedding_date'])) ?>
                                    <span class="status-badge status-<?= $event['status'] ?>">
                                        <i class="fas fa-circle"></i>
                                        <?php
                                        $statusLabels = [
                                            'active' => 'Ativo',
                                            'pending_signature' => 'Pendente',
                                            'completed' => 'Concluído',
                                            'cancelled' => 'Cancelado'
                                        ];
                                        echo $statusLabels[$event['status']] ?? 'Indefinido';
                                        ?>
                                    </span>
                                </div>
                                <h3 class="event-title">
                                    <?= htmlspecialchars($event['client_name']) ?> & <?= htmlspecialchars($event['partner_name']) ?>
                                </h3>
                                <div class="event-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= htmlspecialchars($event['location_details']) ?>
                                </div>
                            </div>

                            <div class="event-body">
                                <div class="event-details">
                                    <div class="event-detail">
                                        <i class="fas fa-users"></i>
                                        <?= $event['estimated_guests'] ?? 'N/A' ?> convidados
                                    </div>
                                    <div class="event-detail">
                                        <i class="fas fa-clock"></i>
                                        <?php if ($isPast): ?>
                                            Realizado
                                        <?php elseif ($daysUntil <= 0): ?>
                                            Hoje!
                                        <?php elseif ($daysUntil == 1): ?>
                                            Amanhã
                                        <?php else: ?>
                                            <?= $daysUntil ?> dias
                                        <?php endif; ?>
                                    </div>
                                    <div class="event-detail">
                                        <i class="fas fa-envelope"></i>
                                        <?= htmlspecialchars($event['client_email']) ?>
                                    </div>
                                    <div class="event-detail">
                                        <i class="fas fa-phone"></i>
                                        <?= htmlspecialchars($event['client_phone'] ?? 'N/A') ?>
                                    </div>
                                </div>

                                <div class="contract-info">
                                    <div class="contract-value">
                                        R$ <?= number_format($netValue, 2, ',', '.') ?>
                                    </div>
                                    <div class="commission-info">
                                        Valor bruto: R$ <?= number_format($event['total_value'], 2, ',', '.') ?>
                                        (Comissão Clea: <?= ($event['commission_rate'] * 100) ?>%)
                                    </div>
                                </div>

                                <?php if ($event['other_vendor_count'] > 0): ?>
                                <div class="other-vendors">
                                    <div class="other-vendors-title">
                                        <i class="fas fa-users"></i> Outros fornecedores
                                    </div>
                                    <div class="vendors-list">
                                        <?= $event['other_vendor_count'] ?> outro(s) fornecedor(es) neste evento
                                    </div>
                                </div>
                                <?php endif; ?>

                                <div class="event-actions">
                                    <a href="app.php?url=/vendor/messages?event=<?= $event['wedding_id'] ?>"
                                       class="action-btn outline" onclick="event.stopPropagation()">
                                        <i class="fas fa-comments"></i> Chat
                                    </a>
                                    <a href="javascript:void(0)"
                                       class="action-btn primary"
                                       onclick="event.stopPropagation(); showEventDetails(<?= $event['id'] ?>)">
                                        <i class="fas fa-eye"></i> Detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <!-- Event Details Modal -->
    <div id="eventModal" class="modal" style="display: none;">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <h3><i class="fas fa-calendar-alt"></i> Detalhes do Evento</h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body" id="eventModalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', filterEvents);
        document.getElementById('statusFilter').addEventListener('change', filterEvents);
        document.getElementById('dateFilter').addEventListener('change', filterEvents);

        function filterEvents() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;
            const cards = document.querySelectorAll('.event-card');

            cards.forEach(card => {
                const client = card.dataset.client;
                const status = card.dataset.status;
                const eventDate = new Date(card.dataset.date);
                const now = new Date();
                const thisMonth = new Date(now.getFullYear(), now.getMonth(), 1);
                const nextMonth = new Date(now.getFullYear(), now.getMonth() + 1, 1);

                let showCard = true;

                // Search filter
                if (searchTerm && !client.includes(searchTerm)) {
                    showCard = false;
                }

                // Status filter
                if (statusFilter && status !== statusFilter) {
                    showCard = false;
                }

                // Date filter
                if (dateFilter) {
                    switch (dateFilter) {
                        case 'upcoming':
                            if (eventDate < now) showCard = false;
                            break;
                        case 'past':
                            if (eventDate >= now) showCard = false;
                            break;
                        case 'this-month':
                            if (eventDate < thisMonth || eventDate >= nextMonth) showCard = false;
                            break;
                        case 'next-month':
                            const nextNextMonth = new Date(now.getFullYear(), now.getMonth() + 2, 1);
                            if (eventDate < nextMonth || eventDate >= nextNextMonth) showCard = false;
                            break;
                    }
                }

                card.style.display = showCard ? 'block' : 'none';
            });
        }

        function showEventDetails(eventId) {
            // In a real application, this would fetch event details via AJAX
            const modal = document.getElementById('eventModal');
            const content = document.getElementById('eventModalContent');

            content.innerHTML = `
                <div style="text-align: center; padding: 40px;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 32px; color: var(--primary);"></i>
                    <p style="margin-top: 15px;">Carregando detalhes do evento...</p>
                </div>
            `;

            modal.style.display = 'block';

            // Simulate loading
            setTimeout(() => {
                content.innerHTML = `
                    <div>
                        <h4>Informações Detalhadas</h4>
                        <p>Aqui apareceriam detalhes completos do evento #${eventId}</p>
                        <ul>
                            <li>Timeline completa do casamento</li>
                            <li>Detalhes dos serviços contratados</li>
                            <li>Informações de contato dos noivos</li>
                            <li>Histórico de mensagens</li>
                            <li>Documentos e contratos</li>
                        </ul>
                        <p><em>Funcionalidade em desenvolvimento...</em></p>
                    </div>
                `;
            }, 1000);
        }

        // Modal functionality
        document.querySelector('.close').onclick = function() {
            document.getElementById('eventModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('eventModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>

    <style>
        /* Modal Styles */
        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: var(--white);
            margin: 5% auto;
            border-radius: 12px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid var(--gray-200);
        }

        .modal-header h3 {
            margin: 0;
            color: var(--gray-900);
        }

        .close {
            color: var(--gray-500);
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
        }

        .close:hover {
            color: var(--gray-900);
        }

        .modal-body {
            padding: 20px;
        }
    </style>
</body>
</html>