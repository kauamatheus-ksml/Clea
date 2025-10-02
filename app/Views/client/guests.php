<?php
$sidebarTitle = 'Portal do Cliente';
$pageTitle = 'Lista de Convidados';

$sidebarMenu = '
<div class="nav-section">
    <div class="nav-section-title">Planejamento</div>
    <a href="app.php?url=/client/dashboard" class="nav-item">
        <span class="nav-icon">💒</span>
        Meu Casamento
    </a>
    <a href="app.php?url=/client/wedding" class="nav-item">
        <span class="nav-icon">💍</span>
        Cronograma
    </a>
    <a href="app.php?url=/client/guests" class="nav-item active">
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

<!-- Guests Overview Header -->
<div class="guests-header">
    <div class="header-content">
        <div class="header-text">
            <h1 class="header-title">Lista de Convidados</h1>
            <p class="header-subtitle">Gerencie sua lista de convidados e organize o mapa de assentos</p>
        </div>

        <div class="header-stats">
            <div class="stat-item">
                <span class="stat-number"><?= count($guests) ?></span>
                <span class="stat-label">Total de convidados</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= count(array_filter($guests, fn($g) => $g['confirmation_status'] === 'confirmed')) ?></span>
                <span class="stat-label">Confirmados</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= count(array_filter($guests, fn($g) => $g['confirmation_status'] === 'pending')) ?></span>
                <span class="stat-label">Aguardando</span>
            </div>
        </div>
    </div>
</div>

<!-- Guest Management Tabs -->
<div class="guest-tabs">
    <button class="tab-button active" onclick="showTab('list')">📋 Lista de Convidados</button>
    <button class="tab-button" onclick="showTab('seating')">🪑 Mapa de Assentos</button>
    <button class="tab-button" onclick="showTab('invitations')">✉️ Convites</button>
</div>

