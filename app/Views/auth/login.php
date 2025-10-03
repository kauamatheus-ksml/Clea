<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login - Clea Casamentos</title>
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
            --overlay: rgba(101, 41, 41, 0.8);
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
        }

        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(101, 41, 41, 0.1);
            width: 100%;
            max-width: 400px;
            border: 2px solid rgba(242, 171, 177, 0.2);
        }

        .logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo h1 {
            font-family: 'Lora', serif;
            font-size: 32px;
            color: var(--primary-dark);
            margin-bottom: 8px;
        }

        .logo p {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid rgba(242, 171, 177, 0.3);
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s;
            background-color: var(--card-bg);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary-medium);
            box-shadow: 0 0 0 3px rgba(242, 171, 177, 0.1);
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-medium), var(--primary-dark));
            color: white;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 16px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(242, 171, 177, 0.4);
        }

        .login-links {
            text-align: center;
        }

        .login-links a {
            color: var(--primary-medium);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .login-links a:hover {
            color: var(--primary-dark);
        }

        .divider {
            margin: 24px 0;
            text-align: center;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background-color: rgba(242, 171, 177, 0.3);
        }

        .divider span {
            background-color: white;
            padding: 0 16px;
            color: var(--text-secondary);
            font-size: 14px;
            position: relative;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(242, 171, 177, 0.2);
        }

        .register-link p {
            color: var(--text-secondary);
            font-size: 14px;
        }

        .register-link a {
            color: var(--primary-dark);
            font-weight: 600;
            text-decoration: none;
        }

        .register-link a:hover {
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
            transition: color 0.3s;
        }

        .back-home a:hover {
            color: var(--primary-dark);
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background-color: #fee;
            border: 1px solid #fcc;
            color: #c33;
        }

        .alert-success {
            background-color: #efe;
            border: 1px solid #cfc;
            color: #363;
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
                padding: 24px;
            }

            .back-home {
                position: static;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="back-home">
        <a href="<?= url('/') ?>">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M19 12H5m7-7l-7 7 7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
            </svg>
            Voltar ao site
        </a>
    </div>

    <div class="login-container">
        <div class="logo">
            <h1>Clea</h1>
            <p>Área de acesso</p>
        </div>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success_message']) ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <form action="<?= url('authenticate') ?>" method="POST" id="loginForm">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-login">Entrar</button>
        </form>

        <div class="divider">
            <span>ou</span>
        </div>

        <div class="register-link">
            <p>Ainda não tem uma conta?</p>
            <a href="<?= url('register') ?>">Cadastre-se aqui</a>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if (!email || !password) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos.');
                return;
            }

            if (!email.includes('@')) {
                e.preventDefault();
                alert('Por favor, insira um email válido.');
                return;
            }
        });
    </script>
</body>
</html>
