<?php
$sidebarTitle = 'Portal do Cliente';
$pageTitle = 'Explorar Fornecedores';

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
    <a href="app.php?url=/client/vendors" class="nav-item active">
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

<!-- Header with Search and Filters -->
<div class="vendors-header">
    <div class="header-content">
        <div class="header-text">
            <h1 class="header-title">Fornecedores Curados</h1>
            <p class="header-subtitle">Descubra profissionais selecionados especialmente para casamentos minimalistas</p>
        </div>

        <div class="header-stats">
            <div class="stat-item">
                <span class="stat-number"><?= count($availableVendors) ?></span>
                <span class="stat-label">Fornecedores disponíveis</span>
            </div>
            <div class="stat-item">
                <span class="stat-number"><?= count($contractedVendors) ?></span>
                <span class="stat-label">Já contratados</span>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Controls -->
<div class="vendors-controls">
    <div class="search-container">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Buscar por nome, categoria ou cidade..." class="search-input">
            <button class="search-btn">🔍</button>
        </div>
    </div>

    <div class="filter-container">
        <div class="filter-group">
            <label class="filter-label">Categoria:</label>
            <select id="categoryFilter" class="filter-select">
                <option value="">Todas as categorias</option>
                <option value="Fotografia">📸 Fotografia</option>
                <option value="Buffet">🍽️ Buffet</option>
                <option value="Música">🎵 Música</option>
                <option value="Decoração">🌸 Decoração</option>
                <option value="Confeitaria">🎂 Confeitaria</option>
                <option value="Cerimonial">💐 Cerimonial</option>
                <option value="Vestidos">👗 Vestidos</option>
                <option value="Transporte">🚗 Transporte</option>
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label">Localização:</label>
            <select id="locationFilter" class="filter-select">
                <option value="">Todas as cidades</option>
                <option value="São Paulo">São Paulo</option>
                <option value="Rio de Janeiro">Rio de Janeiro</option>
                <option value="Belo Horizonte">Belo Horizonte</option>
                <option value="Campinas">Campinas</option>
            </select>
        </div>

        <div class="filter-group">
            <label class="filter-label">Status:</label>
            <select id="statusFilter" class="filter-select">
                <option value="all">Todos</option>
                <option value="available">Disponíveis</option>
                <option value="contracted">Contratados</option>
            </select>
        </div>

        <button class="filter-reset" onclick="resetFilters()">Limpar Filtros</button>
    </div>
</div>

<!-- Contracted Vendors Section -->
<?php if (!empty($contractedVendors)): ?>
<div class="content-section">
    <div class="section-header">
        <h2 class="section-title">Seus Fornecedores Contratados</h2>
        <span class="section-badge"><?= count($contractedVendors) ?> contratados</span>
    </div>

    <div class="vendors-grid contracted-vendors">
        <?php foreach ($contractedVendors as $vendor): ?>
        <div class="vendor-card contracted" data-vendor-id="<?= $vendor['vendor_user_id'] ?>">
            <div class="vendor-status-badge">✅ Contratado</div>

            <div class="vendor-image">
                <img src="<?= $vendor['profile_image_url'] ?: 'https://via.placeholder.com/300x200?text=' . urlencode($vendor['business_name']) ?>"
                     alt="<?= htmlspecialchars($vendor['business_name']) ?>"
                     loading="lazy">
                <div class="vendor-category"><?= htmlspecialchars($vendor['category']) ?></div>
            </div>

            <div class="vendor-content">
                <h3 class="vendor-name"><?= htmlspecialchars($vendor['business_name']) ?></h3>
                <p class="vendor-description"><?= htmlspecialchars(substr($vendor['description'], 0, 100)) ?>...</p>

                <div class="vendor-meta">
                    <span class="vendor-location">📍 São Paulo, SP</span>
                    <span class="vendor-rating">⭐ 4.9 (89 avaliações)</span>
                </div>

                <div class="vendor-contract-info">
                    <div class="contract-value">R$ <?= number_format($vendor['total_value'], 2, ',', '.') ?></div>
                    <div class="contract-status status-<?= $vendor['status'] ?>">
                        <?= ucfirst(str_replace('_', ' ', $vendor['status'])) ?>
                    </div>
                </div>
            </div>

            <div class="vendor-actions">
                <button class="btn btn-secondary" onclick="viewContract(<?= $vendor['id'] ?>)">Ver Contrato</button>
                <button class="btn btn-primary" onclick="sendMessage(<?= $vendor['vendor_user_id'] ?>)">💬 Mensagem</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Available Vendors Section -->
