<?php
$sidebarTitle = 'Portal do Cliente';
$pageTitle = 'Cronograma do Casamento';

$sidebarMenu = '
<div class="nav-section">
    <div class="nav-section-title">Planejamento</div>
    <a href="app.php?url=/client/dashboard" class="nav-item">
        <span class="nav-icon">💒</span>
        Meu Casamento
    </a>
    <a href="app.php?url=/client/wedding" class="nav-item active">
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

<!-- Wedding Info Header -->
<div class="wedding-header">
    <div class="wedding-info">
        <h1 class="wedding-title">
            <?= htmlspecialchars($wedding['partner_name'] ?? 'Nosso Casamento') ?>
            <span class="wedding-date"><?= $wedding ? date('d/m/Y', strtotime($wedding['wedding_date'])) : 'Data não definida' ?></span>
        </h1>
        <div class="wedding-details">
            <div class="detail-item">
                <span class="detail-icon">📍</span>
                <span class="detail-text"><?= htmlspecialchars($wedding['venue'] ?? 'Local não definido') ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-icon">👥</span>
                <span class="detail-text"><?= ($wedding['estimated_guests'] ?? 0) ?> convidados estimados</span>
            </div>
            <div class="detail-item">
                <span class="detail-icon">💰</span>
                <span class="detail-text">Orçamento: R$ <?= number_format($wedding['budget'] ?? 0, 2, ',', '.') ?></span>
            </div>
        </div>
    </div>

    <?php if ($wedding && $stats['days_until'] > 0): ?>
    <div class="countdown-card">
        <div class="countdown-number"><?= $stats['days_until'] ?></div>
        <div class="countdown-label">dias restantes</div>
        <div class="countdown-date">até <?= date('d/m/Y', strtotime($wedding['wedding_date'])) ?></div>
    </div>
    <?php endif; ?>
</div>

<!-- Timeline Navigation -->
<div class="timeline-nav">
    <div class="timeline-filters">
        <button class="filter-btn active" data-period="all">Todas as Fases</button>
        <button class="filter-btn" data-period="6months">6+ Meses Antes</button>
        <button class="filter-btn" data-period="3months">3-6 Meses Antes</button>
        <button class="filter-btn" data-period="1month">1-3 Meses Antes</button>
        <button class="filter-btn" data-period="final">Semana Final</button>
    </div>

    <div class="timeline-actions">
        <button class="btn btn-secondary" onclick="toggleCompletedTasks()">
            <span id="toggleText">Ocultar Concluídas</span>
        </button>
        <button class="btn btn-primary" onclick="addCustomTask()">
            + Adicionar Tarefa
        </button>
    </div>
</div>

<!-- Timeline Content -->
<div class="timeline-container">
    <?php foreach ($upcomingTasks as $index => $taskGroup): ?>
    <div class="timeline-phase" data-period="<?= $taskGroup['period'] ?>">
        <div class="phase-header">
            <div class="phase-title"><?= $taskGroup['title'] ?></div>
            <div class="phase-duration"><?= $taskGroup['duration'] ?></div>
            <div class="phase-progress">
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?= $taskGroup['completion'] ?>%"></div>
                </div>
                <span class="progress-text"><?= $taskGroup['completion'] ?>% concluído</span>
            </div>
        </div>

        <div class="phase-tasks">
            <?php foreach ($taskGroup['tasks'] as $task): ?>
            <div class="task-item <?= $task['completed'] ? 'completed' : '' ?>" data-priority="<?= $task['priority'] ?>">
                <div class="task-checkbox">
                    <input type="checkbox" id="task_<?= $task['id'] ?>" <?= $task['completed'] ? 'checked' : '' ?>
                           onchange="toggleTaskCompletion(<?= $task['id'] ?>)">
                    <label for="task_<?= $task['id'] ?>"></label>
                </div>

                <div class="task-content">
                    <div class="task-title"><?= htmlspecialchars($task['task']) ?></div>
                    <div class="task-meta">
                        <span class="task-date">
                            <span class="task-icon">📅</span>
                            <?= date('d/m/Y', strtotime($task['due_date'])) ?>
                        </span>
                        <span class="task-priority priority-<?= $task['priority'] ?>">
                            <?= ucfirst($task['priority']) ?>
                        </span>
                        <?php if (!empty($task['vendor'])): ?>
                        <span class="task-vendor">
                            <span class="task-icon">🏢</span>
                            <?= htmlspecialchars($task['vendor']) ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($task['description'])): ?>
                    <div class="task-description"><?= htmlspecialchars($task['description']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="task-actions">
                    <button class="task-action-btn" onclick="editTask(<?= $task['id'] ?>)" title="Editar">✏️</button>
                    <button class="task-action-btn" onclick="deleteTask(<?= $task['id'] ?>)" title="Excluir">🗑️</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Progress Overview -->
<div class="content-section">
    <div class="section-header">
        <h2 class="section-title">Resumo do Progresso</h2>
        <div class="progress-stats">
            <span class="stat-item">
                <strong><?= array_sum(array_column($upcomingTasks, 'completed_tasks')) ?></strong> concluídas
            </span>
            <span class="stat-item">
                <strong><?= array_sum(array_column($upcomingTasks, 'total_tasks')) - array_sum(array_column($upcomingTasks, 'completed_tasks')) ?></strong> pendentes
            </span>
        </div>
    </div>

    <div class="progress-overview">
        <?php
        $categories = [
            ['name' => 'Cerimônia', 'icon' => '💒', 'progress' => 90, 'color' => 'var(--success)'],
            ['name' => 'Festa', 'icon' => '🎉', 'progress' => 75, 'color' => 'var(--info)'],
            ['name' => 'Fotografia', 'icon' => '📸', 'progress' => 100, 'color' => 'var(--success)'],
            ['name' => 'Vestuário', 'icon' => '👗', 'progress' => 60, 'color' => 'var(--warning)'],
            ['name' => 'Convidados', 'icon' => '💌', 'progress' => 45, 'color' => 'var(--error)'],
            ['name' => 'Financeiro', 'icon' => '💰', 'progress' => 80, 'color' => 'var(--info)']
        ];

        foreach ($categories as $category): ?>
        <div class="category-progress">
            <div class="category-header">
                <span class="category-icon"><?= $category['icon'] ?></span>
                <span class="category-name"><?= $category['name'] ?></span>
                <span class="category-percentage"><?= $category['progress'] ?>%</span>
            </div>
            <div class="category-bar">
                <div class="category-fill" style="width: <?= $category['progress'] ?>%; background-color: <?= $category['color'] ?>"></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.wedding-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    background: linear-gradient(135deg, var(--primary-medium), var(--primary-dark));
    color: white;
    padding: 32px;
    border-radius: 16px;
    margin-bottom: 32px;
    box-shadow: 0 8px 32px rgba(101, 41, 41, 0.3);
}

