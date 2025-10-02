<?php
$sidebarTitle = 'Portal do Cliente';
$pageTitle = 'Gestão Financeira';

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
    <a href="app.php?url=/client/contracts" class="nav-item">
        <span class="nav-icon">📋</span>
        Contratos
    </a>
</div>

<div class="nav-section">
    <div class="nav-section-title">Gestão</div>
    <a href="app.php?url=/client/financial" class="nav-item active">
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

<!-- Financial Overview Header -->
<div class="financial-header">
    <div class="header-content">
        <div class="header-text">
            <h1 class="header-title">Gestão Financeira</h1>
            <p class="header-subtitle">Acompanhe todos os gastos, pagamentos e cronograma financeiro do seu casamento</p>
        </div>

        <div class="budget-summary">
            <div class="budget-circle">
                <div class="budget-amount">R$ <?= number_format($financialData['total_value'], 0, ',', '.') ?></div>
                <div class="budget-label">Total Contratado</div>
            </div>
        </div>
    </div>
</div>

<!-- Financial Stats -->
<div class="financial-stats">
    <div class="stat-card total">
        <div class="stat-icon">💰</div>
        <div class="stat-content">
            <div class="stat-value">R$ <?= number_format($financialData['total_value'], 2, ',', '.') ?></div>
            <div class="stat-label">Valor Total</div>
            <div class="stat-sublabel">Todos os contratos</div>
        </div>
    </div>

    <div class="stat-card committed">
        <div class="stat-icon">✅</div>
        <div class="stat-content">
            <div class="stat-value">R$ <?= number_format($financialData['committed_value'], 2, ',', '.') ?></div>
            <div class="stat-label">Comprometido</div>
            <div class="stat-sublabel">Contratos ativos</div>
        </div>
    </div>

    <div class="stat-card pending">
        <div class="stat-icon">⏳</div>
        <div class="stat-content">
            <div class="stat-value">R$ <?= number_format($financialData['pending_value'], 2, ',', '.') ?></div>
            <div class="stat-label">Pendente</div>
            <div class="stat-sublabel">Aguardando confirmação</div>
        </div>
    </div>

    <div class="stat-card installments">
        <div class="stat-icon">📅</div>
        <div class="stat-content">
            <div class="stat-value"><?= count($financialData['payment_schedule']) ?></div>
            <div class="stat-label">Parcelas</div>
            <div class="stat-sublabel">Cronograma de pagamentos</div>
        </div>
    </div>
</div>