<!-- Guest List Tab -->
<div id="listTab" class="tab-content active">
    <div class="content-section">
        <div class="section-header">
            <h2 class="section-title">Gerenciar Convidados</h2>
            <div class="header-actions">
                <button class="btn btn-secondary" onclick="importGuests()">📤 Importar Lista</button>
                <button class="btn btn-primary" onclick="addGuest()">➕ Adicionar Convidado</button>
            </div>
        </div>

        <!-- Guest Filters -->
        <div class="guest-filters">
            <div class="filter-group">
                <input type="text" id="searchGuests" placeholder="Buscar convidados..." class="search-input">
            </div>
            <div class="filter-group">
                <select id="statusFilter" class="filter-select">
                    <option value="">Todos os status</option>
                    <option value="confirmed">Confirmados</option>
                    <option value="pending">Aguardando resposta</option>
                    <option value="declined">Declinaram</option>
                </select>
            </div>
            <div class="filter-group">
                <select id="tableFilter" class="filter-select">
                    <option value="">Todas as mesas</option>
                    <option value="1">Mesa 1</option>
                    <option value="2">Mesa 2</option>
                    <option value="3">Mesa 3</option>
                    <option value="unassigned">Sem mesa</option>
                </select>
            </div>
        </div>

        <!-- Guest Summary Cards -->
        <div class="guest-summary">
            <div class="summary-card confirmed">
                <div class="summary-icon">✅</div>
                <div class="summary-info">
                    <div class="summary-count"><?= count(array_filter($guests, fn($g) => $g['confirmation_status'] === 'confirmed')) ?></div>
                    <div class="summary-label">Confirmados</div>
                </div>
            </div>

            <div class="summary-card pending">
                <div class="summary-icon">⏳</div>
                <div class="summary-info">
                    <div class="summary-count"><?= count(array_filter($guests, fn($g) => $g['confirmation_status'] === 'pending')) ?></div>
                    <div class="summary-label">Aguardando</div>
                </div>
            </div>

            <div class="summary-card declined">
                <div class="summary-icon">❌</div>
                <div class="summary-info">
                    <div class="summary-count"><?= count(array_filter($guests, fn($g) => $g['confirmation_status'] === 'declined')) ?></div>
                    <div class="summary-label">Declinaram</div>
                </div>
            </div>

            <div class="summary-card unassigned">
                <div class="summary-icon">🪑</div>
                <div class="summary-info">
                    <div class="summary-count"><?= count(array_filter($guests, fn($g) => !$g['seating_table'])) ?></div>
                    <div class="summary-label">Sem mesa</div>
                </div>
            </div>
        </div>

        <!-- Guest List Table -->
        <?php if (empty($guests)): ?>
        <div class="empty-state">
            <div class="empty-icon">👥</div>
            <h3>Sua lista está vazia</h3>
            <p>Comece adicionando seus convidados para o casamento.</p>
            <button class="btn btn-primary" onclick="addGuest()">Adicionar Primeiro Convidado</button>
        </div>
        <?php else: ?>

        <div class="guest-table-container">
            <table class="guest-table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                        </th>
                        <th>Nome</th>
                        <th>Confirmação</th>
                        <th>Mesa</th>
                        <th>Observações</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="guestTableBody">
                    <?php foreach ($guests as $guest): ?>
                    <tr class="guest-row"
                        data-status="<?= $guest['confirmation_status'] ?>"
                        data-table="<?= $guest['seating_table'] ?: 'unassigned' ?>">
                        <td>
                            <input type="checkbox" class="guest-checkbox" value="<?= $guest['id'] ?>">
                        </td>
                        <td>
                            <div class="guest-info">
                                <span class="guest-name"><?= htmlspecialchars($guest['name']) ?></span>
                                <span class="guest-id">#<?= $guest['id'] ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-<?= $guest['confirmation_status'] ?>">
                                <?php
                                $statusLabels = [
                                    'confirmed' => 'Confirmado',
                                    'pending' => 'Aguardando',
                                    'declined' => 'Declinou'
                                ];
                                echo $statusLabels[$guest['confirmation_status']] ?? 'Desconhecido';
                                ?>
                            </span>
                        </td>
                        <td>
                            <div class="table-assignment">
                                <?php if ($guest['seating_table']): ?>
                                <span class="table-number">Mesa <?= $guest['seating_table'] ?></span>
                                <?php else: ?>
                                <span class="no-table">Sem mesa</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <span class="guest-notes"><?= htmlspecialchars($guest['notes'] ?? '') ?></span>
                        </td>
                        <td>
                            <div class="guest-actions">
                                <button class="action-btn edit" onclick="editGuest(<?= $guest['id'] ?>)" title="Editar">✏️</button>
                                <button class="action-btn assign" onclick="assignTable(<?= $guest['id'] ?>)" title="Designar Mesa">🪑</button>
                                <button class="action-btn delete" onclick="deleteGuest(<?= $guest['id'] ?>)" title="Remover">🗑️</button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Bulk Actions -->
        <div class="bulk-actions" id="bulkActions" style="display: none;">
            <span class="selected-count" id="selectedCount">0 convidados selecionados</span>
            <div class="bulk-buttons">
                <button class="btn btn-secondary" onclick="bulkAssignTable()">🪑 Designar Mesa</button>
                <button class="btn btn-info" onclick="bulkSendInvitation()">✉️ Enviar Convite</button>
                <button class="btn btn-danger" onclick="bulkDelete()">🗑️ Remover</button>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Seating Chart Tab -->