.wedding-info {
    flex: 1;
}

.wedding-title {
    font-family: 'Lora', serif;
    font-size: 32px;
    margin-bottom: 8px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.wedding-date {
    font-size: 18px;
    font-weight: 400;
    opacity: 0.9;
}

.wedding-details {
    display: flex;
    gap: 24px;
    margin-top: 16px;
    flex-wrap: wrap;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    opacity: 0.9;
}

.countdown-card {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 24px;
    text-align: center;
    min-width: 160px;
}

.countdown-number {
    font-size: 48px;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 8px;
}

.countdown-label {
    font-size: 14px;
    opacity: 0.9;
    margin-bottom: 4px;
}

.countdown-date {
    font-size: 12px;
    opacity: 0.8;
}

.timeline-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    flex-wrap: wrap;
    gap: 16px;
}

.timeline-filters {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 8px 16px;
    border: 1px solid rgba(242, 171, 177, 0.3);
    background: white;
    color: var(--text-primary);
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s;
}

.filter-btn:hover,
.filter-btn.active {
    background: var(--primary-medium);
    color: white;
    border-color: var(--primary-medium);
}

.timeline-actions {
    display: flex;
    gap: 12px;
}

.timeline-container {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.timeline-phase {
    background: white;
    border-radius: 16px;
    border: 1px solid rgba(242, 171, 177, 0.2);
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(101, 41, 41, 0.08);
}

.phase-header {
    background: var(--primary-light);
    padding: 20px 24px;
    border-bottom: 1px solid rgba(242, 171, 177, 0.2);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.phase-title {
    font-family: 'Lora', serif;
    font-size: 20px;
    font-weight: 600;
    color: var(--text-primary);
}

.phase-duration {
    font-size: 14px;
    color: var(--text-secondary);
    font-weight: 500;
}

.phase-progress {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 200px;
}

.phase-progress .progress-bar {
    flex: 1;
    height: 8px;
    background: rgba(242, 171, 177, 0.2);
    border-radius: 4px;
    overflow: hidden;
}

.phase-progress .progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary-medium), var(--primary-dark));
    border-radius: 4px;
    transition: width 0.3s;
}

.progress-text {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
}

.phase-tasks {
    padding: 8px;
}

.task-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    margin: 8px 0;
    border-radius: 12px;
    border: 1px solid transparent;
    transition: all 0.3s;
    background: var(--card-bg);
}

.task-item:hover {
    border-color: rgba(242, 171, 177, 0.3);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(101, 41, 41, 0.1);
}

.task-item.completed {
    opacity: 0.6;
    background: rgba(242, 171, 177, 0.05);
}

.task-item.completed .task-title {
    text-decoration: line-through;
}

