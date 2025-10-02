<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Clea Casamentos - Casamentos Minimalistas e Sustentáveis</title>
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
            background-color: var(--background);
            line-height: 1.6;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Lora', serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        header {
            position: sticky;
            top: 0;
            z-index: 50;
            background-color: rgba(255, 243, 224, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(242, 171, 177, 0.2);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
        }

        .logo {
            font-size: 24px;
            font-family: 'Lora', serif;
            font-weight: 600;
            color: var(--primary-dark);
            text-decoration: none;
        }

        nav {
            display: flex;
            gap: 32px;
            align-items: center;
            font-size: 14px;
            font-weight: 500;
            letter-spacing: 1px;
        }

        nav a {
            text-decoration: none;
            color: var(--text-primary);
            transition: color 0.3s;
        }

        nav a:hover {
            color: var(--primary-medium);
        }

        .btn-login {
            background-color: transparent;
            color: var(--primary-dark);
            padding: 8px 16px;
            border: 2px solid var(--primary-medium);
            border-radius: 25px;
            transition: all 0.3s;
            margin-right: 8px;
        }

        .btn-login:hover {
            background-color: var(--primary-medium);
            color: white;
        }

        .btn-contato {
            background-color: var(--primary-medium);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            transition: all 0.3s;
        }

        .btn-contato:hover {
            background-color: var(--primary-dark);
        }

        .btn-contato:hover {
            opacity: 0.9;
        }

        .menu-mobile {
            display: none;
            border: none;
            background: none;
            cursor: pointer;
        }

        /* Hero Section */
        .hero {
            position: relative;
            height: 80vh;
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDr_ElSERCXaBCHycqDMKmQMqTp-Alb7Dc0kglBMD3CGQjM3Sc3Bg0OGHbwA9feeUnyhz6Sdo_g_DJtMVNWeVIhK8ldPP_BEOG10aKYSMrXCcde-Rp-OAyybvUnhBHjAa0j6WXiWi3M2EfdE0TjS0mGQLIH0N_jRlD-bAZtaISNJcw1OOCewYHS72L1_cTE0jILygs4SzKCg08lArQnTuSI0cWa36bMLQOh-RiSpwS0rDRIONlN7s_kg-yt5yMypBauQghdGxAuUYU");
            background-size: cover;
            background-position: center;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--overlay);
        }

        .hero-content {
            position: relative;
            z-index: 10;
            padding: 16px;
        }

        .hero h1 {
            font-size: 48px;
            font-weight: 300;
            line-height: 1.2;
            margin-bottom: 16px;
        }

        .hero p {
            font-size: 18px;
            font-weight: 300;
            max-width: 600px;
            margin: 0 auto 32px;
        }

        .newsletter-form {
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-width: 400px;
            margin: 0 auto;
        }

        .newsletter-form input {
            padding: 12px 20px;
            border-radius: 25px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 16px;
        }

        .newsletter-form input:focus {
            outline: none;
            border-color: var(--primary-medium);
            background-color: rgba(255, 255, 255, 0.2);
        }

        .newsletter-form input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .newsletter-form button {
            background-color: var(--primary-medium);
            color: white;
            padding: 12px 24px;
            border-radius: 25px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .newsletter-form button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* Sections */
        .section {
            padding: 80px 0;
        }

        .section-bg-beige {
            background-color: var(--card-bg);
        }

        .section-bg-verde {
            background-color: rgba(242, 171, 177, 0.1);
        }

        .section h2 {
            font-size: 36px;
            text-align: center;
            margin-bottom: 16px;
        }

        .section p {
            text-align: center;
            font-size: 18px;
            max-width: 800px;
            margin: 0 auto 48px;
        }

        /* Grid */
        .grid {
            display: grid;
            gap: 32px;
        }

        .grid-3 {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }

        .card {
            background-color: var(--primary-light);
            padding: 32px;
            border-radius: 12px;
            border: 1px solid rgba(242, 171, 177, 0.2);
            box-shadow: 0 2px 8px rgba(101, 41, 41, 0.08);
            transition: all 0.3s;
            text-align: center;
        }

        .card:hover {
            box-shadow: 0 8px 24px rgba(101, 41, 41, 0.15);
            transform: translateY(-4px);
            border-color: var(--primary-medium);
        }

        .card h3 {
            font-size: 20px;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 8px;
        }

        /* Service Icons */
        .service-icon {
            width: 96px;
            height: 96px;
            background: linear-gradient(135deg, var(--primary-medium), rgba(242, 171, 177, 0.7));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 4px 12px rgba(242, 171, 177, 0.3);
        }

        .service-icon svg {
            width: 40px;
            height: 40px;
            color: white;
        }

        /* Two Column Layout */
        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            align-items: center;
        }

        .image-box {
            height: 400px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(101, 41, 41, 0.15);
            border: 2px solid rgba(242, 171, 177, 0.3);
        }

        .image-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .image-box:hover img {
            transform: scale(1.05);
        }

        /* Gallery */
        .gallery {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .gallery-item {
            aspect-ratio: 1;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid rgba(242, 171, 177, 0.2);
            transition: all 0.3s;
        }

        .gallery-item:hover {
            border-color: var(--primary-medium);
            box-shadow: 0 4px 12px rgba(242, 171, 177, 0.3);
        }

        .gallery-item.wide {
            grid-column: span 2;
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .gallery-item:hover img {
            transform: scale(1.05);
        }

        /* Process Steps */
        .process {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
        }

        .process::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            height: 100%;
            width: 2px;
            background: linear-gradient(to bottom, var(--primary-medium), var(--primary-dark));
            transform: translateX(-50%);
            opacity: 0.6;
        }

        .process-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 64px 64px;
        }

        .process-step {
            position: relative;
        }

        .process-step.right {
            text-align: left;
        }

        .process-step.left {
            text-align: right;
        }

        .process-step h3 {
            font-size: 24px;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 8px;
        }

        /* Testimonials */
        .testimonial {
            background-color: var(--primary-light);
            padding: 32px;
            border-radius: 12px;
            border: 2px solid var(--primary-medium);
            box-shadow: 0 4px 12px rgba(101, 41, 41, 0.08);
        }

        .testimonial p {
            font-style: italic;
            margin-bottom: 16px;
            text-align: left;
        }

        .testimonial .author {
            font-weight: 600;
            text-align: left;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 12px 32px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s;
            border: 2px solid transparent;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-medium), var(--primary-dark));
            color: white;
            box-shadow: 0 4px 12px rgba(242, 171, 177, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(242, 171, 177, 0.6);
        }

        .btn-secondary {
            border-color: var(--primary-medium);
            color: var(--primary-dark);
            background-color: transparent;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, var(--primary-medium), var(--primary-dark));
            color: white;
            transform: translateY(-2px);
        }

        /* Footer */
        footer {
            background-color: var(--card-bg);
            padding: 40px 0;
            text-align: center;
            border-top: 2px solid rgba(242, 171, 177, 0.2);
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .footer-links {
            display: flex;
            gap: 16px;
        }

        .footer-links a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--primary-medium);
        }

        /* WhatsApp Button */
        .whatsapp-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #25D366;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s;
            z-index: 100;
        }

        .whatsapp-btn:hover {
            background-color: #20b358;
        }

        /* Responsive */
        @media (max-width: 768px) {
            nav {
                display: none;
            }

            .menu-mobile {
                display: block;
            }

            .hero h1 {
                font-size: 32px;
            }

            .newsletter-form {
                flex-direction: column;
            }

            .two-column {
                grid-template-columns: 1fr;
            }

            .gallery {
                grid-template-columns: repeat(2, 1fr);
            }

            .process::before {
                display: none;
            }

            .process-grid {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .process-step.left,
            .process-step.right {
                text-align: left;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (min-width: 768px) {
            .newsletter-form {
                flex-direction: row;
            }

            .newsletter-form input {
                flex: 1;
            }

            .newsletter-form button {
                flex-shrink: 0;
            }
        }
    </style>
</head>
<body>
    <div class="relative min-h-screen w-full flex flex-col">
        <!-- Header -->
        <header>
            <div class="container">
                <div class="header-content">
                    <a href="#" class="logo">Clea</a>
                    <nav>
                        <a href="#filosofia">Filosofia</a>
                        <a href="#servicos">Serviços</a>
                        <a href="#galeria">Galeria</a>
                        <a href="login.php" class="btn-login">Login</a>
                        <a href="#contato" class="btn-contato">Contato</a>
                    </nav>
                    <button class="menu-mobile">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M4 6h16M4 12h16m-7 6h7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main style="flex-grow: 1;">
            <!-- Hero Section -->
            <section class="hero">
                <div class="hero-bg"></div>
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <h1>Casamentos com alma.<br/>Essencialmente belos.</h1>
                    <p>Conectamos noivos a fornecedores curados para uma celebração autêntica e sustentável.</p>
                    <div class="newsletter-form">
                        <input type="email" placeholder="Seu melhor e-mail">
                        <button type="submit">Receber novidades</button>
                    </div>
                </div>
            </section>

            <!-- Filosofia Section -->
            <section class="section section-bg-beige" id="filosofia">
                <div class="container">
                    <h2>Nossa Filosofia: Sustentabilidade em 3D</h2>
                    <p>Acreditamos que um casamento pode ser um ato de amor pelo planeta e pela sociedade.</p>
                    <div class="grid grid-3">
                        <div class="card">
                            <h3>Ambiental</h3>
                            <p>Minimizamos o impacto ambiental com fornecedores locais, materiais reciclados e zero desperdício.</p>
                        </div>
                        <div class="card">
                            <h3>Social</h3>
                            <p>Valorizamos o comércio justo, a diversidade de fornecedores e o apoio a comunidades locais.</p>
                        </div>
                        <div class="card">
                            <h3>Afetiva</h3>
                            <p>Criamos celebrações que refletem a essência do casal, fortalecendo laços e memórias.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Serviços Section -->
            <section class="section" id="servicos">
                <div class="container">
                    <h2>Nossos Pilares de Serviço</h2>
                    <div class="grid grid-3">
                        <div class="card">
                            <div class="service-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                                </svg>
                            </div>
                            <h3>Curadoria de Fornecedores</h3>
                            <p>Uma rede selecionada de parceiros que compartilham nossos valores de sustentabilidade e excelência.</p>
                        </div>
                        <div class="card">
                            <div class="service-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                                </svg>
                            </div>
                            <h3>Design Essencialista</h3>
                            <p>Criamos projetos de decoração que valorizam a beleza do simples, o significado dos detalhes e a personalidade do casal.</p>
                        </div>
                        <div class="card">
                            <div class="service-icon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M13 10V3L4 14h7v7l9-11h-7z" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"></path>
                                </svg>
                            </div>
                            <h3>Planejamento Consciente</h3>
                            <p>Assessoria completa para um planejamento leve, focado no que realmente importa: a celebração do amor.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- About Section -->
            <section class="section section-bg-verde">
                <div class="container">
                    <div class="two-column">
                        <div>
                            <h2 style="text-align: left; margin-bottom: 16px;">O essencialismo aplicado ao seu dia.</h2>
                            <p style="text-align: left; margin-bottom: 24px;">Menos excesso, mais significado. Menos tendências, mais personalidade. Menos estresse, mais presença. Guiamos cada escolha para que ela represente a história de vocês, criando um evento que é um reflexo autêntico da sua união, com foco na experiência e nas memórias que ficarão.</p>
                            <a href="#contato" class="btn btn-primary">Comece a planejar</a>
                        </div>
                        <div class="image-box">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCricnZmF8i7Scz42LcP8ybFiOaeT2AP-v0m9-Lqd29YMEAIrtEEpFMBEU0XXq-hw54ghFBeVlXB3mrAfh1zCIyena1Cv73CFuqW-uQ5NPJQQfB5syuIK83o-l8U7B6L3_XOLiHnvXszefzjwcXrGD8Ag1KdjnCVVK7jZw26yRQD28VMtWt4kfmR2EtUEmA0fSO8IerEKZxYsKy83I_B3l54HeRwg-Phg22FpjlIpIgFUqLQFshzleUGD3xctYcz8F_PM422AsQ0E8" alt="Casamento essencialista">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Galeria Section -->
            <section class="section" id="galeria">
                <div class="container">
                    <h2>Galeria de Momentos Essenciais</h2>
                    <div class="gallery">
                        <div class="gallery-item">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBy2u7JCLr71At87ZsZi1RmSCP1TlVRD3f_dSDGMiER4Os-e0nIwjvddWgbHxtTEMv8xox0vdmaxcR0LCZbgFveoct1AddY8Aenbl4QzZypZmnqW7vhWb1HPXuN9zTOM9MxLMrxt9D0-JXKuV_BdcGZHiA1kzEqrA9JNzeiz1q71U2yAIvdrjk4dAn0CAEc2WGkjgLzoHPHbeHxK9OnQbkixbXfM4QoTW74ZjxhDnxteRfItbhjhKbLXFqzWCqZneV9i1jb0xibTZE" alt="Galeria 1">
                        </div>
                        <div class="gallery-item wide">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuB70-JmpSi7e6AWbiwe9f2IYT425IUL_xAPj0I02uoT2F4ho8pmmrbDT1gaMuW7Uo0p8uKYPh1WPFYmEObUTrNeQwah1l9U5GdRRaDfHnspep7lqIXSbfrHmxMSZgUpfY8AbNtDq48nQfeY25QTNJ8dCY22o1V4aDOz_KxgVXC4lwXqLDkrlDH_ZbfLx3ZcXjSIEaP-PVPQ1hJuR5PIHvKYyQZyZIRN1Bk3ZN-JXncP7YEFxtsDGYo4BKaTrfSxNBQ8lMT15BWBmCU" alt="Galeria 2">
                        </div>
                        <div class="gallery-item">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBhXat8uVLMSceBs7_r4FbeZKeK_nHMzZ4OHjjiuxPd1wgSp_KROTsG-v9V21i6QCmxftPcPyUWjGRuct_hLHb9z8OL6-OrCpmeokKThFOmaF2drIQSO-q734IWsGInuiInBCPcp_Anv5zahvbS0fqUiSmCc3QrVTFzNoqgGdmMByK-61ve145F3bvdbdYDBrkPO3Z1Vr4EIE__v-Bg6c1D-AwCePAeU_R8TfZ4GiLL8grYo-0vluVD8_ZfSQhlHFh57FbcGB9HUqk" alt="Galeria 3">
                        </div>
                        <div class="gallery-item wide">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuBPvwaJZpqIs2FtWvOLWW83v3W7txKIy4EDIPHIbfyhiwGZXk5CEPPdNerZsfYtaDKbubIki4BQ7ZLRVO3J7XyrK624Yg_-ADFGIGGcTUZKJEzwrgIfda0RvjRRHx0eaPRZ-LcXVXfd7N4pqS767FDx5PE5cR-F12lyTUkGuJ29fXJ6rEpdNDhM3gqLpad9aAN9e5qJ9heIfIq1TJU5lc5tNz6b4NKF5rEa_1TfcrEL4PmEKJC0sY3ruTrDGx6iWbCHDxPpJ4QpGw4" alt="Galeria 4">
                        </div>
                        <div class="gallery-item">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuD2Cvltkl5qj1Gjyis1EKBjZhFsRm-mi05ZWXFvWAl0WHdi9trBnAJHj0isIzkiyjpCYy04Jp_kWcLcn4gR2aE3LthcAZMYEnp_XyKAPUYJ3VI-PXgxA61PQ1lQzFeFcAfDX04nQ8y-nWHvZGtff3qnX-owhBNA5Cfdle_oipgVbTWj4Nxwy123gjoJy9qCe3uM_rIaifotcSI7o6uC1Fhw0x4wQdUvw-HwU4jlQAv57xjaklqgl65C434XylymJ80kjljD7eBKOTQ" alt="Galeria 5">
                        </div>
                        <div class="gallery-item">
                            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuB82cVwrhPUOg__uVJ-pMRGYtBBfNelvqz_UNquV2oRr4_LSlhm8cBv52yj4T72X3UJTQbgw3IisWl7bO6hL5ImzLEv2kHtwG-814xk67UJ0ECK83qkTpS-xu9vLAedXDnJt6ifTGibZ0iJN-BwEQTDFqF_cL_TqTfBxVPOf-U2NpSKQftmqE8uoIiAyPGBnAA7ZK2dB7IQa_CvC-dhQ_K3lOzkvcGBQP4wfvlKZNDBheFwNWyXG-UCyxlYRD1ZyaW6b-Bk2LweS84" alt="Galeria 6">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Como Funciona Section -->
            <section class="section section-bg-beige">
                <div class="container">
                    <h2>Como Funciona</h2>
                    <div class="process">
                        <div class="process-grid">
                            <div class="process-step left">
                                <h3>01. Conexão</h3>
                                <p>Iniciamos com uma conversa para entender seus sonhos, valores e a essência da sua história de amor.</p>
                            </div>
                            <div></div>
                            <div></div>
                            <div class="process-step right">
                                <h3>02. Curadoria</h3>
                                <p>Apresentamos uma seleção personalizada de fornecedores e ideias alinhadas à sua visão e orçamento.</p>
                            </div>
                            <div class="process-step left">
                                <h3>03. Cocriação</h3>
                                <p>Juntos, desenhamos cada detalhe, do convite à festa, garantindo que tudo tenha o toque de vocês.</p>
                            </div>
                            <div></div>
                            <div></div>
                            <div class="process-step right">
                                <h3>04. Celebração</h3>
                                <p>No grande dia, cuidamos de tudo para que vocês apenas celebrem, presentes e conectados um ao outro.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Depoimentos Section -->
            <section class="section">
                <div class="container">
                    <h2>Depoimentos de Impacto</h2>
                    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); max-width: 800px; margin: 0 auto;">
                        <div class="testimonial">
                            <p>"A Clea não apenas organizou nosso casamento, mas nos ajudou a criar um dia que era verdadeiramente nosso. A atenção à sustentabilidade foi o que nos atraiu, mas o cuidado e a dedicação em cada detalhe foi o que nos conquistou."</p>
                            <p class="author">- Isabela & Rafael</p>
                        </div>
                        <div class="testimonial">
                            <p>"Contratar a Clea foi a melhor decisão que tomamos. O processo foi leve, divertido e o resultado foi um casamento minimalista, elegante e cheio de significado, exatamente como sonhávamos. Somos eternamente gratos."</p>
                            <p class="author">- Camila & Lucas</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contato Section -->
            <section class="section section-bg-verde" id="contato">
                <div class="container" style="text-align: center;">
                    <h2>Vamos conversar sobre o seu dia?</h2>
                    <p>Seja para tirar uma dúvida ou para iniciar o planejamento, estamos aqui para ouvir você.</p>
                    <div style="margin-top: 32px; display: flex; flex-direction: column; gap: 16px; align-items: center;">
                        <a href="#" class="btn btn-primary" style="width: 100%; max-width: 300px;">Agendar uma conversa</a>
                        <a href="#" class="btn btn-secondary" style="width: 100%; max-width: 300px;">Enviar um WhatsApp</a>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer>
            <div class="container">
                <div class="footer-content">
                    <p style="font-size: 14px;">© 2024 Clea Casamentos. Por um mundo com mais amor e menos desperdício.</p>
                    <div class="footer-links">
                        <a href="#">Instagram</a>
                        <a href="#">Pinterest</a>
                        <a href="#">E-mail</a>
                    </div>
                </div>
            </div>
        </footer>

        <!-- WhatsApp Button -->
        <a href="#" class="whatsapp-btn">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.894 11.892-1.99 0-3.902-.539-5.586-1.54l-6.167 1.688a.75.75 0 0 1-.947-.947z"></path>
            </svg>
        </a>
    </div>

    <script>
        // Menu mobile toggle
        document.querySelector('.menu-mobile').addEventListener('click', function() {
            const nav = document.querySelector('nav');
            nav.style.display = nav.style.display === 'flex' ? 'none' : 'flex';
        });

        // Smooth scroll para links internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Form de newsletter
        document.querySelector('.newsletter-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            alert('Obrigado! Em breve você receberá nossas novidades no email: ' + email);
            this.querySelector('input[type="email"]').value = '';
        });
    </script>
</body>
</html>