<div id="seatingTab" class="tab-content">
    <div class="content-section">
        <div class="section-header">
            <h2 class="section-title">Mapa de Assentos</h2>
            <div class="header-actions">
                <button class="btn btn-secondary" onclick="autoAssignSeats()">🎯 Distribuição Automática</button>
                <button class="btn btn-primary" onclick="addTable()">➕ Adicionar Mesa</button>
            </div>
        </div>

        <div class="seating-container">
            <div class="seating-chart">
                <!-- Wedding Arch / Altar -->
                <div class="altar">
                    <div class="altar-icon">💒</div>
                    <div class="altar-label">Altar</div>
                </div>

                <!-- Tables -->
                <div class="tables-area">
                    <!-- Table 1 -->
                    <div class="table-item" id="table-1" data-table="1">
                        <div class="table-number">Mesa 1</div>
                        <div class="table-seats">
                            <?php
                            $table1Guests = array_filter($guests, fn($g) => $g['seating_table'] == 1);
                            for ($i = 1; $i <= 8; $i++):
                                $guest = array_shift($table1Guests);
                            ?>
                            <div class="seat <?= $guest ? 'occupied' : 'empty' ?>"
                                 data-seat="<?= $i ?>"
                                 onclick="manageSeat(1, <?= $i ?>)">
                                <?php if ($guest): ?>
                                <span class="seat-guest"><?= htmlspecialchars(substr($guest['name'], 0, 10)) ?></span>
                                <?php else: ?>
                                <span class="seat-number"><?= $i ?></span>
                                <?php endif; ?>
                            </div>
                            <?php endfor; ?>
                        </div>
                        <div class="table-info">
                            <span class="occupied-count"><?= count(array_filter($guests, fn($g) => $g['seating_table'] == 1)) ?>/8</span>
                        </div>
                    </div>

                    <!-- Table 2 -->
                    <div class="table-item" id="table-2" data-table="2">
                        <div class="table-number">Mesa 2</div>
                        <div class="table-seats">
                            <?php
                            $table2Guests = array_filter($guests, fn($g) => $g['seating_table'] == 2);
                            for ($i = 1; $i <= 8; $i++):
                                $guest = array_shift($table2Guests);
                            ?>
                            <div class="seat <?= $guest ? 'occupied' : 'empty' ?>"
                                 data-seat="<?= $i ?>"
                                 onclick="manageSeat(2, <?= $i ?>)">
                                <?php if ($guest): ?>
                                <span class="seat-guest"><?= htmlspecialchars(substr($guest['name'], 0, 10)) ?></span>
                                <?php else: ?>
                                <span class="seat-number"><?= $i ?></span>
                                <?php endif; ?>
                            </div>
                            <?php endfor; ?>
                        </div>
                        <div class="table-info">
                            <span class="occupied-count"><?= count(array_filter($guests, fn($g) => $g['seating_table'] == 2)) ?>/8</span>
                        </div>
                    </div>

                    <!-- Table 3 -->
                    <div class="table-item" id="table-3" data-table="3">
                        <div class="table-number">Mesa 3</div>
                        <div class="table-seats">
                            <?php
                            $table3Guests = array_filter($guests, fn($g) => $g['seating_table'] == 3);
                            for ($i = 1; $i <= 8; $i++):
                                $guest = array_shift($table3Guests);
                            ?>
                            <div class="seat <?= $guest ? 'occupied' : 'empty' ?>"
                                 data-seat="<?= $i ?>"
                                 onclick="manageSeat(3, <?= $i ?>)">
                                <?php if ($guest): ?>
                                <span class="seat-guest"><?= htmlspecialchars(substr($guest['name'], 0, 10)) ?></span>
                                <?php else: ?>
                                <span class="seat-number"><?= $i ?></span>
                                <?php endif; ?>
                            </div>
                            <?php endfor; ?>
                        </div>
                        <div class="table-info">
                            <span class="occupied-count"><?= count(array_filter($guests, fn($g) => $g['seating_table'] == 3)) ?>/8</span>
                        </div>
                    </div>
                </div>

                <!-- Unassigned Guests -->
                <div class="unassigned-area">
                    <h4>Convidados sem Mesa</h4>
                    <div class="unassigned-guests">
                        <?php
                        $unassignedGuests = array_filter($guests, fn($g) => !$g['seating_table']);
                        foreach ($unassignedGuests as $guest):
                        ?>
                        <div class="unassigned-guest" draggable="true" data-guest-id="<?= $guest['id'] ?>">
                            <span class="guest-name"><?= htmlspecialchars($guest['name']) ?></span>
                            <span class="guest-status status-<?= $guest['confirmation_status'] ?>">
                                <?= substr($guest['confirmation_status'], 0, 1) ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Seating Legend -->
            <div class="seating-legend">
                <h4>Legenda</h4>
                <div class="legend-items">
                    <div class="legend-item">
                        <div class="legend-sample seat occupied"></div>
                        <span>Assento Ocupado</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-sample seat empty"></div>
                        <span>Assento Livre</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-sample guest-status status-confirmed"></div>
                        <span>Confirmado</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-sample guest-status status-pending"></div>
                        <span>Aguardando</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invitations Tab -->
