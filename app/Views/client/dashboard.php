<?php
$sidebarTitle = 'Portal do Cliente';
$pageTitle = 'Meu Casamento';

$sidebarMenu = '
<div class="nav-section">
    <div class="nav-section-title">Planejamento</div>
    <a href="app.php?url=/client/dashboard" class="nav-item active">
        <span class="nav-icon">💒</span>
        Meu Casamento
    </a>
    <a href="app.php?url=/client/wedding" class="nav-item">
        <span class="nav-icon">💍</span>
        Cronograma
    </a>
    <a href="app.php?url=/client/guests" class="nav-item">
        <span class="nav-icon">👥</span>
        Convidados
    </a>
</div>

<div class="nav-section">
    <div class="nav-section-title">Fornecedores</div>
    <a href="app.php?url=/client/vendors" class="nav-item">
        <span class="nav-icon">🎪</span>
        Explorar
    </a>
    <a href="app.php?url=/client/contracts" class="nav-item">
        <span class="nav-icon">📋</span>
        Contratos
    </a>
</div>

<div class="nav-section">
    <div class="nav-section-title">Gestão</div>
    <a href="app.php?url=/client/financial" class="nav-item">
        <span class="nav-icon">💰</span>
        Financeiro
    </a>
    <a href="app.php?url=/client/messages" class="nav-item">
        <span class="nav-icon">💬</span>
        Mensagens
    </a>
</div>';

ob_start();
?>

<!-- Wedding Overview -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Dias até o casamento</div>
            <div class="stat-icon">📅</div>
        </div>
        <div class="stat-value"><?= $stats['days_until'] ?></div>
        <div class="stat-label">Data: <?= $stats['wedding_exists'] && isset($stats['wedding_date']) ? date('d/m/Y', strtotime($stats['wedding_date'])) : 'Não definida' ?></div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Orçamento</div>
            <div class="stat-icon">💰</div>
        </div>
        <div class="stat-value">R$ <?= number_format($stats['total_value'], 0, ',', '.') ?></div>
        <div class="stat-label">Gasto: R$ <?= number_format($stats['total_value'], 0, ',', '.') ?> (100%)</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Estimados</div>
            <div class="stat-icon">✅</div>
        </div>
        <div class="stat-value"><?= $stats['estimated_guests'] ?></div>
        <div class="stat-label">Convidados estimados</div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-title">Serviços</div>
            <div class="stat-icon">📋</div>
        </div>
        <div class="stat-value"><?= $stats['active_contracts'] ?>/<?= $stats['contracted_vendors'] ?></div>
        <div class="stat-label">Contratos ativos</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="content-section">
    <div class="section-header">
        <h2 class="section-title">Próximas Tarefas</h2>
        <a href="app.php?url=/client/wedding" class="btn btn-primary">Ver Cronograma</a>
    </div>

    <div class="task-list">
        <div class="task-item urgent">
            <div class="task-icon">🎂</div>
            <div class="task-content">
                <div class="task-title">Provar bolo de casamento</div>
                <div class="task-date">Hoje, 15:00</div>
            </div>
            <span class="status-badge status-pending">Urgente</span>
        </div>

        <div class="task-item">
            <div class="task-icon">💐</div>
            <div class="task-content">
                <div class="task-title">Definir arranjos florais</div>
                <div class="task-date">Amanhã</div>
            </div>
            <span class="status-badge status-active">Pendente</span>
        </div>

        <div class="task-item">
            <div class="task-icon">📸</div>
            <div class="task-content">
                <div class="task-title">Reunião com fotógrafo</div>
                <div class="task-date">15/01/2025</div>
            </div>
            <span class="status-badge status-active">Agendado</span>
        </div>

        <div class="task-item">
            <div class="task-icon">🎵</div>
            <div class="task-content">
                <div class="task-title">Escolher playlist cerimônia</div>
                <div class="task-date">20/01/2025</div>
            </div>
            <span class="status-badge status-pending">A fazer</span>
        </div>
    </div>
</div>