.task-checkbox {
    position: relative;
    flex-shrink: 0;
    margin-top: 2px;
}

.task-checkbox input[type="checkbox"] {
    width: 20px;
    height: 20px;
    border: 2px solid var(--primary-medium);
    border-radius: 4px;
    appearance: none;
    cursor: pointer;
    transition: all 0.3s;
}

.task-checkbox input[type="checkbox"]:checked {
    background: var(--primary-medium);
    border-color: var(--primary-medium);
}

.task-checkbox input[type="checkbox"]:checked::after {
    content: '✓';
    color: white;
    font-size: 12px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.task-content {
    flex: 1;
}

.task-title {
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 8px;
    line-height: 1.4;
}

.task-meta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    align-items: center;
}

.task-date,
.task-vendor {
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    color: var(--text-secondary);
}

.task-priority {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.priority-high {
    background: rgba(239, 68, 68, 0.1);
    color: var(--error);
}

.priority-medium {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
}

.priority-low {
    background: rgba(34, 197, 94, 0.1);
    color: var(--success);
}

.task-description {
    font-size: 12px;
    color: var(--text-secondary);
    margin-top: 4px;
    line-height: 1.4;
}

.task-actions {
    display: flex;
    gap: 4px;
    flex-shrink: 0;
    opacity: 0;
    transition: opacity 0.3s;
}

.task-item:hover .task-actions {
    opacity: 1;
}

.task-action-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: rgba(242, 171, 177, 0.1);
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: all 0.3s;
}

.task-action-btn:hover {
    background: var(--primary-medium);
    transform: scale(1.1);
}

.progress-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.category-progress {
    padding: 16px;
    background: var(--card-bg);
    border-radius: 12px;
    border: 1px solid rgba(242, 171, 177, 0.2);
}

.category-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.category-icon {
    font-size: 20px;
}

.category-name {
    flex: 1;
    font-weight: 500;
    color: var(--text-primary);
}

.category-percentage {
    font-weight: 600;
    color: var(--text-primary);
}

.category-bar {
    width: 100%;
    height: 8px;
    background: rgba(242, 171, 177, 0.2);
    border-radius: 4px;
    overflow: hidden;
}

.category-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s;
}

.progress-stats {
    display: flex;
    gap: 24px;
    align-items: center;
}

.stat-item {
    font-size: 14px;
    color: var(--text-secondary);
}

.stat-item strong {
    color: var(--text-primary);
}

/* Responsive */
@media (max-width: 768px) {
    .wedding-header {
        flex-direction: column;
        gap: 24px;
        text-align: center;
    }

    .wedding-details {
        justify-content: center;
    }

    .timeline-nav {
        flex-direction: column;
        align-items: stretch;
    }

    .phase-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .progress-overview {
        grid-template-columns: 1fr;
    }

    .task-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
}
</style>

<script>
// Filter timeline by period
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Update active button
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const period = this.dataset.period;

        // Show/hide phases
        document.querySelectorAll('.timeline-phase').forEach(phase => {
            if (period === 'all' || phase.dataset.period === period) {
                phase.style.display = 'block';
            } else {
                phase.style.display = 'none';
            }
        });
    });
});

// Toggle completed tasks visibility
let hideCompleted = false;
function toggleCompletedTasks() {
    hideCompleted = !hideCompleted;
    const toggleText = document.getElementById('toggleText');

    document.querySelectorAll('.task-item.completed').forEach(task => {
        task.style.display = hideCompleted ? 'none' : 'flex';
    });

    toggleText.textContent = hideCompleted ? 'Mostrar Concluídas' : 'Ocultar Concluídas';
}

// Task management functions
function toggleTaskCompletion(taskId) {
    const taskItem = document.querySelector(`#task_${taskId}`).closest('.task-item');
    const isChecked = document.querySelector(`#task_${taskId}`).checked;

    if (isChecked) {
        taskItem.classList.add('completed');
    } else {
        taskItem.classList.remove('completed');
    }

    // In a real app, this would send an AJAX request to update the database
    console.log(`Task ${taskId} completion status: ${isChecked}`);
}

function editTask(taskId) {
    // In a real app, this would open a modal or redirect to an edit page
    alert(`Editar tarefa ${taskId}`);
}

function deleteTask(taskId) {
    if (confirm('Tem certeza que deseja excluir esta tarefa?')) {
        const taskItem = document.querySelector(`#task_${taskId}`).closest('.task-item');
        taskItem.remove();

        // In a real app, this would send an AJAX request to delete from database
        console.log(`Task ${taskId} deleted`);
    }
}

function addCustomTask() {
    // In a real app, this would open a modal to add a new task
    alert('Adicionar nova tarefa personalizada');
}
</script>

<?php
$content = ob_get_clean();
include '../app/Views/layouts/dashboard.php';
?>