<div class="content-section">
    <div class="section-header">
        <h2 class="section-title">Fornecedores Disponíveis</h2>
        <div class="view-options">
            <button class="view-btn active" data-view="grid" onclick="setViewMode('grid')">⚏ Grade</button>
            <button class="view-btn" data-view="list" onclick="setViewMode('list')">☰ Lista</button>
        </div>
    </div>

    <div class="vendors-grid" id="vendorsContainer">
        <?php foreach ($availableVendors as $vendor): ?>
        <?php
        // Check if vendor is already contracted
        $isContracted = false;
        foreach ($contractedVendors as $contracted) {
            if ($contracted['vendor_user_id'] == $vendor['id']) {
                $isContracted = true;
                break;
            }
        }
        ?>

        <div class="vendor-card <?= $isContracted ? 'contracted' : 'available' ?>"
             data-vendor-id="<?= $vendor['id'] ?>"
             data-category="<?= htmlspecialchars($vendor['category']) ?>"
             data-location="<?= htmlspecialchars($vendor['city']) ?>"
             data-status="<?= $isContracted ? 'contracted' : 'available' ?>">

            <?php if ($isContracted): ?>
            <div class="vendor-status-badge">✅ Já contratado</div>
            <?php endif; ?>

            <div class="vendor-image">
                <img src="<?= $vendor['profile_image_url'] ?: 'https://via.placeholder.com/300x200?text=' . urlencode($vendor['business_name']) ?>"
                     alt="<?= htmlspecialchars($vendor['business_name']) ?>"
                     loading="lazy">
                <div class="vendor-category"><?= htmlspecialchars($vendor['category']) ?></div>
                <button class="vendor-favorite" onclick="toggleFavorite(<?= $vendor['id'] ?>)">♡</button>
            </div>

            <div class="vendor-content">
                <h3 class="vendor-name"><?= htmlspecialchars($vendor['business_name']) ?></h3>
                <p class="vendor-description"><?= htmlspecialchars(substr($vendor['description'], 0, 120)) ?>...</p>

                <div class="vendor-meta">
                    <span class="vendor-location">📍 <?= htmlspecialchars($vendor['city']) ?>, <?= htmlspecialchars($vendor['state']) ?></span>
                    <span class="vendor-rating">⭐ 4.<?= rand(7,9) ?> (<?= rand(45,150) ?> avaliações)</span>
                </div>

                <div class="vendor-pricing">
                    <div class="price-range">A partir de R$ <?= number_format(rand(800, 5000), 0, ',', '.') ?></div>
                    <div class="price-note">Pacotes personalizáveis</div>
                </div>
            </div>

            <div class="vendor-actions">
                <button class="btn btn-secondary" onclick="viewVendorDetails(<?= $vendor['id'] ?>)">Ver Detalhes</button>
                <?php if (!$isContracted): ?>
                <button class="btn btn-primary" onclick="requestQuote(<?= $vendor['id'] ?>)">Solicitar Orçamento</button>
                <?php else: ?>
                <button class="btn btn-primary" onclick="sendMessage(<?= $vendor['id'] ?>)">💬 Conversar</button>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Load More Button -->
    <div class="load-more-container">
        <button class="btn btn-secondary load-more-btn">Carregar Mais Fornecedores</button>
    </div>