<!-- Wedding Progress -->
<div class="content-section">
    <div class="section-header">
        <h2 class="section-title">Progresso do Planejamento</h2>
    </div>

    <div class="progress-grid">
        <div class="progress-category">
            <div class="progress-header">
                <div class="progress-title">💒 Cerimônia</div>
                <div class="progress-percentage">90%</div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 90%"></div>
            </div>
            <div class="progress-details">Local confirmado, decoração 80% definida</div>
        </div>

        <div class="progress-category">
            <div class="progress-header">
                <div class="progress-title">🎉 Festa</div>
                <div class="progress-percentage">75%</div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 75%"></div>
            </div>
            <div class="progress-details">Buffet e DJ confirmados, decoração pendente</div>
        </div>

        <div class="progress-category">
            <div class="progress-header">
                <div class="progress-title">📸 Fotografia</div>
                <div class="progress-percentage">100%</div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 100%"></div>
            </div>
            <div class="progress-details">Fotógrafo e videomaker contratados</div>
        </div>

        <div class="progress-category">
            <div class="progress-header">
                <div class="progress-title">👗 Vestuário</div>
                <div class="progress-percentage">60%</div>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 60%"></div>
            </div>
            <div class="progress-details">Vestido escolhido, ajustes pendentes</div>
        </div>
    </div>
</div>

<!-- Recent Vendors -->
<div class="content-section">
    <div class="section-header">
        <h2 class="section-title">Fornecedores Contratados</h2>
        <a href="app.php?url=/client/vendors" class="btn btn-secondary">Ver Todos</a>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>Fornecedor</th>
                <th>Categoria</th>
                <th>Status</th>
                <th>Valor</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="vendor-info">
                        <strong>Foto Studio Premium</strong>
                        <div style="font-size: 12px; color: #666;">⭐ 4.9 • 150 avaliações</div>
                    </div>
                </td>
                <td>Fotografia</td>
                <td><span class="status-badge status-completed">Contratado</span></td>
                <td>R$ 3.500</td>
                <td>
                    <a href="#" class="btn btn-secondary" style="padding: 4px 8px; font-size: 12px;">Ver Contrato</a>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="vendor-info">
                        <strong>Buffet Elegance</strong>
                        <div style="font-size: 12px; color: #666;">⭐ 4.8 • 89 avaliações</div>
                    </div>
                </td>
                <td>Buffet</td>
                <td><span class="status-badge status-completed">Contratado</span></td>
                <td>R$ 12.000</td>
                <td>
                    <a href="#" class="btn btn-secondary" style="padding: 4px 8px; font-size: 12px;">Ver Contrato</a>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="vendor-info">
                        <strong>DJ Music Events</strong>
                        <div style="font-size: 12px; color: #666;">⭐ 4.7 • 76 avaliações</div>
                    </div>
                </td>
                <td>Música</td>
                <td><span class="status-badge status-pending">Negociando</span></td>
                <td>R$ 2.500</td>
                <td>
                    <a href="#" class="btn btn-primary" style="padding: 4px 8px; font-size: 12px;">Finalizar</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<style>
.task-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.task-item {
    display: flex;
    align-items: center;
    padding: 16px;
    background: var(--card-bg);
    border-radius: 12px;
    border: 1px solid rgba(242, 171, 177, 0.2);
    transition: all 0.3s;
}

.task-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(101, 41, 41, 0.1);
}

.task-item.urgent {
    border-left: 4px solid var(--error);
    background: rgba(239, 68, 68, 0.05);
}

.task-icon {
    font-size: 24px;
    margin-right: 16px;
    flex-shrink: 0;
}

.task-content {
    flex: 1;
}

.task-title {
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.task-date {
    font-size: 12px;
    color: var(--text-secondary);
}

.progress-grid {
    display: grid;
    gap: 20px;
}

.progress-category {
    padding: 20px;
    background: var(--card-bg);
    border-radius: 12px;
    border: 1px solid rgba(242, 171, 177, 0.2);
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.progress-title {
    font-weight: 600;
    color: var(--text-primary);
}

.progress-percentage {
    font-weight: 700;
    color: var(--primary-dark);
}

.progress-bar {
    width: 100%;
    height: 8px;
    background-color: rgba(242, 171, 177, 0.2);
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 8px;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary-medium), var(--primary-dark));
    border-radius: 4px;
    transition: width 0.3s;
}

.progress-details {
    font-size: 12px;
    color: var(--text-secondary);
}

.vendor-info strong {
    display: block;
    color: var(--text-primary);
}
</style>

<?php
$content = ob_get_clean();
include '../app/Views/layouts/dashboard.php';
?>