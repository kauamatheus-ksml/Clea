<?php
$sidebarTitle = 'Portal do Cliente';
$pageTitle = 'Meus Contratos';

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
    <a href="app.php?url=/client/contracts" class="nav-item active">
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

<!-- Contracts Overview Header -->
<div class="contracts-header">
    <div class="header-content">
        <div class="header-text">
            <h1 class="header-title">Gestão de Contratos</h1>
            <p class="header-subtitle">Acompanhe todos os seus contratos digitais e status de assinatura</p>
        </div>

        <div class="header-stats">
            <div class="stat-item">
                <span class="stat-number"><?= count($contracts) ?></span>
                <span class="stat-label">Total de contratos</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= count(array_filter($contracts, fn($c) => $c['status'] === 'active')) ?></span>
                <span class="stat-label">Ativos</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">R$ <?= number_format(array_sum(array_column($contracts, 'total_value')), 0, ',', '.') ?></span>
                <span class="stat-label">Valor total</span>
            </div>
        </div>
    </div>
</div>

<!-- Contract Status Summary -->
<div class="status-summary">
    <div class="status-card pending">
        <div class="status-icon">⏳</div>
        <div class="status-info">
            <div class="status-count"><?= count(array_filter($contracts, fn($c) => $c['status'] === 'pending_signature')) ?></div>
            <div class="status-label">Aguardando Assinatura</div>
        </div>
    </div>

    <div class="status-card active">
        <div class="status-icon">✅</div>
        <div class="status-info">
            <div class="status-count"><?= count(array_filter($contracts, fn($c) => $c['status'] === 'active')) ?></div>
            <div class="status-label">Contratos Ativos</div>
        </div>
    </div>

    <div class="status-card completed">
        <div class="status-icon">🎉</div>
        <div class="status-info">
            <div class="status-count"><?= count(array_filter($contracts, fn($c) => $c['status'] === 'completed')) ?></div>
            <div class="status-label">Concluídos</div>
        </div>
    </div>

    <div class="status-card cancelled">
        <div class="status-icon">❌</div>
        <div class="status-info">
            <div class="status-count"><?= count(array_filter($contracts, fn($c) => $c['status'] === 'cancelled')) ?></div>
            <div class="status-label">Cancelados</div>
        </div>
    </div>
</div>

