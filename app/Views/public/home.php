<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Clea Casamentos' ?></title>
    <meta name="description" content="Planejamento de casamentos elegante e personalizado. Encontre os melhores fornecedores curados para o seu grande dia.">
    <meta name="keywords" content="casamento, planejamento de casamento, fornecedores, noivas, wedding planner">

    <!-- Open Graph -->
    <meta property="og:title" content="Clea Casamentos - Planejamento Elegante">
    <meta property="og:description" content="Transforme seu casamento em realidade com os melhores fornecedores curados.">
    <meta property="og:type" content="website">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
            --accent: #d4948a;
            --text-dark: #2c2c2c;
            --text-light: #6b6b6b;
            --bg-light: #fafafa;
            --white: #ffffff;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Lora', serif;
            font-weight: 600;
        }

        /* Header/Navigation */
        .header {
            background: var(--white);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .nav {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
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

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }

        .btn-outline {
            border: 2px solid var(--primary);
            color: var(--primary);
            background: transparent;
        }

        .btn-outline:hover {
            background: var(--primary);
            color: var(--white);
        }

        /* Hero Section */
        .hero {
            margin-top: 80px;
            background: linear-gradient(135deg, #f2abb1 0%, #d4948a 100%);
            padding: 8rem 2rem;
            text-align: center;
            color: var(--white);
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        /* Stats Section */
        .stats {
            max-width: 1200px;
            margin: -50px auto 0;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .stat-card {
            background: var(--white);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary);
            font-family: 'Lora', serif;
        }

        .stat-label {
            color: var(--text-light);
            font-size: 1.1rem;
            margin-top: 0.5rem;
        }

        /* Features Section */
        .features {
            padding: 6rem 2rem;
            background: var(--bg-light);
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--secondary);
        }

        .section-subtitle {
            text-align: center;
            color: var(--text-light);
            font-size: 1.2rem;
            margin-bottom: 4rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .features-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 3rem;
        }

        .feature-card {
            background: var(--white);
            padding: 2.5rem;
            border-radius: 15px;
            text-align: center;
            transition: transform 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--secondary);
        }

        .feature-card p {
            color: var(--text-light);
        }

        /* Vendors Section */
        .vendors-section {
            padding: 6rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .vendors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .vendor-card {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }

        .vendor-card:hover {
            transform: translateY(-5px);
        }

        .vendor-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #f2abb1, #d4948a);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
        }

        .vendor-info {
            padding: 1.5rem;
        }

        .vendor-category {
            display: inline-block;
            background: var(--primary);
            color: var(--white);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .vendor-name {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
            color: var(--secondary);
        }

        .vendor-city {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .vendor-description {
            color: var(--text-light);
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* CTA Section */
        .cta {
            background: var(--secondary);
            color: var(--white);
            padding: 6rem 2rem;
            text-align: center;
        }

        .cta h2 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }

        .cta p {
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-white {
            background: var(--white);
            color: var(--secondary);
        }

        .btn-white:hover {
            background: var(--primary);
            color: var(--white);
        }

        /* Footer */
        .footer {
            background: var(--text-dark);
            color: var(--white);
            padding: 3rem 2rem 1.5rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-bottom: 2rem;
        }

        .footer h4 {
            margin-bottom: 1rem;
        }

        .footer a {
            color: var(--white);
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            opacity: 0.8;
            transition: opacity 0.3s;
        }

        .footer a:hover {
            opacity: 1;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            opacity: 0.7;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .nav-links {
                display: none;
            }

            .vendors-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav">
            <a href="<?= url('/') ?>" class="logo">Clea</a>
            <ul class="nav-links">
                <li><a href="<?= url('/') ?>">Início</a></li>
                <li><a href="<?= url('vendors') ?>">Fornecedores</a></li>
                <li><a href="<?= url('about') ?>">Sobre</a></li>
                <li><a href="<?= url('contact') ?>">Contato</a></li>
            </ul>
            <div>
                <a href="<?= url('login.php') ?>" class="btn btn-outline">Entrar</a>
                <a href="<?= url('register') ?>" class="btn btn-primary">Criar Conta</a>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Seu Casamento dos Sonhos Começa Aqui</h1>
        <p>Planejamento elegante e personalizado com os melhores fornecedores curados especialmente para você.</p>
        <div class="hero-buttons">
            <a href="<?= url('register') ?>" class="btn btn-white">Comece Agora</a>
            <a href="<?= url('vendors') ?>" class="btn btn-outline">Explorar Fornecedores</a>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stat-card">
            <div class="stat-number"><?= $data['totalVendors'] ?? 0 ?>+</div>
            <div class="stat-label">Fornecedores Curados</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">100%</div>
            <div class="stat-label">Satisfação Garantida</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">24/7</div>
            <div class="stat-label">Suporte Dedicado</div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <h2 class="section-title">Por que escolher a Clea?</h2>
        <p class="section-subtitle">Transformamos o planejamento do seu casamento em uma experiência única e memorável.</p>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">💍</div>
                <h3>Fornecedores Curados</h3>
                <p>Seleção criteriosa dos melhores profissionais para garantir qualidade excepcional em cada detalhe.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📅</div>
                <h3>Cronograma Inteligente</h3>
                <p>Timeline personalizado com todas as etapas do planejamento organizadas perfeitamente.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💰</div>
                <h3>Gestão Financeira</h3>
                <p>Controle completo do orçamento com relatórios detalhados e pagamentos organizados.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📝</div>
                <h3>Contratos Digitais</h3>
                <p>Documentação segura e centralizada para todos os seus contratos com fornecedores.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">👥</div>
                <h3>Lista de Convidados</h3>
                <p>Gestão completa de convidados com confirmação de presença e mapa de assentos.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💬</div>
                <h3>Chat Integrado</h3>
                <p>Comunicação direta com fornecedores em um só lugar, sem complicações.</p>
            </div>
        </div>
    </section>

    <!-- Featured Vendors -->
    <section class="vendors-section">
        <h2 class="section-title">Fornecedores em Destaque</h2>
        <p class="section-subtitle">Conheça alguns dos nossos profissionais mais conceituados</p>

        <div class="vendors-grid">
            <?php if (!empty($data['featuredVendors'])): ?>
                <?php foreach ($data['featuredVendors'] as $vendor): ?>
                    <div class="vendor-card">
                        <div class="vendor-image">
                            <?php
                            $icons = [
                                'buffet' => '🍽️',
                                'fotografia' => '📸',
                                'decoracao' => '🎨',
                                'musica' => '🎵',
                                'flores' => '🌸',
                                'bolo' => '🎂'
                            ];
                            echo $icons[$vendor['category']] ?? '💼';
                            ?>
                        </div>
                        <div class="vendor-info">
                            <span class="vendor-category"><?= ucfirst($vendor['category']) ?></span>
                            <h3 class="vendor-name"><?= htmlspecialchars($vendor['business_name']) ?></h3>
                            <p class="vendor-city">📍 <?= htmlspecialchars($vendor['city']) ?></p>
                            <p class="vendor-description">
                                <?= htmlspecialchars(substr($vendor['description'] ?? 'Profissional qualificado', 0, 100)) ?>...
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; grid-column: 1/-1; color: var(--text-light);">
                    Nenhum fornecedor disponível no momento.
                </p>
            <?php endif; ?>
        </div>

        <div style="text-align: center;">
            <a href="<?= url('vendors') ?>" class="btn btn-primary">Ver Todos os Fornecedores</a>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <h2>Pronto para começar?</h2>
        <p>Crie sua conta gratuitamente e comece a planejar o casamento dos seus sonhos hoje mesmo.</p>
        <a href="<?= url('register') ?>" class="btn btn-white">Criar Conta Gratuita</a>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div>
                <h4>Clea Casamentos</h4>
                <p>Transformando sonhos em realidade desde 2025.</p>
            </div>
            <div>
                <h4>Links Rápidos</h4>
                <a href="<?= url('/') ?>">Início</a>
                <a href="<?= url('vendors') ?>">Fornecedores</a>
                <a href="<?= url('about') ?>">Sobre</a>
                <a href="<?= url('contact') ?>">Contato</a>
            </div>
            <div>
                <h4>Para Você</h4>
                <a href="<?= url('register') ?>">Criar Conta</a>
                <a href="<?= url('login.php') ?>">Entrar</a>
                <a href="/register?type=vendor">Seja um Fornecedor</a>
            </div>
            <div>
                <h4>Contato</h4>
                <p>contato@cleacasamentos.com.br</p>
                <p>(11) 9999-9999</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Clea Casamentos. Todos os direitos reservados.</p>
        </div>
    </footer>
</body>
</html>
