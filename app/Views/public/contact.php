<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Contato - Clea Casamentos' ?></title>
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
            background: #fafafa;
        }

        h1, h2 {
            font-family: 'Lora', serif;
            font-weight: 600;
        }

        .header {
            background: var(--white);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 1rem 0;
        }

        .nav {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: 'Lora', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--secondary);
            text-decoration: none;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 4rem 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-header h1 {
            font-size: 3rem;
            color: var(--secondary);
            margin-bottom: 1rem;
        }

        .page-header p {
            font-size: 1.2rem;
            color: var(--text-light);
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .contact-form {
            background: var(--white);
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
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
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            transition: border-color 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 150px;
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
        }

        .btn:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }

        .contact-info {
            margin-top: 3rem;
            text-align: center;
        }

        .contact-info h2 {
            color: var(--secondary);
            margin-bottom: 1.5rem;
        }

        .contact-item {
            margin-bottom: 1rem;
            font-size: 1.1rem;
            color: var(--text-light);
        }

        .contact-item strong {
            color: var(--text-dark);
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="nav">
            <a href="<?= url('/') ?>" class="logo">Clea</a>
        </nav>
    </header>

    <div class="container">
        <div class="page-header">
            <h1>Entre em Contato</h1>
            <p>Estamos aqui para ajudar você a planejar o casamento dos seus sonhos</p>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= url('contact') ?>" class="contact-form">
            <div class="form-group">
                <label for="name">Nome Completo *</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">E-mail *</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="phone">Telefone</label>
                <input type="tel" id="phone" name="phone" placeholder="(11) 99999-9999">
            </div>

            <div class="form-group">
                <label for="message">Mensagem *</label>
                <textarea id="message" name="message" required placeholder="Como podemos ajudar você?"></textarea>
            </div>

            <button type="submit" class="btn">Enviar Mensagem</button>
        </form>

        <div class="contact-info">
            <h2>Outras formas de contato</h2>
            <div class="contact-item">
                <strong>📧 E-mail:</strong> contato@cleacasamentos.com.br
            </div>
            <div class="contact-item">
                <strong>📱 WhatsApp:</strong> (11) 99999-9999
            </div>
            <div class="contact-item">
                <strong>⏰ Horário:</strong> Segunda a Sexta, 9h às 18h
            </div>
        </div>
    </div>
</body>
</html>
