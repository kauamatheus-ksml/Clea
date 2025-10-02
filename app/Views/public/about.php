<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Sobre - Clea Casamentos' ?></title>
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
            line-height: 1.8;
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

        .container {
            max-width: 900px;
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
            font-size: 1.3rem;
            color: var(--text-light);
        }

        .content-section {
            margin-bottom: 3rem;
        }

        .content-section h2 {
            font-size: 2rem;
            color: var(--secondary);
            margin-bottom: 1rem;
        }

        .content-section p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            color: var(--text-light);
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .value-card {
            background: var(--white);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .value-card h3 {
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }

        .cta-box {
            background: var(--primary);
            color: var(--white);
            padding: 3rem;
            border-radius: 15px;
            text-align: center;
            margin-top: 3rem;
        }

        .cta-box h2 {
            color: var(--white);
            margin-bottom: 1rem;
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
            margin-top: 1rem;
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
        <div class="page-header">
            <h1>Sobre a Clea Casamentos</h1>
            <p>Transformando sonhos em realidade desde 2025</p>
        </div>

        <div class="content-section">
            <h2>Nossa História</h2>
            <p>
                A Clea nasceu do sonho de tornar o planejamento de casamentos mais acessível, elegante e sem estresse.
                Acreditamos que cada casal merece ter o casamento dos seus sonhos, com fornecedores de qualidade e um
                processo de planejamento que seja tão especial quanto o grande dia.
            </p>
            <p>
                Nossa plataforma conecta noivos a uma rede cuidadosamente curada de fornecedores profissionais,
                oferecendo ferramentas intuitivas para gerenciar cada aspecto do casamento em um só lugar.
            </p>
        </div>

        <div class="content-section">
            <h2>Nossos Valores</h2>
            <div class="values-grid">
                <div class="value-card">
                    <h3>💍 Excelência</h3>
                    <p>Apenas os melhores fornecedores fazem parte da nossa rede curada.</p>
                </div>
                <div class="value-card">
                    <h3>🤝 Confiança</h3>
                    <p>Transparência e segurança em todas as transações e comunicações.</p>
                </div>
                <div class="value-card">
                    <h3>✨ Personalização</h3>
                    <p>Cada casamento é único e merece um planejamento personalizado.</p>
                </div>
                <div class="value-card">
                    <h3>🎯 Simplicidade</h3>
                    <p>Ferramentas intuitivas que tornam o planejamento fácil e prazeroso.</p>
                </div>
            </div>
        </div>

        <div class="content-section">
            <h2>O Que Oferecemos</h2>
            <p>
                <strong>Para Noivos:</strong> Cronograma completo, curadoria de fornecedores, gestão financeira,
                lista de convidados, contratos digitais e chat integrado com fornecedores.
            </p>
            <p>
                <strong>Para Fornecedores:</strong> Plataforma profissional para gerenciar eventos, receber pagamentos,
                comunicar-se com clientes e expandir seus negócios.
            </p>
        </div>

        <div class="cta-box">
            <h2>Pronto para começar?</h2>
            <p>Junte-se a centenas de casais que já estão planejando o casamento dos sonhos com a Clea.</p>
            <a href="<?= url('register') ?>" class="btn">Criar Conta Gratuita</a>
        </div>
    </div>
</body>
</html>