<div id="invitationsTab" class="tab-content">
    <div class="content-section">
        <div class="section-header">
            <h2 class="section-title">Gerenciar Convites</h2>
            <div class="header-actions">
                <button class="btn btn-secondary" onclick="previewInvitation()">👁️ Visualizar Modelo</button>
                <button class="btn btn-primary" onclick="sendBulkInvitations()">✉️ Enviar Convites</button>
            </div>
        </div>

        <!-- Invitation Stats -->
        <div class="invitation-stats">
            <div class="stat-card sent">
                <div class="stat-icon">✅</div>
                <div class="stat-content">
                    <div class="stat-value">65</div>
                    <div class="stat-label">Convites Enviados</div>
                </div>
            </div>

            <div class="stat-card pending">
                <div class="stat-icon">⏳</div>
                <div class="stat-content">
                    <div class="stat-value">12</div>
                    <div class="stat-label">Aguardando Envio</div>
                </div>
            </div>

            <div class="stat-card responses">
                <div class="stat-icon">💬</div>
                <div class="stat-content">
                    <div class="stat-value">43</div>
                    <div class="stat-label">Respostas Recebidas</div>
                </div>
            </div>
        </div>

        <!-- Invitation Template -->
        <div class="invitation-preview">
            <div class="invitation-card">
                <div class="invitation-header">
                    <h3>Marina & Frederico</h3>
                    <div class="invitation-date">29 de Março de 2026</div>
                </div>

                <div class="invitation-content">
                    <p>Com alegria, convidamos você para celebrar nosso casamento</p>
                    <div class="event-details">
                        <div class="detail-item">
                            <strong>Data:</strong> Sábado, 29 de Março de 2026
                        </div>
                        <div class="detail-item">
                            <strong>Horário:</strong> 16h00 (Cerimônia) | 18h00 (Festa)
                        </div>
                        <div class="detail-item">
                            <strong>Local:</strong> Quinta da Baronesa - São Paulo/SP
                        </div>
                    </div>

                    <div class="rsvp-section">
                        <p>Confirme sua presença até 15 de Março</p>
                        <button class="rsvp-button">Confirmar Presença</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Guest Modal -->
<div id="guestModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="guestModalTitle">Adicionar Convidado</h3>
            <button class="modal-close" onclick="closeGuestModal()">&times;</button>
        </div>

        <div class="modal-body">
            <form id="guestForm">
                <div class="form-group">
                    <label for="guestName">Nome Completo *</label>
                    <input type="text" id="guestName" name="name" required class="form-input">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="guestStatus">Status de Confirmação</label>
                        <select id="guestStatus" name="status" class="form-select">
                            <option value="pending">Aguardando Resposta</option>
                            <option value="confirmed">Confirmado</option>
                            <option value="declined">Declinou</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="guestTable">Mesa</label>
                        <select id="guestTable" name="table" class="form-select">
                            <option value="">Sem mesa</option>
                            <option value="1">Mesa 1</option>
                            <option value="2">Mesa 2</option>
                            <option value="3">Mesa 3</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="guestNotes">Observações</label>
                    <textarea id="guestNotes" name="notes" class="form-textarea" rows="3"></textarea>
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeGuestModal()">Cancelar</button>
            <button class="btn btn-primary" onclick="saveGuest()">Salvar</button>
        </div>
    </div>
</div>

<style>
.guests-header {
    background: linear-gradient(135deg, var(--primary-medium), var(--primary-dark));
    color: white;
    padding: 32px;
    border-radius: 16px;
    margin-bottom: 32px;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 32px;
}

.header-title {
    font-family: 'Lora', serif;
    font-size: 32px;
    margin-bottom: 8px;
}

.header-subtitle {
    font-size: 16px;
    opacity: 0.9;
    line-height: 1.5;
}

.header-stats {
    display: flex;
    gap: 32px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 28px;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 12px;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.guest-tabs {
    display: flex;
    background: white;
    border-radius: 12px;
    padding: 4px;
    margin-bottom: 32px;
    border: 1px solid rgba(242, 171, 177, 0.2);
    box-shadow: 0 2px 8px rgba(101, 41, 41, 0.05);
}

.tab-button {
    flex: 1;
    padding: 12px 20px;
    background: none;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 500;
    color: var(--text-secondary);
    transition: all 0.3s;
}

.tab-button.active,
.tab-button:hover {
    background: var(--primary-medium);
    color: white;
    transform: translateY(-1px);
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

.guest-filters {
    display: flex;
    gap: 16px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.search-input,
.filter-select {
    padding: 10px 12px;
    border: 1px solid rgba(242, 171, 177, 0.3);
    border-radius: 8px;
    font-size: 14px;
    min-width: 200px;
}

.guest-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.summary-card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid rgba(242, 171, 177, 0.2);
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 2px 8px rgba(101, 41, 41, 0.05);
}

.summary-icon {
    font-size: 24px;
    flex-shrink: 0;
}

.summary-count {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-primary);
}

.summary-label {
    font-size: 12px;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 16px;
    border: 1px solid rgba(242, 171, 177, 0.2);
}

.empty-icon {
    font-size: 64px;
    margin-bottom: 20px;
}

.guest-table-container {
    background: white;
    border-radius: 16px;
    border: 1px solid rgba(242, 171, 177, 0.2);
    overflow: hidden;
    margin-bottom: 16px;
}

.guest-table {
    width: 100%;
    border-collapse: collapse;
}

.guest-table th,
.guest-table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid rgba(242, 171, 177, 0.1);
}