</div>

<!-- Vendor Details Modal -->
<div id="vendorModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalVendorName"></h3>
            <button class="modal-close" onclick="closeVendorModal()">&times;</button>
        </div>

        <div class="modal-body">
            <div class="vendor-detail-image">
                <img id="modalVendorImage" src="" alt="">
            </div>

            <div class="vendor-detail-info">
                <div class="detail-section">
                    <h4>Sobre</h4>
                    <p id="modalVendorDescription"></p>
                </div>

                <div class="detail-section">
                    <h4>Especialidades</h4>
                    <div id="modalVendorSpecialties" class="specialties-list"></div>
                </div>

                <div class="detail-section">
                    <h4>Pacotes</h4>
                    <div id="modalVendorPackages" class="packages-list"></div>
                </div>

                <div class="detail-section">
                    <h4>Galeria</h4>
                    <div id="modalVendorGallery" class="gallery-grid"></div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeVendorModal()">Fechar</button>
            <button class="btn btn-primary" id="modalActionBtn">Solicitar Orçamento</button>
        </div>
    </div>
</div>

<style>
.vendors-header {
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

.vendors-controls {
    background: white;
    padding: 24px;
    border-radius: 16px;
    border: 1px solid rgba(242, 171, 177, 0.2);
    margin-bottom: 32px;
    box-shadow: 0 4px 12px rgba(101, 41, 41, 0.08);
}

.search-container {
    margin-bottom: 20px;
}

.search-box {
    position: relative;
    max-width: 500px;
}

.search-input {
    width: 100%;
    padding: 12px 50px 12px 16px;
    border: 2px solid rgba(242, 171, 177, 0.3);
    border-radius: 12px;
    font-size: 16px;
    transition: all 0.3s;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary-medium);
    box-shadow: 0 0 0 3px rgba(242, 171, 177, 0.1);
}

.search-btn {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: background 0.3s;
}

.search-btn:hover {
    background: rgba(242, 171, 177, 0.1);
}

.filter-container {
    display: flex;
    gap: 20px;
    align-items: end;
    flex-wrap: wrap;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.filter-label {
    font-size: 12px;
    font-weight: 500;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-select {
    padding: 8px 12px;
    border: 1px solid rgba(242, 171, 177, 0.3);
    border-radius: 8px;
    background: white;
    font-size: 14px;
    min-width: 150px;
    cursor: pointer;
}

.filter-reset {
    padding: 8px 16px;
    background: rgba(242, 171, 177, 0.1);
    border: 1px solid rgba(242, 171, 177, 0.3);
    border-radius: 8px;
    color: var(--text-primary);
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
}

.filter-reset:hover {
    background: var(--primary-medium);
    color: white;
}

.section-badge {
    background: var(--primary-medium);
    color: white;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.view-options {
    display: flex;
    gap: 8px;
}

.view-btn {
    padding: 8px 12px;
    border: 1px solid rgba(242, 171, 177, 0.3);
    background: white;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s;
}

.view-btn.active,
.view-btn:hover {
    background: var(--primary-medium);
    color: white;
    border-color: var(--primary-medium);
}

.vendors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.vendors-grid.list-view {
    grid-template-columns: 1fr;
}

.vendors-grid.list-view .vendor-card {
    display: flex;
    align-items: center;
    padding: 20px;
}

.vendors-grid.list-view .vendor-image {
    width: 120px;
    height: 80px;
    margin-right: 20px;
    flex-shrink: 0;
}

.vendors-grid.list-view .vendor-content {
    flex: 1;
    margin: 0 20px 0 0;
}

.vendors-grid.list-view .vendor-actions {
    flex-direction: row;
    gap: 12px;
}

.vendor-card {
    background: white;
    border-radius: 16px;
    border: 1px solid rgba(242, 171, 177, 0.2);
    overflow: hidden;
    transition: all 0.3s;
    position: relative;
    box-shadow: 0 4px 12px rgba(101, 41, 41, 0.08);
}

.vendor-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(101, 41, 41, 0.15);
}

.vendor-card.contracted {
    border-color: var(--success);
    background: rgba(34, 197, 94, 0.02);
}

.vendor-status-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: var(--success);
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
    z-index: 2;
}

.vendor-image {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: hidden;
}

.vendor-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.vendor-card:hover .vendor-image img {
    transform: scale(1.05);
}

.vendor-category {
    position: absolute;
    bottom: 12px;
    left: 12px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 4px 8px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
}

.vendor-favorite {
    position: absolute;
    top: 12px;
    left: 12px;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.3s;
    z-index: 2;
}

.vendor-favorite:hover,
.vendor-favorite.active {
    background: var(--error);
    color: white;
}

.vendor-content {
    padding: 20px;
}

.vendor-name {
    font-family: 'Lora', serif;
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 8px;
}

.vendor-description {
    color: var(--text-secondary);
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 16px;
}

.vendor-meta {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-bottom: 16px;
}

.vendor-location,
.vendor-rating {
    font-size: 12px;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 4px;
}

.vendor-pricing {
    margin-bottom: 16px;
}

.price-range {
    font-weight: 600;
    color: var(--primary-dark);
    margin-bottom: 4px;
}

.price-note {
    font-size: 12px;
    color: var(--text-secondary);
}

.vendor-contract-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    padding: 12px;
    background: rgba(242, 171, 177, 0.05);
    border-radius: 8px;
}

