<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Cadastro - Clea Casamentos</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,500;0,600;1,400&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet"/>
    <style>
        :root {
            --primary-light: #fff3e0;
            --primary-medium: #f2abb1;
            --primary-dark: #652929;
            --text-primary: #652929;
            --text-secondary: #8b4444;
            --background: #fff3e0;
            --card-bg: #fefcf8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--text-primary);
            background: linear-gradient(135deg, var(--primary-light), var(--card-bg));
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            background-color: white;
            padding: 0;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(101, 41, 41, 0.15);
            width: 100%;
            max-width: 600px;
            border: 2px solid rgba(242, 171, 177, 0.2);
            overflow: hidden;
        }

        .logo {
            text-align: center;
            padding: 30px 20px 20px;
            background: linear-gradient(135deg, var(--primary-light) 0%, rgba(255, 243, 224, 0.5) 100%);
        }

        .logo h1 {
            font-family: 'Lora', serif;
            font-size: 36px;
            color: var(--primary-dark);
            margin-bottom: 8px;
        }

        .logo p {
            color: var(--text-secondary);
            font-size: 14px;
        }

        /* Account Type Tabs */
        .account-types {
            display: flex;
            background-color: var(--card-bg);
            border-bottom: 2px solid var(--primary-light);
        }

        .account-type-btn {
            flex: 1;
            padding: 18px;
            border: none;
            background-color: transparent;
            color: var(--text-secondary);
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .account-type-btn:hover {
            background-color: rgba(242, 171, 177, 0.1);
        }

        .account-type-btn.active {
            color: var(--primary-dark);
            background-color: white;
        }

        .account-type-btn.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-medium), var(--primary-dark));
        }

        .account-type-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: var(--primary-light);
            transition: all 0.3s;
        }

        .account-type-btn.active .account-type-icon {
            background: linear-gradient(135deg, var(--primary-medium), var(--primary-dark));
            color: white;
        }

        /* Form Content */
        .form-content {
            padding: 30px 40px 40px;
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-primary);
            font-size: 14px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid rgba(242, 171, 177, 0.3);
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
            background-color: var(--card-bg);
            font-family: 'Montserrat', sans-serif;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary-medium);
            box-shadow: 0 0 0 4px rgba(242, 171, 177, 0.1);
            background-color: white;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .btn-register {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-medium), var(--primary-dark));
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(242, 171, 177, 0.4);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid rgba(242, 171, 177, 0.2);
        }

        .login-link p {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .login-link a {
            color: var(--primary-dark);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s;
        }

        .login-link a:hover {
            color: var(--primary-medium);
        }

        .back-home {
            position: absolute;
            top: 20px;
            left: 20px;
        }

        .back-home a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            padding: 8px 12px;
            border-radius: 8px;
        }

        .back-home a:hover {
            color: var(--primary-dark);
            background-color: rgba(255, 243, 224, 0.8);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background-color: #fff5f5;
            border: 1px solid #fcc;
            color: #c33;
        }

        .alert-success {
            background-color: #f0fff4;
            border: 1px solid #cfc;
            color: #363;
        }

        /* Info boxes */
        .info-box {
            background: linear-gradient(135deg, var(--primary-light), rgba(255, 243, 224, 0.5));
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 20px;
            border: 1px solid var(--primary-medium);
        }

        .info-box h4 {
            color: var(--primary-dark);
            font-size: 15px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-box p {
            color: var(--text-secondary);
            font-size: 13px;
            line-height: 1.5;
        }

        /* Category cards for vendor */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-top: 8px;
        }

        .category-card {
            position: relative;
            cursor: pointer;
        }

        .category-card input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .category-card label {
            display: block;
            padding: 12px;
            text-align: center;
            background-color: var(--card-bg);
            border: 2px solid rgba(242, 171, 177, 0.3);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 14px;
        }

        .category-card input[type="radio"]:checked + label {
            background: linear-gradient(135deg, var(--primary-medium), var(--primary-dark));
            color: white;
            border-color: var(--primary-dark);
        }

        .category-card label:hover {
            border-color: var(--primary-medium);
            transform: translateY(-2px);
        }

        @media (max-width: 480px) {
            .register-container {
                margin: 20px;
                border-radius: 16px;
            }

            .form-content {
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .account-type-btn {
                font-size: 13px;
                padding: 14px 8px;
            }

            .account-type-icon {
                width: 28px;
                height: 28px;
            }

            .back-home {
                position: static;
                margin-bottom: 20px;
            }

            .category-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="back-home">
        <a href="index.php">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M19 12H5m7-7l-7 7 7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
            </svg>
            Voltar ao site
        </a>
    </div>

    <div class="register-container">
        <div class="logo">
            <h1>Clea</h1>
            <p>Criar nova conta</p>
        </div>

        <!-- Account Type Tabs -->
        <div class="account-types">
            <button type="button" class="account-type-btn active" data-tab="client">
                <div class="account-type-icon">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <span>Cliente</span>
            </button>
            <button type="button" class="account-type-btn" data-tab="vendor">
                <div class="account-type-icon">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                </div>
                <span>Fornecedor</span>
            </button>
        </div>

        <div class="form-content">
            <?php
            if (isset($_SESSION['error_message'])) {
                echo '<div class="alert alert-error">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
                unset($_SESSION['error_message']);
            }
            if (isset($_SESSION['success_message'])) {
                echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
                unset($_SESSION['success_message']);
            }
            ?>

            <!-- Client Form -->
            <div id="client-form" class="tab-content active">
                <div class="info-box">
                    <h4>
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        Para Noivos
                    </h4>
                    <p>Cadastre-se para planejar seu casamento dos sonhos com nossos fornecedores curados e serviços personalizados.</p>
                </div>

                <form action="register-process.php" method="POST" id="clientForm">
                    <input type="hidden" name="role" value="client">

                    <div class="form-group">
                        <label for="client_name">Nome Completo:</label>
                        <input type="text" id="client_name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="client_email">Email:</label>
                        <input type="email" id="client_email" name="email" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="client_password">Senha:</label>
                            <input type="password" id="client_password" name="password" required minlength="6">
                        </div>

                        <div class="form-group">
                            <label for="client_confirm_password">Confirmar Senha:</label>
                            <input type="password" id="client_confirm_password" name="confirm_password" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="client_phone">Telefone (opcional):</label>
                        <input type="tel" id="client_phone" name="phone" placeholder="(00) 00000-0000">
                    </div>

                    <button type="submit" class="btn-register">Criar Conta de Cliente</button>
                </form>
            </div>

            <!-- Vendor Form -->
            <div id="vendor-form" class="tab-content">
                <div class="info-box">
                    <h4>
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        Para Fornecedores
                    </h4>
                    <p>Junte-se à nossa rede curada de parceiros e conecte-se com casais que valorizam qualidade e sustentabilidade.</p>
                </div>

                <form action="register-process.php" method="POST" id="vendorForm">
                    <input type="hidden" name="role" value="vendor">

                    <div class="form-group">
                        <label for="vendor_name">Nome Completo:</label>
                        <input type="text" id="vendor_name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="vendor_email">Email:</label>
                        <input type="email" id="vendor_email" name="email" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="vendor_password">Senha:</label>
                            <input type="password" id="vendor_password" name="password" required minlength="6">
                        </div>

                        <div class="form-group">
                            <label for="vendor_confirm_password">Confirmar Senha:</label>
                            <input type="password" id="vendor_confirm_password" name="confirm_password" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="vendor_phone">Telefone:</label>
                        <input type="tel" id="vendor_phone" name="phone" placeholder="(00) 00000-0000" required>
                    </div>

                    <div class="form-group">
                        <label for="business_name">Nome da Empresa:</label>
                        <input type="text" id="business_name" name="business_name" required>
                    </div>

                    <div class="form-group">
                        <label for="category">Categoria do Serviço:</label>
                        <div class="category-grid">
                            <div class="category-card">
                                <input type="radio" id="cat_espaco" name="category" value="espaco">
                                <label for="cat_espaco">🏰 Espaço</label>
                            </div>
                            <div class="category-card">
                                <input type="radio" id="cat_fotografia" name="category" value="fotografia">
                                <label for="cat_fotografia">📸 Fotografia</label>
                            </div>
                            <div class="category-card">
                                <input type="radio" id="cat_cerimonial" name="category" value="cerimonial">
                                <label for="cat_cerimonial">🎭 Cerimonial</label>
                            </div>
                            <div class="category-card">
                                <input type="radio" id="cat_buffet" name="category" value="buffet">
                                <label for="cat_buffet">🍽️ Buffet</label>
                            </div>
                            <div class="category-card">
                                <input type="radio" id="cat_decoracao" name="category" value="decoracao">
                                <label for="cat_decoracao">🌸 Decoração</label>
                            </div>
                            <div class="category-card">
                                <input type="radio" id="cat_trajes" name="category" value="trajes">
                                <label for="cat_trajes">👗 Trajes</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Descrição dos Serviços:</label>
                        <textarea id="description" name="description" rows="3" placeholder="Conte-nos sobre seus serviços e diferenciais..."></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">Cidade:</label>
                            <input type="text" id="city" name="city" value="Fortaleza">
                        </div>

                        <div class="form-group">
                            <label for="state">Estado:</label>
                            <input type="text" id="state" name="state" value="CE" maxlength="2">
                        </div>
                    </div>

                    <div class="info-box" style="margin-top: 20px; margin-bottom: 0;">
                        <h4>
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                            </svg>
                            Processo de Aprovação
                        </h4>
                        <p>Sua conta será analisada pela nossa equipe em até 48 horas. Você receberá um email quando for aprovada.</p>
                    </div>

                    <button type="submit" class="btn-register">Solicitar Cadastro como Fornecedor</button>
                </form>
            </div>

            <div class="login-link">
                <p>Já tem uma conta?</p>
                <a href="login.php">Faça login aqui</a>
            </div>
        </div>
    </div>

    <script>
        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.account-type-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');

                    // Remove active class from all buttons and contents
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));

                    // Add active class to clicked button and corresponding content
                    this.classList.add('active');
                    document.getElementById(targetTab + '-form').classList.add('active');
                });
            });
        });

        // Form validation for Client
        document.getElementById('clientForm').addEventListener('submit', function(e) {
            const password = document.getElementById('client_password').value;
            const confirmPassword = document.getElementById('client_confirm_password').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('As senhas não coincidem.');
                return;
            }

            if (password.length < 6) {
                e.preventDefault();
                alert('A senha deve ter pelo menos 6 caracteres.');
                return;
            }
        });

        // Form validation for Vendor
        document.getElementById('vendorForm').addEventListener('submit', function(e) {
            const password = document.getElementById('vendor_password').value;
            const confirmPassword = document.getElementById('vendor_confirm_password').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('As senhas não coincidem.');
                return;
            }

            if (password.length < 6) {
                e.preventDefault();
                alert('A senha deve ter pelo menos 6 caracteres.');
                return;
            }

            // Check if category is selected
            const categorySelected = document.querySelector('input[name="category"]:checked');
            if (!categorySelected) {
                e.preventDefault();
                alert('Por favor, selecione uma categoria de serviço.');
                return;
            }
        });

        // Phone mask for both forms
        function applyPhoneMask(inputElement) {
            inputElement.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value.length <= 11) {
                    value = value.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
                    value = value.replace(/^(\d{2})(\d{4})(\d{4})$/, '($1) $2-$3');
                }
                this.value = value;
            });
        }

        // Apply phone mask to both phone inputs
        applyPhoneMask(document.getElementById('client_phone'));
        applyPhoneMask(document.getElementById('vendor_phone'));

        // Category card selection animation
        document.querySelectorAll('.category-card input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove selected class from all cards
                document.querySelectorAll('.category-card').forEach(card => {
                    card.classList.remove('selected');
                });

                // Add selected class to current card
                if (this.checked) {
                    this.closest('.category-card').classList.add('selected');
                }
            });
        });
    </script>
</body>
</html>