.guest-table th {
    background: var(--primary-light);
    font-weight: 600;
    color: var(--text-primary);
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.guest-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.guest-name {
    font-weight: 500;
    color: var(--text-primary);
}

.guest-id {
    font-size: 11px;
    color: var(--text-secondary);
}

.table-assignment .no-table {
    color: var(--text-secondary);
    font-style: italic;
}

.table-number {
    background: var(--primary-light);
    color: var(--text-primary);
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
}

.guest-actions {
    display: flex;
    gap: 4px;
}

.action-btn {
    width: 28px;
    height: 28px;
    border: none;
    background: rgba(242, 171, 177, 0.1);
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    transition: all 0.3s;
}

.action-btn:hover {
    background: var(--primary-medium);
    transform: scale(1.1);
}

.bulk-actions {
    background: var(--primary-light);
    padding: 16px 20px;
    border-radius: 12px;
    border: 1px solid rgba(242, 171, 177, 0.3);
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 16px;
}

.selected-count {
    font-weight: 500;
    color: var(--text-primary);
}

.bulk-buttons {
    display: flex;
    gap: 8px;
}

/* Seating Chart Styles */
.seating-container {
    display: grid;
    grid-template-columns: 1fr 250px;
    gap: 32px;
}

.seating-chart {
    background: white;
    padding: 32px;
    border-radius: 16px;
    border: 1px solid rgba(242, 171, 177, 0.2);
    position: relative;
    min-height: 600px;
}

.altar {
    position: absolute;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    background: var(--primary-light);
    padding: 16px 24px;
    border-radius: 12px;
    border: 2px solid var(--primary-medium);
}

.altar-icon {
    font-size: 24px;
    margin-bottom: 8px;
}

.altar-label {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 14px;
}

.tables-area {
    margin-top: 120px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 40px;
    justify-items: center;
}

.table-item {
    background: white;
    border: 2px solid rgba(242, 171, 177, 0.3);
    border-radius: 50%;
    width: 180px;
    height: 180px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
    cursor: pointer;
    transition: all 0.3s;
}

.table-item:hover {
    border-color: var(--primary-medium);
    box-shadow: 0 4px 12px rgba(101, 41, 41, 0.15);
}

.table-number {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 8px;
    font-size: 14px;
}

.table-seats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 4px;
    margin-bottom: 8px;
}

.seat {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    cursor: pointer;
    transition: all 0.3s;
}

.seat.empty {
    background: rgba(242, 171, 177, 0.2);
    border: 1px solid rgba(242, 171, 177, 0.4);
    color: var(--text-secondary);
}

.seat.occupied {
    background: var(--primary-medium);
    color: white;
    font-weight: 500;
}

.seat:hover {
    transform: scale(1.1);
}

.seat-guest,
.seat-number {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.table-info {
    position: absolute;
    bottom: -25px;
    font-size: 11px;
    color: var(--text-secondary);
}

.unassigned-area {
    margin-top: 40px;
    padding: 20px;
    background: rgba(242, 171, 177, 0.05);
    border-radius: 12px;
    border: 2px dashed rgba(242, 171, 177, 0.3);
}

.unassigned-area h4 {
    color: var(--text-primary);
    margin-bottom: 16px;
    text-align: center;
}

.unassigned-guests {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: center;
}

.unassigned-guest {
    background: white;
    padding: 8px 12px;
    border-radius: 20px;
    border: 1px solid rgba(242, 171, 177, 0.3);
    cursor: grab;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    transition: all 0.3s;
}

.unassigned-guest:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(101, 41, 41, 0.1);
}