<!-- Contracts List -->
<div class="content-section">
    <div class="section-header">
        <h2 class="section-title">Seus Contratos</h2>
        <div class="header-actions">
            <select id="statusFilter" class="filter-select">
                <option value="">Todos os status</option>
                <option value="pending_signature">Aguardando Assinatura</option>
                <option value="active">Ativos</option>
                <option value="completed">Concluídos</option>
                <option value="cancelled">Cancelados</option>
            </select>
        </div>
    </div>

    <?php if (empty($contracts)): ?>
    <div class="empty-state">
        <div class="empty-icon">📋</div>
        <h3>Nenhum contrato encontrado</h3>
        <p>Você ainda não possui contratos. Explore nossos fornecedores e faça suas contratações.</p>
        <a href="app.php?url=/client/vendors" class="btn btn-primary">Explorar Fornecedores</a>
    </div>
    <?php else: ?>

    <div class="contracts-list">
        <?php foreach ($contracts as $contract): ?>
        <div class="contract-card" data-status="<?= $contract['status'] ?>">
            <div class="contract-header">
                <div class="contract-vendor">
                    <div class="vendor-avatar">
                        <?= strtoupper(substr($contract['business_name'] ?? $contract['vendor_name'], 0, 2)) ?>
                    </div>
                    <div class="vendor-info">
                        <h3 class="vendor-name"><?= htmlspecialchars($contract['business_name'] ?? $contract['vendor_name']) ?></h3>
                        <span class="vendor-category"><?= htmlspecialchars($contract['category'] ?? 'Serviço') ?></span>
                    </div>
                </div>

                <div class="contract-status">
                    <span class="status-badge status-<?= $contract['status'] ?>">
                        <?php
                        $statusLabels = [
                            'pending_signature' => 'Aguardando Assinatura',
                            'active' => 'Ativo',
                            'completed' => 'Concluído',
                            'cancelled' => 'Cancelado'
                        ];
                        echo $statusLabels[$contract['status']] ?? 'Desconhecido';
                        ?>
                    </span>
                </div>
            </div>

            <div class="contract-content">
                <div class="contract-description">
                    <p><?= htmlspecialchars($contract['service_description']) ?></p>
                </div>

                <div class="contract-details">
                    <div class="detail-row">
                        <span class="detail-label">Valor do Contrato:</span>
                        <span class="detail-value">R$ <?= number_format($contract['total_value'], 2, ',', '.') ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Taxa de Comissão:</span>
                        <span class="detail-value"><?= ($contract['commission_rate'] * 100) ?>%</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Data de Criação:</span>
                        <span class="detail-value"><?= date('d/m/Y', strtotime($contract['created_at'])) ?></span>
                    </div>
                    <?php if ($contract['signed_at']): ?>
                    <div class="detail-row">
                        <span class="detail-label">Data de Assinatura:</span>
                        <span class="detail-value"><?= date('d/m/Y H:i', strtotime($contract['signed_at'])) ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if ($contract['status'] === 'pending_signature'): ?>
                <div class="signature-alert">
                    <div class="alert-icon">⚠️</div>
                    <div class="alert-content">
                        <strong>Ação Necessária:</strong> Este contrato está aguardando sua assinatura digital.
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="contract-actions">
                <button class="btn btn-secondary" onclick="viewContract(<?= $contract['id'] ?>)">
                    📄 Visualizar Contrato
                </button>

                <?php if ($contract['status'] === 'pending_signature'): ?>
                <button class="btn btn-primary" onclick="signContract(<?= $contract['id'] ?>)">
                    ✍️ Assinar Digitalmente
                </button>
                <?php elseif ($contract['status'] === 'active'): ?>
                <button class="btn btn-success" onclick="downloadContract(<?= $contract['id'] ?>)">
                    💾 Baixar PDF
                </button>
                <button class="btn btn-info" onclick="sendMessage(<?= $contract['vendor_user_id'] ?>)">
                    💬 Conversar
                </button>
                <?php endif; ?>

                <div class="dropdown">
                    <button class="btn btn-outline dropdown-toggle" onclick="toggleDropdown(<?= $contract['id'] ?>)">
                        ⋮ Mais
                    </button>
                    <div class="dropdown-menu" id="dropdown-<?= $contract['id'] ?>">
                        <a href="#" onclick="viewTimeline(<?= $contract['id'] ?>)">📅 Histórico</a>
                        <a href="#" onclick="requestChanges(<?= $contract['id'] ?>)">✏️ Solicitar Alteração</a>
                        <?php if ($contract['status'] === 'active'): ?>
                        <a href="#" onclick="requestCancellation(<?= $contract['id'] ?>)">🗑️ Solicitar Cancelamento</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Contract Viewer Modal -->
