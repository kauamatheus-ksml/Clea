<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Criar Conta - Clea Casamentos' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #f2abb1;
            --secondary: #652929;
            --text-dark: #2c2c2c;
            --text-light: #6b6b6b;
            --white: #ffffff;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
            background: linear-gradient(135deg, #f2abb1 0%, #d4948a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        h1, h2 {
            font-family: 'Lora', serif;
            font-weight: 600;
        }

        .register-container {
            background: var(--white);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
        }

        .logo {
            text-align: center;
            font-family: 'Lora', serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }

        .subtitle {
            text-align: center;
            color: var(--text-light);
            margin-bottom: 2rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .role-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .role-option {
            position: relative;
        }

        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .role-label {
            display: block;
            padding: 1.5rem;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .role-option input[type="radio"]:checked + .role-label {
            border-color: var(--primary);
            background: rgba(242, 171, 177, 0.1);
        }

        .role-icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .role-title {
            font-weight: 600;
            color: var(--secondary);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
        }

        .vendor-fields {
            display: none;
        }

        .vendor-fields.active {
            display: block;
        }

        .btn {
            width: 100%;
            padding: 1rem;
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .btn:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-light);
        }

        .login-link a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            color: var(--primary);
        }

        @media (max-width: 600px) {
            .register-container {
                padding: 2rem;
            }

            .role-selector {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="logo">Clea</div>
        <p class="subtitle">Crie sua conta e comece a planejar</p>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= url('register') ?>" id="registerForm">
            <div class="role-selector">
                <div class="role-option">
                    <input type="radio" name="role" value="client" id="role-client" checked>
                    <label for="role-client" class="role-label">
                        <div class="role-icon">💑</div>
                        <div class="role-title">Sou Noivo(a)</div>
                    </label>
                </div>
                <div class="role-option">
                    <input type="radio" name="role" value="vendor" id="role-vendor">
                    <label for="role-vendor" class="role-label">
                        <div class="role-icon">💼</div>
                        <div class="role-title">Sou Fornecedor</div>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="name">Nome Completo *</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Senha *</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>

            <div class="vendor-fields" id="vendorFields">
                <div class="form-group">
                    <label for="business_name">Nome da Empresa *</label>
                    <input type="text" id="business_name" name="business_name">
                </div>

                <div class="form-group">
                    <label for="category">Categoria *</label>
                    <select id="category" name="category">
                        <option value="">Selecione...</option>
                        <option value="buffet">Buffet</option>
                        <option value="fotografia">Fotografia</option>
                        <option value="decoracao">Decoração</option>
                        <option value="musica">Música</option>
                        <option value="flores">Flores</option>
                        <option value="bolo">Bolo</option>
                        <option value="convites">Convites</option>
                        <option value="video">Vídeo</option>
                        <option value="outros">Outros</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="city">Cidade *</label>
                    <input type="text" id="city" name="city" placeholder="Ex: São Paulo">
                </div>
            </div>

            <button type="submit" class="btn">Criar Conta</button>
        </form>

        <div class="login-link">
            Já tem uma conta? <a href="<?= url('login') ?>">Fazer login</a>
        </div>
    </div>

    <script>
        const roleInputs = document.querySelectorAll('input[name="role"]');
        const vendorFields = document.getElementById('vendorFields');

        roleInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.value === 'vendor') {
                    vendorFields.classList.add('active');
                    document.getElementById('business_name').required = true;
                    document.getElementById('category').required = true;
                    document.getElementById('city').required = true;
                } else {
                    vendorFields.classList.remove('active');
                    document.getElementById('business_name').required = false;
                    document.getElementById('category').required = false;
                    document.getElementById('city').required = false;
                }
            });
        });
    </script>
</body>
</html>