.contract-value {
    font-weight: 600;
    color: var(--primary-dark);
    font-size: 16px;
}

.contract-status {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-active {
    background: rgba(34, 197, 94, 0.1);
    color: var(--success);
}

.status-pending_signature {
    background: rgba(245, 158, 11, 0.1);
    color: var(--warning);
}

.vendor-actions {
    padding: 0 20px 20px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.load-more-container {
    text-align: center;
    margin-top: 32px;
}

.load-more-btn {
    padding: 12px 32px;
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
    max-width: 800px;
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

.vendor-detail-image {
    width: 100%;
    height: 200px;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 24px;
}

.vendor-detail-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.detail-section {
    margin-bottom: 24px;
}

.detail-section h4 {
    font-family: 'Lora', serif;
    color: var(--text-primary);
    margin-bottom: 12px;
    font-size: 18px;
}

.specialties-list,
.packages-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.specialty-tag,
.package-item {
    background: rgba(242, 171, 177, 0.1);
    color: var(--text-primary);
    padding: 6px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 12px;
}

.gallery-item {
    width: 100%;
    height: 100px;
    border-radius: 8px;
    overflow: hidden;
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
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

    .filter-container {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-group {
        width: 100%;
    }

    .vendors-grid {
        grid-template-columns: 1fr;
    }

    .modal-content {
        margin: 10% auto;
        width: 95%;
    }
}
</style>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    filterVendors();
});

// Filter functionality
document.getElementById('categoryFilter').addEventListener('change', filterVendors);
document.getElementById('locationFilter').addEventListener('change', filterVendors);
document.getElementById('statusFilter').addEventListener('change', filterVendors);

function filterVendors() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value;
    const locationFilter = document.getElementById('locationFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;

    const vendorCards = document.querySelectorAll('.vendor-card');

    vendorCards.forEach(card => {
        const name = card.querySelector('.vendor-name').textContent.toLowerCase();
        const description = card.querySelector('.vendor-description').textContent.toLowerCase();
        const category = card.dataset.category;
        const location = card.dataset.location;
        const status = card.dataset.status;

        const matchesSearch = name.includes(searchTerm) ||
                            description.includes(searchTerm) ||
                            category.toLowerCase().includes(searchTerm);
        const matchesCategory = !categoryFilter || category === categoryFilter;
        const matchesLocation = !locationFilter || location === locationFilter;
        const matchesStatus = statusFilter === 'all' || status === statusFilter;

        if (matchesSearch && matchesCategory && matchesLocation && matchesStatus) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('categoryFilter').value = '';
    document.getElementById('locationFilter').value = '';
    document.getElementById('statusFilter').value = 'all';
    filterVendors();
}

// View mode toggle
function setViewMode(mode) {
    const container = document.getElementById('vendorsContainer');
    const buttons = document.querySelectorAll('.view-btn');

    buttons.forEach(btn => btn.classList.remove('active'));
    document.querySelector(`[data-view="${mode}"]`).classList.add('active');

    if (mode === 'list') {
        container.classList.add('list-view');
    } else {
        container.classList.remove('list-view');
    }
}

// Vendor actions
function toggleFavorite(vendorId) {
    const btn = document.querySelector(`[data-vendor-id="${vendorId}"] .vendor-favorite`);
    btn.classList.toggle('active');

    // In a real app, this would save to database
    console.log(`Toggled favorite for vendor ${vendorId}`);
}

function viewVendorDetails(vendorId) {
    // Mock data - in real app, this would come from an API call
    const mockData = {
        name: 'Foto Studio Premium',
        image: 'https://via.placeholder.com/800x300?text=Foto+Studio+Premium',
        description: 'Especialistas em fotografia de casamento minimalista com mais de 10 anos de experiência. Nossa abordagem discreta e elegante captura os momentos mais especiais do seu grande dia.',
        specialties: ['Fotografia de Casamento', 'Ensaio Pré-Wedding', 'Making Of', 'Drone', 'Álbum Digital'],
        packages: ['Básico - R$ 2.500', 'Completo - R$ 3.500', 'Premium - R$ 5.000'],
        gallery: [
            'https://via.placeholder.com/200x150?text=Foto+1',
            'https://via.placeholder.com/200x150?text=Foto+2',
            'https://via.placeholder.com/200x150?text=Foto+3',
            'https://via.placeholder.com/200x150?text=Foto+4'
        ]
    };

    // Populate modal
    document.getElementById('modalVendorName').textContent = mockData.name;
    document.getElementById('modalVendorImage').src = mockData.image;
    document.getElementById('modalVendorDescription').textContent = mockData.description;

    // Specialties
    const specialtiesContainer = document.getElementById('modalVendorSpecialties');
    specialtiesContainer.innerHTML = mockData.specialties.map(s =>
        `<span class="specialty-tag">${s}</span>`
    ).join('');

    // Packages
    const packagesContainer = document.getElementById('modalVendorPackages');
    packagesContainer.innerHTML = mockData.packages.map(p =>
        `<div class="package-item">${p}</div>`
    ).join('');

    // Gallery
    const galleryContainer = document.getElementById('modalVendorGallery');
    galleryContainer.innerHTML = mockData.gallery.map(img =>
        `<div class="gallery-item"><img src="${img}" alt="Trabalho do fornecedor"></div>`
    ).join('');

    // Show modal
    document.getElementById('vendorModal').style.display = 'block';
}

function closeVendorModal() {
    document.getElementById('vendorModal').style.display = 'none';
}

function requestQuote(vendorId) {
    alert(`Solicitar orçamento do fornecedor ${vendorId}`);
    // In real app, this would open a quote request form or redirect
}

function sendMessage(vendorId) {
    alert(`Enviar mensagem para fornecedor ${vendorId}`);
    // In real app, this would open chat or redirect to messages
}

function viewContract(contractId) {
    alert(`Ver contrato ${contractId}`);
    // In real app, this would show contract details
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('vendorModal');
    if (event.target === modal) {
        closeVendorModal();
    }
});
</script>

<?php
$content = ob_get_clean();
include '../app/Views/layouts/dashboard.php';
?>