<div id="contractModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalContractTitle">Contrato Digital</h3>
            <button class="modal-close" onclick="closeContractModal()">&times;</button>
        </div>

        <div class="modal-body">
            <div class="contract-preview">
                <div class="contract-document">
                    <div class="document-header">
                        <h2>CONTRATO DE PRESTAÇÃO DE SERVIÇOS</h2>
                        <div class="contract-number">Contrato Nº: <span id="modalContractNumber"></span></div>
                    </div>

                    <div class="contract-parties">
                        <div class="party">
                            <h4>CONTRATANTE:</h4>
                            <p><strong><?= htmlspecialchars($_SESSION['user_name']) ?></strong></p>
                            <p>Email: <?= htmlspecialchars($_SESSION['user_email'] ?? 'cliente@email.com') ?></p>
                        </div>

                        <div class="party">
                            <h4>CONTRATADA:</h4>
                            <p id="modalVendorName"></p>
                            <p id="modalVendorCategory"></p>
                        </div>
                    </div>

                    <div class="contract-terms">
                        <h4>OBJETO DO CONTRATO:</h4>
                        <p id="modalServiceDescription"></p>

                        <h4>VALOR E FORMA DE PAGAMENTO:</h4>
                        <p>Valor total: <span id="modalContractValue"></span></p>
                        <p>• 40% na assinatura do contrato</p>
                        <p>• 60% até 30 dias antes do evento</p>

                        <h4>PRAZO E LOCAL:</h4>
                        <p>Data do evento: <span id="modalEventDate"></span></p>
                        <p>Local: <span id="modalEventLocation"></span></p>
                    </div>

                    <div class="signature-section">
                        <div class="signature-box">
                            <div class="signature-status" id="modalSignatureStatus"></div>
                            <div class="signature-date" id="modalSignatureDate"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeContractModal()">Fechar</button>
            <button class="btn btn-primary" id="modalActionBtn" style="display: none;">Assinar Contrato</button>
        </div>
    </div>
</div>

<!-- Timeline Modal -->
<div id="timelineModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Histórico do Contrato</h3>
            <button class="modal-close" onclick="closeTimelineModal()">&times;</button>
        </div>

        <div class="modal-body">
            <div class="timeline">
                <div class="timeline-item completed">
                    <div class="timeline-icon">📝</div>
                    <div class="timeline-content">
                        <h4>Contrato Criado</h4>
                        <p>Contrato gerado automaticamente pela plataforma</p>
                        <span class="timeline-date">15/03/2026 - 14:30</span>
                    </div>
                </div>

                <div class="timeline-item completed">
                    <div class="timeline-icon">👁️</div>
                    <div class="timeline-content">
                        <h4>Contrato Visualizado</h4>
                        <p>Cliente visualizou o contrato pela primeira vez</p>
                        <span class="timeline-date">15/03/2026 - 15:45</span>
                    </div>
                </div>

                <div class="timeline-item current">
                    <div class="timeline-icon">✍️</div>
                    <div class="timeline-content">
                        <h4>Aguardando Assinatura</h4>
                        <p>Contrato aguardando assinatura digital do cliente</p>
                        <span class="timeline-date">Status atual</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeTimelineModal()">Fechar</button>
        </div>
    </div>
</div>

