<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Fornecedores - Clea Casamentos' ?></title>

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
            background: var(--bg-light);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Lora', serif;
            font-weight: 600;
        }

        /* Header */
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

        /* Main Content */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .page-header h1 {
            font-size: 2.5rem;
            color: var(--secondary);
            margin-bottom: 0.5rem;
        }

        .page-header p {
            font-size: 1.1rem;
            color: var(--text-light);
        }

        /* Filters */
        .filters {
            background: var(--white);
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .filter-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .filter-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-dark);
        }

        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: var(--primary);
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
            font-size: 1rem;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--white);
        }

        .btn-primary:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }

        /* Vendors Grid */
        .vendors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }

        .vendor-card {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s;
            text-decoration: none;
            color: inherit;
            display: block;
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

        .no-results {
            text-align: center;
            padding: 3rem;
            color: var(--text-light);
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .vendors-grid {
                grid-template-columns: 1fr;
            }

            .filter-form {
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
                <li><a href="<?= url('login.php') ?>">Entrar</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="container">
        <div class="page-header">
            <h1>Nossos Fornecedores</h1>
            <p>Profissionais cuidadosamente selecionados para o seu casamento dos sonhos</p>
        </div>

        <!-- Filters -->
        <div class="filters">
            <form method="GET" action="/vendors" class="filter-form">
                <div class="filter-group">
                    <label for="category">Categoria</label>
                    <select name="category" id="category">
                        <option value="">Todas as categorias</option>
                        <?php foreach ($data['categories'] as $cat): ?>
                            <option value="<?= $cat['category'] ?>" <?= ($_GET['category'] ?? '') === $cat['category'] ? 'selected' : '' ?>>
                                <?= ucfirst($cat['category']) ?> (<?= $cat['count'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="city">Cidade</label>
                    <select name="city" id="city">
                        <option value="">Todas as cidades</option>
                        <?php foreach ($data['cities'] as $cityData): ?>
                            <option value="<?= $cityData['city'] ?>" <?= ($_GET['city'] ?? '') === $cityData['city'] ? 'selected' : '' ?>>
                                <?= $cityData['city'] ?> (<?= $cityData['count'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="search">Buscar</label>
                    <input type="text" name="search" id="search" placeholder="Nome ou palavra-chave..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>

                <div class="filter-group" style="display: flex; align-items: flex-end;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Filtrar</button>
                </div>
            </form>
        </div>

        <!-- Vendors Grid -->
        <?php if (!empty($data['vendors'])): ?>
            <div class="vendors-grid">
                <?php foreach ($data['vendors'] as $vendor): ?>
                    <a href="<?= url('vendor-detail?id=' . $vendor['id']) ?>" class="vendor-card">
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
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                <p>Nenhum fornecedor encontrado com os filtros selecionados.</p>
                <p><a href="<?= url('vendors') ?>" class="btn btn-primary" style="margin-top: 1rem;">Ver todos</a></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