.unassigned-guest:active {
    cursor: grabbing;
}

.guest-status {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: 600;
    color: white;
}

.guest-status.status-confirmed {
    background: var(--success);
}

.guest-status.status-pending {
    background: var(--warning);
}

.guest-status.status-declined {
    background: var(--error);
}

.seating-legend {
    background: white;
    padding: 20px;
    border-radius: 12px;
    border: 1px solid rgba(242, 171, 177, 0.2);
    height: fit-content;
}

.seating-legend h4 {
    color: var(--text-primary);
    margin-bottom: 16px;
}

.legend-items {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 12px;
    color: var(--text-secondary);
}

.legend-sample {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* Invitation Styles */
.invitation-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.invitation-preview {
    display: flex;
    justify-content: center;
    margin-top: 32px;
}

.invitation-card {
    background: white;
    max-width: 400px;
    padding: 40px;
    border-radius: 16px;
    border: 1px solid rgba(242, 171, 177, 0.3);
    box-shadow: 0 8px 24px rgba(101, 41, 41, 0.15);
    text-align: center;
}

.invitation-header {
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--primary-medium);
}

.invitation-header h3 {
    font-family: 'Lora', serif;
    font-size: 24px;
    color: var(--primary-dark);
    margin-bottom: 8px;
}

.invitation-date {
    color: var(--text-secondary);
    font-style: italic;
}

.invitation-content p {
    color: var(--text-secondary);
    margin-bottom: 20px;
    line-height: 1.6;
}

.event-details {
    background: var(--primary-light);
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 24px;
    text-align: left;
}

.detail-item {
    margin-bottom: 8px;
    font-size: 14px;
    color: var(--text-primary);
}

.rsvp-section {
    text-align: center;
}

.rsvp-button {
    background: var(--primary-medium);
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}

.rsvp-button:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    width: 90%;
    max-width: 500px;
    border-radius: 16px;
    overflow: hidden;
}

.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid rgba(242, 171, 177, 0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: var(--primary-light);
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: var(--text-secondary);
}

.modal-body {
    padding: 24px;
}

.modal-footer {
    padding: 20px 24px;
    border-top: 1px solid rgba(242, 171, 177, 0.2);
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

.form-group {
    margin-bottom: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    color: var(--text-primary);
    font-size: 14px;
}

.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid rgba(242, 171, 177, 0.3);
    border-radius: 8px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--primary-medium);
    box-shadow: 0 0 0 3px rgba(242, 171, 177, 0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }

    .guest-filters {
        flex-direction: column;
    }

    .guest-summary {
        grid-template-columns: 1fr 1fr;
    }

    .seating-container {
        grid-template-columns: 1fr;
    }

    .tables-area {
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .table-item {
        width: 150px;
        height: 150px;
    }

    .bulk-actions {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }

    .invitation-stats {
        grid-template-columns: 1fr;
    }

    .modal-content {
        margin: 10% auto;
        width: 95%;
    }

    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Tab Management
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
    });

    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });

    // Show selected tab
    document.getElementById(tabName + 'Tab').classList.add('active');
    event.target.classList.add('active');
}

// Guest List Management
function addGuest() {
    document.getElementById('guestModalTitle').textContent = 'Adicionar Convidado';
    document.getElementById('guestForm').reset();
    document.getElementById('guestModal').style.display = 'block';
}

function editGuest(guestId) {
    // Mock data - in real app, this would fetch from database
    document.getElementById('guestModalTitle').textContent = 'Editar Convidado';
    document.getElementById('guestName').value = 'João Silva';
    document.getElementById('guestStatus').value = 'confirmed';
    document.getElementById('guestTable').value = '1';
    document.getElementById('guestNotes').value = 'Vegetariano';

    document.getElementById('guestModal').style.display = 'block';
}

function deleteGuest(guestId) {
    if (confirm('Tem certeza que deseja remover este convidado?')) {
        alert(`Convidado ${guestId} removido com sucesso!`);
        // In real app, this would delete from database and refresh
        location.reload();
    }
}

function saveGuest() {
    const formData = new FormData(document.getElementById('guestForm'));
    const guestData = Object.fromEntries(formData);

    console.log('Salvando convidado:', guestData);
    alert('Convidado salvo com sucesso!');

    closeGuestModal();
    // In real app, this would save to database and refresh
}

