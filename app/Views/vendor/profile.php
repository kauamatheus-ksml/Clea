<?php
require_once ROOT_PATH . '/app/core/Auth.php';
use App\Core\Auth;

if (!Auth::isLoggedIn() || Auth::getUserRole() !== 'vendor') {
    header('Location: /public/login.php');
    exit();
}

$user = Auth::getUser();
$vendorProfile = $data['vendorProfile'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Perfil - Fornecedor - Clea Casamentos</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-container {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 30px;
            margin-bottom: 30px;
        }

        .profile-main {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--gray-200);
            padding: 30px;
        }

        .profile-sidebar {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--gray-200);
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            font-weight: 700;
            margin: 0 auto 20px;
            position: relative;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
        }

        .avatar-upload {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 36px;
            height: 36px;
            background: var(--white);
            border: 2px solid var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .avatar-upload:hover {
            background: var(--primary);
            color: white;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 8px;
        }

        .profile-category {
            display: inline-block;
            background: var(--primary-light);
            color: var(--primary);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .profile-status {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
        }

        .status-approved {
            color: var(--success);
        }

        .status-pending {
            color: var(--warning);
        }

        .form-section {
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: var(--gray-700);
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
            font-family: inherit;
        }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--gray-300);
            border-radius: 8px;
            font-size: 14px;
            background: var(--white);
        }

        .sidebar-card {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--gray-200);
            padding: 20px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stats-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .stats-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .stats-item:last-child {
            border-bottom: none;
        }

        .stats-label {
            color: var(--gray-600);
            font-size: 14px;
        }

        .stats-value {
            font-weight: 600;
            color: var(--gray-900);
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 10px;
            margin-bottom: 15px;
        }

        .gallery-item {
            aspect-ratio: 1;
            background: var(--gray-100);
            border-radius: 8px;
            border: 2px dashed var(--gray-300);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: var(--gray-500);
        }

        .gallery-item:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--gray-200);
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-outline {
            background: var(--white);
            color: var(--gray-700);
            border: 1px solid var(--gray-300);
        }

        .btn-outline:hover {
            background: var(--gray-50);
        }

        .approval-banner {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border: 1px solid #f59e0b;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .banner-content {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .banner-icon {
            color: #d97706;
            font-size: 20px;
        }

        .banner-text {
            flex: 1;
        }

        .banner-title {
            font-weight: 600;
            color: #92400e;
            margin-bottom: 4px;
        }

        .banner-description {
            font-size: 14px;
            color: #a16207;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            font-size: 14px;
            color: var(--gray-700);
        }

        .contact-item i {
            color: var(--primary);
            width: 16px;
        }

        @media (max-width: 768px) {
            .profile-container {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .btn-group {
                flex-direction: column;
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
                <a href="app.php?url=/vendor/financial"><i class="fas fa-dollar-sign"></i> Financeiro</a>
                <a href="app.php?url=/vendor/messages"><i class="fas fa-comments"></i> Mensagens</a>
                <a href="app.php?url=/vendor/profile" class="active"><i class="fas fa-user-edit"></i> Meu Perfil</a>
                <div class="sidebar-divider"></div>
                <a href="auth.php?action=logout"><i class="fas fa-sign-out-alt"></i> Sair</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-header">
                <h1><i class="fas fa-user-edit"></i> Meu Perfil</h1>
                <p>Gerencie suas informações públicas</p>
            </div>

            <?php if (!($vendorProfile['is_approved'] ?? false)): ?>
            <div class="approval-banner">
                <div class="banner-content">
                    <i class="fas fa-hourglass-half banner-icon"></i>
                    <div class="banner-text">
                        <div class="banner-title">Perfil em Análise</div>
                        <div class="banner-description">
                            Seu perfil está sendo analisado pela equipe Clea. Após a aprovação,
                            você aparecerá na curadoria para clientes.
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <form id="profileForm" onsubmit="updateProfile(event)">
                <div class="profile-container">
                    <!-- Main Profile Form -->
                    <div class="profile-main">
                        <!-- Profile Header -->
                        <div class="profile-header">
                            <div class="profile-avatar" onclick="document.getElementById('avatarUpload').click()">
                                <?= strtoupper(substr($vendorProfile['business_name'] ?? $user['name'], 0, 2)) ?>
                                <div class="avatar-upload">
                                    <i class="fas fa-camera"></i>
                                </div>
                            </div>
                            <input type="file" id="avatarUpload" accept="image/*" style="display: none;" onchange="handleAvatarUpload(event)">

                            <h2 class="profile-name"><?= htmlspecialchars($vendorProfile['business_name'] ?? $user['name']) ?></h2>
                            <div class="profile-category"><?= ucfirst($vendorProfile['category'] ?? 'Categoria') ?></div>
                            <div class="profile-status">
                                <i class="fas <?= ($vendorProfile['is_approved'] ?? false) ? 'fa-check-circle status-approved' : 'fa-clock status-pending' ?>"></i>
                                <?= ($vendorProfile['is_approved'] ?? false) ? 'Perfil Aprovado' : 'Aguardando Aprovação' ?>
                            </div>
                        </div>

                        <!-- Business Information -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-building"></i>
                                Informações do Negócio
                            </h3>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label" for="businessName">Nome do Negócio *</label>
                                    <input type="text" id="businessName" class="form-input"
                                           value="<?= htmlspecialchars($vendorProfile['business_name'] ?? '') ?>" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="category">Categoria *</label>
                                    <select id="category" class="form-select" required>
                                        <option value="">Selecione uma categoria</option>
                                        <option value="fotografia" <?= ($vendorProfile['category'] ?? '') === 'fotografia' ? 'selected' : '' ?>>Fotografia</option>
                                        <option value="buffet" <?= ($vendorProfile['category'] ?? '') === 'buffet' ? 'selected' : '' ?>>Buffet</option>
                                        <option value="cerimonial" <?= ($vendorProfile['category'] ?? '') === 'cerimonial' ? 'selected' : '' ?>>Cerimonial</option>
                                        <option value="espaco" <?= ($vendorProfile['category'] ?? '') === 'espaco' ? 'selected' : '' ?>>Espaço</option>
                                        <option value="decoracao" <?= ($vendorProfile['category'] ?? '') === 'decoracao' ? 'selected' : '' ?>>Decoração</option>
                                        <option value="musica" <?= ($vendorProfile['category'] ?? '') === 'musica' ? 'selected' : '' ?>>Música</option>
                                        <option value="beleza" <?= ($vendorProfile['category'] ?? '') === 'beleza' ? 'selected' : '' ?>>Beleza</option>
                                        <option value="doces" <?= ($vendorProfile['category'] ?? '') === 'doces' ? 'selected' : '' ?>>Doces</option>
                                        <option value="flores" <?= ($vendorProfile['category'] ?? '') === 'flores' ? 'selected' : '' ?>>Flores</option>
                                        <option value="outros" <?= ($vendorProfile['category'] ?? '') === 'outros' ? 'selected' : '' ?>>Outros</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="city">Cidade *</label>
                                    <input type="text" id="city" class="form-input"
                                           value="<?= htmlspecialchars($vendorProfile['city'] ?? '') ?>" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="state">Estado *</label>
                                    <select id="state" class="form-select" required>
                                        <option value="">Selecione o estado</option>
                                        <option value="SP" <?= ($vendorProfile['state'] ?? '') === 'SP' ? 'selected' : '' ?>>São Paulo</option>
                                        <option value="RJ" <?= ($vendorProfile['state'] ?? '') === 'RJ' ? 'selected' : '' ?>>Rio de Janeiro</option>
                                        <option value="MG" <?= ($vendorProfile['state'] ?? '') === 'MG' ? 'selected' : '' ?>>Minas Gerais</option>
                                        <option value="RS" <?= ($vendorProfile['state'] ?? '') === 'RS' ? 'selected' : '' ?>>Rio Grande do Sul</option>
                                        <option value="SC" <?= ($vendorProfile['state'] ?? '') === 'SC' ? 'selected' : '' ?>>Santa Catarina</option>
                                        <option value="PR" <?= ($vendorProfile['state'] ?? '') === 'PR' ? 'selected' : '' ?>>Paraná</option>
                                        <!-- Add more states as needed -->
                                    </select>
                                </div>

                                <div class="form-group full-width">
                                    <label class="form-label" for="description">Descrição do Negócio</label>
                                    <textarea id="description" class="form-input form-textarea"
                                              placeholder="Descreva seus serviços, experiência e diferenciais..."><?= htmlspecialchars($vendorProfile['description'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-phone"></i>
                                Informações de Contato
                            </h3>

                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label" for="phone">Telefone</label>
                                    <input type="tel" id="phone" class="form-input"
                                           value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                                           placeholder="(11) 99999-9999">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" id="email" class="form-input"
                                           value="<?= htmlspecialchars($user['email']) ?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="website">Website</label>
                                    <input type="url" id="website" class="form-input"
                                           placeholder="https://seusite.com.br">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="instagram">Instagram</label>
                                    <input type="text" id="instagram" class="form-input"
                                           placeholder="@seuinstagram">
                                </div>
                            </div>
                        </div>

                        <!-- Portfolio Gallery -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <i class="fas fa-images"></i>
                                Portfólio (máximo 6 fotos)
                            </h3>

                            <div class="gallery-grid">
                                <div class="gallery-item" onclick="document.getElementById('galleryUpload').click()">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <div class="gallery-item">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div class="gallery-item">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div class="gallery-item">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div class="gallery-item">
                                    <i class="fas fa-image"></i>
                                </div>
                                <div class="gallery-item">
                                    <i class="fas fa-image"></i>
                                </div>
                            </div>
                            <input type="file" id="galleryUpload" accept="image/*" multiple style="display: none;">
                            <small style="color: var(--gray-600); font-size: 13px;">
                                Adicione fotos dos seus trabalhos para atrair mais clientes.
                                Formatos aceitos: JPG, PNG (máx. 5MB por foto)
                            </small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline" onclick="resetForm()">
                                <i class="fas fa-undo"></i>
                                Desfazer Alterações
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Salvar Perfil
                            </button>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="profile-sidebar">
                        <!-- Profile Stats -->
                        <div class="sidebar-card">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar"></i>
                                Estatísticas
                            </h3>
                            <ul class="stats-list">
                                <li class="stats-item">
                                    <span class="stats-label">Perfil visualizado</span>
                                    <span class="stats-value">143 vezes</span>
                                </li>
                                <li class="stats-item">
                                    <span class="stats-label">Contratos ativos</span>
                                    <span class="stats-value">5</span>
                                </li>
                                <li class="stats-item">
                                    <span class="stats-label">Avaliação média</span>
                                    <span class="stats-value">⭐ 4.8</span>
                                </li>
                                <li class="stats-item">
                                    <span class="stats-label">Membro desde</span>
                                    <span class="stats-value"><?= date('m/Y', strtotime($user['created_at'] ?? 'now')) ?></span>
                                </li>
                            </ul>
                        </div>

                        <!-- Contact Information -->
                        <div class="sidebar-card">
                            <h3 class="card-title">
                                <i class="fas fa-user"></i>
                                Informações Pessoais
                            </h3>
                            <div class="contact-info">
                                <div class="contact-item">
                                    <i class="fas fa-user"></i>
                                    <?= htmlspecialchars($user['name']) ?>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <?= htmlspecialchars($user['email']) ?>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-calendar"></i>
                                    Membro desde <?= date('d/m/Y', strtotime($user['created_at'] ?? 'now')) ?>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-shield-alt"></i>
                                    Conta verificada
                                </div>
                            </div>
                        </div>

                        <!-- Help Card -->
                        <div class="sidebar-card">
                            <h3 class="card-title">
                                <i class="fas fa-question-circle"></i>
                                Precisa de Ajuda?
                            </h3>
                            <div style="font-size: 14px; color: var(--gray-600); line-height: 1.5;">
                                <p>Dúvidas sobre como otimizar seu perfil?</p>
                                <p>Entre em contato com nossa equipe:</p>
                                <div style="margin-top: 15px;">
                                    <a href="mailto:fornecedores@clea.com.br"
                                       style="color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                        <i class="fas fa-envelope"></i>
                                        fornecedores@clea.com.br
                                    </a>
                                    <a href="tel:+5511999999999"
                                       style="color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-phone"></i>
                                        (11) 9999-9999
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <script>
        function updateProfile(event) {
            event.preventDefault();

            // Collect form data
            const formData = {
                businessName: document.getElementById('businessName').value,
                category: document.getElementById('category').value,
                city: document.getElementById('city').value,
                state: document.getElementById('state').value,
                description: document.getElementById('description').value,
                phone: document.getElementById('phone').value,
                website: document.getElementById('website').value,
                instagram: document.getElementById('instagram').value
            };

            // Validate required fields
            if (!formData.businessName || !formData.category || !formData.city || !formData.state) {
                alert('Por favor, preencha todos os campos obrigatórios marcados com *');
                return;
            }

            // Show loading state
            const submitBtn = document.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
            submitBtn.disabled = true;

            // Simulate API call
            setTimeout(() => {
                alert('Perfil atualizado com sucesso! As alterações podem levar algumas horas para serem refletidas na plataforma.');

                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;

                // Update page title
                document.title = `${formData.businessName} - Perfil - Clea`;

                // Update profile header
                document.querySelector('.profile-name').textContent = formData.businessName;
                document.querySelector('.profile-category').textContent = formData.category.charAt(0).toUpperCase() + formData.category.slice(1);

            }, 2000);
        }

        function resetForm() {
            if (confirm('Deseja desfazer todas as alterações não salvas?')) {
                document.getElementById('profileForm').reset();
            }
        }

        function handleAvatarUpload(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Arquivo muito grande. O tamanho máximo é 5MB.');
                    return;
                }

                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Por favor, selecione apenas arquivos de imagem.');
                    return;
                }

                // In a real application, you would upload the file here
                alert('Funcionalidade de upload de avatar em desenvolvimento. Em breve você poderá personalizar sua foto de perfil!');
            }
        }

        // Gallery upload handler
        document.getElementById('galleryUpload').addEventListener('change', function(event) {
            const files = event.target.files;
            if (files.length > 6) {
                alert('Máximo de 6 fotos permitidas no portfólio.');
                return;
            }

            for (let file of files) {
                if (file.size > 5 * 1024 * 1024) {
                    alert(`Arquivo ${file.name} muito grande. Tamanho máximo: 5MB.`);
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    alert(`${file.name} não é um arquivo de imagem válido.`);
                    return;
                }
            }

            alert(`${files.length} foto(s) selecionada(s). Funcionalidade de upload em desenvolvimento!`);
        });

        // Auto-format phone number
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 11) {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else if (value.length >= 7) {
                value = value.replace(/(\d{2})(\d{4})(\d+)/, '($1) $2-$3');
            } else if (value.length >= 3) {
                value = value.replace(/(\d{2})(\d+)/, '($1) $2');
            }
            e.target.value = value;
        });

        // Auto-format Instagram handle
        document.getElementById('instagram').addEventListener('input', function(e) {
            let value = e.target.value;
            if (value && !value.startsWith('@')) {
                e.target.value = '@' + value;
            }
        });
    </script>
</body>
</html>