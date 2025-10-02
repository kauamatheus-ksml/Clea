<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Fornecedor - Clea Casamentos' ?></title>
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
            --bg-light: #fafafa;
            --white: #ffffff;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
            background: var(--bg-light);
        }

        h1, h2, h3 {
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

        .back-link {
            color: var(--text-light);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: var(--primary);
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }

        .vendor-header {
            background: var(--white);
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }

        .vendor-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #f2abb1, #d4948a);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            margin: 0 auto 1.5rem;
        }

        .vendor-category {
            display: inline-block;
            background: var(--primary);
            color: var(--white);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .vendor-name {
            font-size: 2.5rem;
            color: var(--secondary);
            margin-bottom: 1rem;
        }

        .vendor-location {
            color: var(--text-light);
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .vendor-email {
            color: var(--text-light);
            font-size: 1rem;
        }

        .vendor-email a {
            color: var(--primary);
            text-decoration: none;
        }

        .vendor-description {
            background: var(--white);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }

        .vendor-description h2 {
            color: var(--secondary);
            margin-bottom: 1rem;
        }

        .vendor-description p {
            color: var(--text-light);
            font-size: 1.1rem;
            line-height: 1.8;
        }

        .cta-box {
            background: var(--primary);
            color: var(--white);
            padding: 2.5rem;
            border-radius: 15px;
            text-align: center;
        }

        .cta-box h2 {
            color: var(--white);
            margin-bottom: 1rem;
        }

        .cta-box p {
            margin-bottom: 1.5rem;
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 2rem;
            background: var(--white);
            color: var(--secondary);
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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
        <a href="<?= url('vendors') ?>" class="back-link">← Voltar para fornecedores</a>

        <div class="vendor-header">
            <div style="text-align: center;">
                <div class="vendor-icon">
                    <?php
                    $icons = [
                        'buffet' => '🍽️',
                        'fotografia' => '📸',
                        'decoracao' => '🎨',
                        'musica' => '🎵',
                        'flores' => '🌸',
                        'bolo' => '🎂'
                    ];
                    echo $icons[$data['vendor']['category']] ?? '💼';
                    ?>
                </div>
                <span class="vendor-category"><?= ucfirst($data['vendor']['category']) ?></span>
                <h1 class="vendor-name"><?= htmlspecialchars($data['vendor']['business_name']) ?></h1>
                <p class="vendor-location">📍 <?= htmlspecialchars($data['vendor']['city']) ?></p>
                <p class="vendor-email">✉️ <a href="mailto:<?= htmlspecialchars($data['vendor']['email']) ?>"><?= htmlspecialchars($data['vendor']['email']) ?></a></p>
            </div>
        </div>

        <div class="vendor-description">
            <h2>Sobre</h2>
            <p>
                <?php if (!empty($data['vendor']['description'])): ?>
                    <?= nl2br(htmlspecialchars($data['vendor']['description'])) ?>
                <?php else: ?>
                    Fornecedor profissional qualificado e aprovado pela plataforma Clea Casamentos.
                    Entre em contato para conhecer mais sobre os serviços oferecidos.
                <?php endif; ?>
            </p>
        </div>

        <div class="cta-box">
            <h2>Interessado neste fornecedor?</h2>
            <p>Crie sua conta na Clea para entrar em contato e contratar este fornecedor.</p>
            <a href="<?= url('register') ?>" class="btn">Criar Conta Gratuita</a>
        </div>
    </div>
</body>
</html>