function closeGuestModal() {
    document.getElementById('guestModal').style.display = 'none';
}

// Table Assignment
function assignTable(guestId) {
    const table = prompt('Digite o número da mesa (1-3) ou deixe vazio para remover:');
    if (table !== null) {
        alert(`Convidado ${guestId} ${table ? 'atribuído à mesa ' + table : 'removido da mesa'}`);
        // In real app, this would update database and refresh
        location.reload();
    }
}

// Bulk Actions
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.guest-checkbox');

    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });

    updateBulkActions();
}

function updateBulkActions() {
    const selectedCheckboxes = document.querySelectorAll('.guest-checkbox:checked');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');

    if (selectedCheckboxes.length > 0) {
        bulkActions.style.display = 'flex';
        selectedCount.textContent = `${selectedCheckboxes.length} convidados selecionados`;
    } else {
        bulkActions.style.display = 'none';
    }
}

function bulkAssignTable() {
    const selected = document.querySelectorAll('.guest-checkbox:checked');
    const table = prompt('Digite o número da mesa para todos os convidados selecionados:');

    if (table && selected.length > 0) {
        alert(`${selected.length} convidados atribuídos à mesa ${table}`);
        // In real app, this would update database
        location.reload();
    }
}

function bulkSendInvitation() {
    const selected = document.querySelectorAll('.guest-checkbox:checked');
    if (selected.length > 0) {
        alert(`Enviando convites para ${selected.length} convidados...`);
        // In real app, this would send invitations
    }
}

function bulkDelete() {
    const selected = document.querySelectorAll('.guest-checkbox:checked');
    if (selected.length > 0 && confirm(`Remover ${selected.length} convidados selecionados?`)) {
        alert(`${selected.length} convidados removidos!`);
        // In real app, this would delete from database
        location.reload();
    }
}

// Seating Chart Management
function manageSeat(tableNumber, seatNumber) {
    const guest = prompt(`Mesa ${tableNumber}, Assento ${seatNumber}:\nDigite o nome do convidado ou deixe vazio para liberar:`);
    if (guest !== null) {
        alert(guest ? `${guest} atribuído à mesa ${tableNumber}, assento ${seatNumber}` : 'Assento liberado');
        // In real app, this would update database and refresh
        location.reload();
    }
}

function addTable() {
    alert('Adicionar nova mesa...');
    // In real app, this would open a form to add new table
}

function autoAssignSeats() {
    if (confirm('Distribuir automaticamente os convidados confirmados nas mesas?')) {
        alert('Distribuição automática realizada!');
        // In real app, this would auto-assign guests to tables
        location.reload();
    }
}

// Search and Filter
document.getElementById('searchGuests').addEventListener('input', function() {
    filterGuests();
});

document.getElementById('statusFilter').addEventListener('change', function() {
    filterGuests();
});

document.getElementById('tableFilter').addEventListener('change', function() {
    filterGuests();
});

function filterGuests() {
    const search = document.getElementById('searchGuests').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const tableFilter = document.getElementById('tableFilter').value;

    const rows = document.querySelectorAll('.guest-row');

    rows.forEach(row => {
        const name = row.querySelector('.guest-name').textContent.toLowerCase();
        const status = row.dataset.status;
        const table = row.dataset.table;

        const matchesSearch = name.includes(search);
        const matchesStatus = !statusFilter || status === statusFilter;
        const matchesTable = !tableFilter || table === tableFilter;

        if (matchesSearch && matchesStatus && matchesTable) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Invitation Management
function previewInvitation() {
    alert('Visualizar modelo de convite...');
    // In real app, this would show invitation preview modal
}

function sendBulkInvitations() {
    if (confirm('Enviar convites para todos os convidados pendentes?')) {
        alert('Convites enviados com sucesso!');
        // In real app, this would send bulk invitations
    }
}

function importGuests() {
    alert('Importar lista de convidados de arquivo CSV ou Excel...');
    // In real app, this would open file import dialog
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to checkboxes
    document.querySelectorAll('.guest-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
});

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const guestModal = document.getElementById('guestModal');
    if (event.target === guestModal) {
        closeGuestModal();
    }
});
</script>

<?php
$content = ob_get_clean();
include '../app/Views/layouts/dashboard.php';
?>