<!-- Payment Schedule -->
<div class="content-section">
    <div class="section-header">
        <h2 class="section-title">Cronograma de Pagamentos</h2>
        <div class="header-actions">
            <button class="btn btn-secondary" onclick="generateBoletos()">📄 Gerar Boletos</button>
            <button class="btn btn-primary" onclick="downloadFinancialReport()">📊 Relatório PDF</button>
        </div>
    </div>

    <?php if (empty($financialData['payment_schedule'])): ?>
    <div class="empty-state">
        <div class="empty-icon">💳</div>
        <h3>Nenhum pagamento programado</h3>
        <p>Você ainda não possui contratos ativos com pagamentos programados.</p>
        <a href="app.php?url=/client/vendors" class="btn btn-primary">Explorar Fornecedores</a>
    </div>
    <?php else: ?>

    <div class="payment-timeline">
        <?php
        $today = new DateTime();
        foreach ($financialData['payment_schedule'] as $index => $payment):
            $downPaymentDate = new DateTime($payment['down_payment_date']);
            $finalPaymentDate = new DateTime($payment['final_payment_date']);
            $isDownPaymentOverdue = $downPaymentDate < $today;
            $isFinalPaymentOverdue = $finalPaymentDate < $today;
        ?>

        <div class="payment-group">
            <div class="payment-vendor">
                <h3><?= htmlspecialchars($payment['business_name']) ?></h3>
                <span class="payment-total">Total: R$ <?= number_format($payment['down_payment'] + $payment['final_payment'], 2, ',', '.') ?></span>
            </div>

            <div class="payment-installments">
                <!-- Down Payment -->
                <div class="payment-item <?= $isDownPaymentOverdue ? 'overdue' : 'upcoming' ?>">
                    <div class="payment-info">
                        <div class="payment-type">Sinal (40%)</div>
                        <div class="payment-amount">R$ <?= number_format($payment['down_payment'], 2, ',', '.') ?></div>
                        <div class="payment-date">Vencimento: <?= date('d/m/Y', strtotime($payment['down_payment_date'])) ?></div>
                    </div>

                    <div class="payment-status">
                        <?php if ($isDownPaymentOverdue): ?>
                        <span class="status-badge overdue">Em Atraso</span>
                        <?php else: ?>
                        <span class="status-badge pending">Pendente</span>
                        <?php endif; ?>
                    </div>

                    <div class="payment-actions">
                        <button class="btn btn-sm btn-primary" onclick="payInstallment(<?= $payment['contract_id'] ?>, 'down')">
                            💳 Pagar Agora
                        </button>
                        <button class="btn btn-sm btn-secondary" onclick="generateBoleto(<?= $payment['contract_id'] ?>, 'down')">
                            📄 Boleto
                        </button>
                    </div>
                </div>

                <!-- Final Payment -->
                <div class="payment-item <?= $isFinalPaymentOverdue ? 'overdue' : 'upcoming' ?>">
                    <div class="payment-info">
                        <div class="payment-type">Restante (60%)</div>
                        <div class="payment-amount">R$ <?= number_format($payment['final_payment'], 2, ',', '.') ?></div>
                        <div class="payment-date">Vencimento: <?= date('d/m/Y', strtotime($payment['final_payment_date'])) ?></div>
                    </div>

                    <div class="payment-status">
                        <?php if ($isFinalPaymentOverdue): ?>
                        <span class="status-badge overdue">Em Atraso</span>
                        <?php else: ?>
                        <span class="status-badge pending">Pendente</span>
                        <?php endif; ?>
                    </div>

                    <div class="payment-actions">
                        <button class="btn btn-sm btn-primary" onclick="payInstallment(<?= $payment['contract_id'] ?>, 'final')">
                            💳 Pagar Agora
                        </button>
                        <button class="btn btn-sm btn-secondary" onclick="generateBoleto(<?= $payment['contract_id'] ?>, 'final')">
                            📄 Boleto
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Contracts Financial Details -->
<div class="content-section">
    <div class="section-header">
        <h2 class="section-title">Detalhes por Contrato</h2>
    </div>

    <div class="contracts-financial">
        <?php foreach ($financialData['contracts'] as $contract): ?>
        <div class="contract-financial-card">
            <div class="contract-header">
                <div class="contract-info">
                    <h3 class="contract-vendor"><?= htmlspecialchars($contract['business_name'] ?? 'Fornecedor') ?></h3>
                    <span class="contract-category"><?= htmlspecialchars($contract['category'] ?? 'Serviço') ?></span>
                </div>
                <div class="contract-value">
                    <span class="value-amount">R$ <?= number_format($contract['total_value'], 2, ',', '.') ?></span>
                    <span class="value-status status-<?= $contract['status'] ?>">
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

            <div class="contract-breakdown">
                <div class="breakdown-row">
                    <span class="breakdown-label">Valor Base:</span>
                    <span class="breakdown-value">R$ <?= number_format($contract['total_value'], 2, ',', '.') ?></span>
                </div>
                <div class="breakdown-row">
                    <span class="breakdown-label">Taxa Clea (<?= ($contract['commission_rate'] * 100) ?>%):</span>
                    <span class="breakdown-value">R$ <?= number_format($contract['total_value'] * $contract['commission_rate'], 2, ',', '.') ?></span>
                </div>
                <div class="breakdown-row total">
                    <span class="breakdown-label">Total a Pagar:</span>
                    <span class="breakdown-value">R$ <?= number_format($contract['total_value'], 2, ',', '.') ?></span>
                </div>
            </div>

            <div class="contract-actions">
                <button class="btn btn-sm btn-secondary" onclick="viewContractDetails(<?= $contract['id'] ?>)">
                    📋 Ver Contrato
                </button>
                <button class="btn btn-sm btn-info" onclick="viewPaymentHistory(<?= $contract['id'] ?>)">
                    📊 Histórico
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Budget Planning -->
<div class="content-section">
    <div class="section-header">
        <h2 class="section-title">Planejamento Orçamentário</h2>
        <button class="btn btn-primary" onclick="openBudgetPlanner()">✏️ Editar Orçamento</button>
    </div>

    <div class="budget-planner">
        <div class="budget-chart">
            <canvas id="budgetChart" width="400" height="200"></canvas>
        </div>

        <div class="budget-categories">
            <?php
            $categories = [
                ['name' => 'Fotografia', 'budgeted' => 4000, 'spent' => 3500, 'color' => '#3b82f6'],
                ['name' => 'Buffet', 'budgeted' => 15000, 'spent' => 12000, 'color' => '#10b981'],
                ['name' => 'Decoração', 'budgeted' => 5000, 'spent' => 0, 'color' => '#f59e0b'],
                ['name' => 'Música', 'budgeted' => 3000, 'spent' => 0, 'color' => '#ef4444'],
                ['name' => 'Outros', 'budgeted' => 3000, 'spent' => 0, 'color' => '#8b5cf6']
            ];

            foreach ($categories as $category):
                $percentage = $category['budgeted'] > 0 ? ($category['spent'] / $category['budgeted']) * 100 : 0;
            ?>
            <div class="budget-category">
                <div class="category-header">
                    <div class="category-name" style="border-left: 4px solid <?= $category['color'] ?>;">
                        <?= $category['name'] ?>
                    </div>
                    <div class="category-values">
                        <span class="spent">R$ <?= number_format($category['spent'], 0, ',', '.') ?></span>
                        <span class="budgeted">/ R$ <?= number_format($category['budgeted'], 0, ',', '.') ?></span>
                    </div>
                </div>
                <div class="category-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?= min($percentage, 100) ?>%; background-color: <?= $category['color'] ?>;"></div>
                    </div>
                    <span class="progress-percentage"><?= round($percentage) ?>%</span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Realizar Pagamento</h3>
            <button class="modal-close" onclick="closePaymentModal()">&times;</button>
        </div>

        <div class="modal-body">
            <div class="payment-details">
                <div class="payment-summary">
                    <h4 id="paymentVendorName">Fornecedor</h4>
                    <div class="payment-amount-large">R$ <span id="paymentAmount">0,00</span></div>
                    <div class="payment-type-label" id="paymentTypeLabel">Tipo de Pagamento</div>
                </div>

                <div class="payment-methods">
                    <h4>Escolha a forma de pagamento:</h4>

                    <div class="payment-method" onclick="selectPaymentMethod('credit')">
                        <div class="method-icon">💳</div>
                        <div class="method-info">
                            <div class="method-name">Cartão de Crédito</div>
                            <div class="method-description">Até 12x sem juros</div>
                        </div>
                        <input type="radio" name="payment_method" value="credit">
                    </div>

                    <div class="payment-method" onclick="selectPaymentMethod('debit')">
                        <div class="method-icon">💳</div>
                        <div class="method-info">
                            <div class="method-name">Cartão de Débito</div>
                            <div class="method-description">Débito em conta</div>
                        </div>
                        <input type="radio" name="payment_method" value="debit">
                    </div>

                    <div class="payment-method" onclick="selectPaymentMethod('pix')">
                        <div class="method-icon">📱</div>
                        <div class="method-info">
                            <div class="method-name">PIX</div>
                            <div class="method-description">Pagamento instantâneo</div>
                        </div>
                        <input type="radio" name="payment_method" value="pix">
                    </div>

                    <div class="payment-method" onclick="selectPaymentMethod('boleto')">
                        <div class="method-icon">📄</div>
                        <div class="method-info">
                            <div class="method-name">Boleto Bancário</div>
                            <div class="method-description">Vencimento em 3 dias úteis</div>
                        </div>
                        <input type="radio" name="payment_method" value="boleto">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closePaymentModal()">Cancelar</button>
            <button class="btn btn-primary" id="proceedPaymentBtn" disabled onclick="proceedPayment()">
                Prosseguir com Pagamento
            </button>
        </div>
    </div>