<style>
.contracts-header {
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

.status-summary {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.status-card {
    background: white;
    padding: 24px;
    border-radius: 16px;
    border: 1px solid rgba(242, 171, 177, 0.2);
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 4px 12px rgba(101, 41, 41, 0.08);
    transition: transform 0.3s;
}

.status-card:hover {
    transform: translateY(-2px);
}

.status-icon {
    font-size: 32px;
    flex-shrink: 0;
}

.status-count {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-primary);
}

.status-label {
    font-size: 14px;
    color: var(--text-secondary);
    margin-top: 4px;
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

.empty-state h3 {
    color: var(--text-primary);
    margin-bottom: 12px;
}

.empty-state p {
    color: var(--text-secondary);
    margin-bottom: 24px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.contracts-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.contract-card {
    background: white;
    border-radius: 16px;
    border: 1px solid rgba(242, 171, 177, 0.2);
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(101, 41, 41, 0.08);
    transition: all 0.3s;
}

.contract-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(101, 41, 41, 0.15);
}

.contract-header {
    padding: 24px;
    background: var(--primary-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid rgba(242, 171, 177, 0.2);
}

.contract-vendor {
    display: flex;
    align-items: center;
    gap: 16px;
}

.vendor-avatar {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary-medium), var(--primary-dark));
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}

.vendor-name {
    font-family: 'Lora', serif;
    font-size: 18px;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.vendor-category {
    font-size: 12px;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.contract-content {
    padding: 24px;
}

.contract-description {
    margin-bottom: 20px;
}

.contract-description p {
    color: var(--text-secondary);
    line-height: 1.6;
}

.contract-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
    margin-bottom: 20px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
}

.detail-label {
    font-size: 14px;
    color: var(--text-secondary);
}

.detail-value {
    font-weight: 500;
    color: var(--text-primary);
}

.signature-alert {
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.3);
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert-icon {
    font-size: 20px;
    flex-shrink: 0;
}

.alert-content {
    color: var(--text-primary);
    font-size: 14px;
}

.contract-actions {
    padding: 24px;
    background: rgba(242, 171, 177, 0.05);
    border-top: 1px solid rgba(242, 171, 177, 0.2);
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: center;
}

.dropdown {
    position: relative;
}

.dropdown-toggle {
    background: rgba(242, 171, 177, 0.1);
    border: 1px solid rgba(242, 171, 177, 0.3);
    padding: 8px 12px;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid rgba(242, 171, 177, 0.3);
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(101, 41, 41, 0.15);
    z-index: 10;
    min-width: 200px;
    display: none;
}

.dropdown-menu.show {
    display: block;
}

.dropdown-menu a {
    display: block;
    padding: 12px 16px;
    color: var(--text-primary);
    text-decoration: none;
    font-size: 14px;
    transition: background 0.3s;
}

.dropdown-menu a:hover {
    background: var(--primary-light);
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
    margin: 3% auto;
    width: 90%;
    max-width: 900px;
    border-radius: 16px;
    overflow: hidden;
    max-height: 90vh;
    overflow-y: auto;
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

.contract-document {
    background: white;
    padding: 40px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-family: 'Times New Roman', serif;
    line-height: 1.6;
}

.document-header {
    text-align: center;
    margin-bottom: 40px;
    border-bottom: 2px solid var(--primary-dark);
    padding-bottom: 20px;
}

.document-header h2 {
    color: var(--primary-dark);
    margin-bottom: 10px;
}

.contract-number {
    font-size: 14px;
    color: var(--text-secondary);
}

.contract-parties {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-bottom: 30px;
}

.party h4 {
    color: var(--primary-dark);
    margin-bottom: 10px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 5px;
}

.contract-terms h4 {
    color: var(--primary-dark);
    margin: 20px 0 10px 0;
}

.signature-section {
    margin-top: 40px;
    padding-top: 20px;
    border-top: 1px solid #ddd;
}

.signature-box {
    text-align: center;
    padding: 20px;
    background: rgba(242, 171, 177, 0.05);
    border-radius: 8px;
}

.signature-status {
    font-weight: 600;
    margin-bottom: 8px;
}

.signature-date {
    font-size: 12px;
    color: var(--text-secondary);
}

.modal-footer {
    padding: 20px 24px;
    border-top: 1px solid rgba(242, 171, 177, 0.2);
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

/* Timeline Styles */
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: rgba(242, 171, 177, 0.3);
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-icon {
    position: absolute;
    left: -23px;
    top: 0;
    width: 30px;
    height: 30px;
    background: white;
    border: 2px solid rgba(242, 171, 177, 0.3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}

.timeline-item.completed .timeline-icon {
    border-color: var(--success);
    background: var(--success);
    color: white;
}

.timeline-item.current .timeline-icon {
    border-color: var(--primary-medium);
    background: var(--primary-medium);
    color: white;
}

.timeline-content h4 {
    color: var(--text-primary);
    margin-bottom: 4px;
}

.timeline-content p {
    color: var(--text-secondary);
    font-size: 14px;
    margin-bottom: 4px;
}

.timeline-date {
    font-size: 12px;
    color: var(--text-secondary);
    font-style: italic;
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }

    .status-summary {
        grid-template-columns: 1fr 1fr;
    }

    .contract-header {
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }

    .contract-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .contract-parties {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .modal-content {
        margin: 10% auto;
        width: 95%;
    }
}
</style>

<script>
// Filter contracts by status
document.getElementById('statusFilter').addEventListener('change', function() {
    const selectedStatus = this.value;
    const contractCards = document.querySelectorAll('.contract-card');

    contractCards.forEach(card => {
        if (!selectedStatus || card.dataset.status === selectedStatus) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Contract actions
function viewContract(contractId) {
    // Mock data - in real app, this would come from database
    const contract = {
        id: contractId,
        number: 'CLE-2026-' + contractId.toString().padStart(4, '0'),
        vendorName: 'Foto Studio Premium',
        vendorCategory: 'Fotografia Profissional',
        serviceDescription: 'Serviços completos de fotografia para casamento, incluindo cerimônia, festa e ensaio pré-wedding.',
        value: 'R$ 3.500,00',
        eventDate: '29/03/2026',
        eventLocation: 'Quinta da Baronesa - São Paulo/SP',
        signatureStatus: 'Aguardando assinatura do cliente',
        signatureDate: 'Pendente',
        status: 'pending_signature'
    };

    // Populate modal
    document.getElementById('modalContractNumber').textContent = contract.number;
    document.getElementById('modalVendorName').textContent = contract.vendorName;
    document.getElementById('modalVendorCategory').textContent = contract.vendorCategory;
    document.getElementById('modalServiceDescription').textContent = contract.serviceDescription;
    document.getElementById('modalContractValue').textContent = contract.value;
    document.getElementById('modalEventDate').textContent = contract.eventDate;
    document.getElementById('modalEventLocation').textContent = contract.eventLocation;
    document.getElementById('modalSignatureStatus').textContent = contract.signatureStatus;
    document.getElementById('modalSignatureDate').textContent = contract.signatureDate;

    // Show action button if contract is pending signature
    const actionBtn = document.getElementById('modalActionBtn');
    if (contract.status === 'pending_signature') {
        actionBtn.style.display = 'block';
        actionBtn.textContent = 'Assinar Contrato';
        actionBtn.onclick = () => signContract(contractId);
    } else {
        actionBtn.style.display = 'none';
    }

    document.getElementById('contractModal').style.display = 'block';
}

function closeContractModal() {
    document.getElementById('contractModal').style.display = 'none';
}

function signContract(contractId) {
    if (confirm('Confirma a assinatura digital deste contrato?')) {
        alert(`Contrato ${contractId} assinado com sucesso!`);
        // In real app, this would update the database and refresh the page
        location.reload();
    }
}

function downloadContract(contractId) {
    alert(`Iniciando download do contrato ${contractId} em PDF...`);
    // In real app, this would generate and download the PDF
}

function sendMessage(vendorId) {
    window.location.href = `app.php?url=/client/messages&vendor=${vendorId}`;
}

function viewTimeline(contractId) {
    document.getElementById('timelineModal').style.display = 'block';
}

function closeTimelineModal() {
    document.getElementById('timelineModal').style.display = 'none';
}

function requestChanges(contractId) {
    alert(`Solicitar alterações no contrato ${contractId}`);
    // In real app, this would open a form or chat
}

function requestCancellation(contractId) {
    if (confirm('Tem certeza que deseja solicitar o cancelamento deste contrato?')) {
        alert(`Solicitação de cancelamento enviada para o contrato ${contractId}`);
        // In real app, this would update the status and notify parties
    }
}

function toggleDropdown(contractId) {
    const dropdown = document.getElementById(`dropdown-${contractId}`);
    dropdown.classList.toggle('show');
}

// Close dropdown when clicking outside
window.addEventListener('click', function(event) {
    if (!event.target.matches('.dropdown-toggle')) {
        const dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach(dropdown => dropdown.classList.remove('show'));
    }
});

// Close modals when clicking outside
window.addEventListener('click', function(event) {
    const contractModal = document.getElementById('contractModal');
    const timelineModal = document.getElementById('timelineModal');

    if (event.target === contractModal) {
        closeContractModal();
    }
    if (event.target === timelineModal) {
        closeTimelineModal();
    }
});
</script>

<?php
$content = ob_get_clean();
include '../app/Views/layouts/dashboard.php';
?>