</div>

<style>
.financial-header {
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

.budget-summary {
    display: flex;
    align-items: center;
}

.budget-circle {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    width: 140px;
    height: 140px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.budget-amount {
    font-size: 20px;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 4px;
}

.budget-label {
    font-size: 12px;
    opacity: 0.9;
}

.financial-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.stat-card {
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

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    font-size: 32px;
    flex-shrink: 0;
}

.stat-value {
    font-size: 20px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.stat-label {
    font-size: 14px;
    color: var(--text-primary);
    font-weight: 500;
}

.stat-sublabel {
    font-size: 12px;
    color: var(--text-secondary);
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

.payment-timeline {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.payment-group {
    background: white;
    border-radius: 16px;
    border: 1px solid rgba(242, 171, 177, 0.2);
    overflow: hidden;
}

.payment-vendor {
    padding: 20px 24px;
    background: var(--primary-light);
    border-bottom: 1px solid rgba(242, 171, 177, 0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.payment-vendor h3 {
    font-family: 'Lora', serif;
    color: var(--text-primary);
    margin: 0;
}

.payment-total {
    font-weight: 600;
    color: var(--primary-dark);
}

.payment-installments {
    padding: 20px 24px;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.payment-item {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 16px;
    border-radius: 12px;
    border: 1px solid rgba(242, 171, 177, 0.2);
    background: var(--card-bg);
}

.payment-item.overdue {
    border-color: var(--error);
    background: rgba(239, 68, 68, 0.05);
}

.payment-info {
    flex: 1;
}

.payment-type {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.payment-amount {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary-dark);
    margin-bottom: 4px;
}

.payment-date {
    font-size: 12px;
    color: var(--text-secondary);
}

.payment-status {
    flex-shrink: 0;
}

.payment-actions {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

.btn-sm {
    padding: 6px 12px;
    font-size: 12px;
}

.contracts-financial {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}

.contract-financial-card {
    background: white;
    border-radius: 16px;
    border: 1px solid rgba(242, 171, 177, 0.2);
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(101, 41, 41, 0.08);
}

.contract-header {
    padding: 20px;
    background: var(--primary-light);
    border-bottom: 1px solid rgba(242, 171, 177, 0.2);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.contract-vendor {
    font-family: 'Lora', serif;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.contract-category {
    font-size: 12px;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.value-amount {
    font-size: 18px;
    font-weight: 700;
    color: var(--primary-dark);
    display: block;
}

.value-status {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 4px;
}

.contract-breakdown {
    padding: 20px;
}

.breakdown-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid rgba(242, 171, 177, 0.1);
}

.breakdown-row.total {
    border-bottom: none;
    margin-top: 8px;
    padding-top: 12px;
    border-top: 2px solid rgba(242, 171, 177, 0.3);
    font-weight: 600;
}

.breakdown-label {
    color: var(--text-secondary);
    font-size: 14px;
}

.breakdown-value {
    color: var(--text-primary);
    font-weight: 500;
}

.contract-actions {
    padding: 16px 20px;
    background: rgba(242, 171, 177, 0.05);
    border-top: 1px solid rgba(242, 171, 177, 0.2);
    display: flex;
    gap: 8px;
}

.budget-planner {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
    align-items: start;
}

.budget-chart {
    background: white;
    padding: 24px;
    border-radius: 12px;
    border: 1px solid rgba(242, 171, 177, 0.2);
    text-align: center;
}

.budget-categories {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.budget-category {
    background: white;
    padding: 16px;
    border-radius: 12px;
    border: 1px solid rgba(242, 171, 177, 0.2);
}

.category-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.category-name {
    font-weight: 500;
    color: var(--text-primary);
    padding-left: 12px;
    border-left-width: 4px;
    border-left-style: solid;
}

.category-values {
    font-size: 14px;
}

.spent {
    font-weight: 600;
    color: var(--text-primary);
}

.budgeted {
    color: var(--text-secondary);
}

.category-progress {
    display: flex;
    align-items: center;
    gap: 12px;
}

.progress-bar {
    flex: 1;
    height: 8px;
    background: rgba(242, 171, 177, 0.2);
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s;
}

.progress-percentage {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-primary);
    min-width: 35px;
    text-align: right;
}

/* Payment Modal */
.payment-details {
    text-align: center;
}

.payment-summary {
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1px solid rgba(242, 171, 177, 0.2);
}

.payment-summary h4 {
    color: var(--text-primary);
    margin-bottom: 8px;
}

.payment-amount-large {
    font-size: 32px;
    font-weight: 700;
    color: var(--primary-dark);
    margin-bottom: 8px;
}

.payment-type-label {
    color: var(--text-secondary);
    font-size: 14px;
}

.payment-methods {
    text-align: left;
}

.payment-methods h4 {
    margin-bottom: 16px;
    color: var(--text-primary);
}

.payment-method {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    border: 2px solid rgba(242, 171, 177, 0.2);
    border-radius: 12px;
    margin-bottom: 12px;
    cursor: pointer;
    transition: all 0.3s;
}

.payment-method:hover,
.payment-method.selected {
    border-color: var(--primary-medium);
    background: rgba(242, 171, 177, 0.05);
}

.method-icon {
    font-size: 24px;
    flex-shrink: 0;
}

.method-info {
    flex: 1;
}

.method-name {
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.method-description {
    font-size: 12px;
    color: var(--text-secondary);
}

.payment-method input[type="radio"] {
    margin-left: auto;
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
    max-width: 600px;
    border-radius: 16px;
    overflow: hidden;
    max-height: 80vh;
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

.modal-footer {
    padding: 20px 24px;
    border-top: 1px solid rgba(242, 171, 177, 0.2);
    display: flex;
    gap: 12px;
    justify-content: flex-end;
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        text-align: center;
        gap: 20px;
    }

    .financial-stats {
        grid-template-columns: 1fr 1fr;
    }

    .payment-item {
        flex-direction: column;
        align-items: stretch;
        gap: 12px;
    }

    .payment-actions {
        justify-content: center;
    }

    .budget-planner {
        grid-template-columns: 1fr;
    }

    .contracts-financial {
        grid-template-columns: 1fr;
    }

    .modal-content {
        margin: 10% auto;
        width: 95%;
    }
}
</style>

<script>
// Financial management functions
function payInstallment(contractId, type) {
    // Mock data - in real app, this would come from database
    const mockData = {
        vendorName: type === 'down' ? 'Foto Studio Premium' : 'Buffet Elegance',
        amount: type === 'down' ? '1,400.00' : '7,200.00',
        typeLabel: type === 'down' ? 'Sinal (40%)' : 'Restante (60%)'
    };

    document.getElementById('paymentVendorName').textContent = mockData.vendorName;
    document.getElementById('paymentAmount').textContent = mockData.amount;
    document.getElementById('paymentTypeLabel').textContent = mockData.typeLabel;

    // Reset payment method selection
    document.querySelectorAll('.payment-method').forEach(method => {
        method.classList.remove('selected');
    });
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.checked = false;
    });
    document.getElementById('proceedPaymentBtn').disabled = true;

    document.getElementById('paymentModal').style.display = 'block';
}

function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
}

function selectPaymentMethod(method) {
    // Remove previous selection
    document.querySelectorAll('.payment-method').forEach(m => m.classList.remove('selected'));

    // Add selection to clicked method
    event.currentTarget.classList.add('selected');
    event.currentTarget.querySelector('input[type="radio"]').checked = true;

    // Enable proceed button
    document.getElementById('proceedPaymentBtn').disabled = false;
}

function proceedPayment() {
    const selectedMethod = document.querySelector('input[name="payment_method"]:checked').value;

    alert(`Redirecionando para pagamento via ${selectedMethod}...`);
    // In real app, this would redirect to payment gateway
    closePaymentModal();
}

function generateBoleto(contractId, type) {
    alert(`Gerando boleto para contrato ${contractId} - ${type}...`);
    // In real app, this would generate and download boleto
}

function generateBoletos() {
    alert('Gerando todos os boletos pendentes...');
    // In real app, this would generate all pending boletos
}

function downloadFinancialReport() {
    alert('Gerando relatório financeiro em PDF...');
    // In real app, this would generate and download PDF report
}

function viewContractDetails(contractId) {
    window.location.href = `app.php?url=/client/contracts&contract=${contractId}`;
}

function viewPaymentHistory(contractId) {
    alert(`Visualizar histórico de pagamentos do contrato ${contractId}`);
    // In real app, this would show payment history modal
}

function openBudgetPlanner() {
    alert('Abrir planejador de orçamento...');
    // In real app, this would open budget planning interface
}

// Simple budget chart using Canvas
window.addEventListener('load', function() {
    const canvas = document.getElementById('budgetChart');
    if (canvas) {
        const ctx = canvas.getContext('2d');

        // Simple pie chart
        const data = [
            {label: 'Fotografia', value: 3500, color: '#3b82f6'},
            {label: 'Buffet', value: 12000, color: '#10b981'},
            {label: 'Outros', value: 4500, color: '#f59e0b'}
        ];

        const total = data.reduce((sum, item) => sum + item.value, 0);
        let currentAngle = -Math.PI / 2;

        const centerX = canvas.width / 2;
        const centerY = canvas.height / 2;
        const radius = Math.min(centerX, centerY) - 20;

        data.forEach(item => {
            const sliceAngle = (item.value / total) * 2 * Math.PI;

            ctx.beginPath();
            ctx.moveTo(centerX, centerY);
            ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
            ctx.closePath();
            ctx.fillStyle = item.color;
            ctx.fill();

            currentAngle += sliceAngle;
        });

        // Add center circle for donut effect
        ctx.beginPath();
        ctx.arc(centerX, centerY, radius * 0.6, 0, 2 * Math.PI);
        ctx.fillStyle = 'white';
        ctx.fill();

        // Add total in center
        ctx.fillStyle = '#652929';
        ctx.font = 'bold 16px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('R$ ' + total.toLocaleString('pt-BR'), centerX, centerY - 5);
        ctx.font = '12px Arial';
        ctx.fillText('Total Gasto', centerX, centerY + 15);
    }
});

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const paymentModal = document.getElementById('paymentModal');
    if (event.target === paymentModal) {
        closePaymentModal();
    }
});
</script>

<?php
$content = ob_get_clean();
include '../app/Views/layouts/dashboard.php';
?>