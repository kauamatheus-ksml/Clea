<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $pageTitle ?? 'Dashboard' ?> - Clea Casamentos</title>
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
            --sidebar-bg: #652929;
            --success: #22c55e;
            --warning: #f59e0b;
            --error: #ef4444;
            --info: #3b82f6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            line-height: 1.6;
        }

        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #5a2424 100%);
            color: white;
            padding: 0;
            box-shadow: 4px 0 12px rgba(0, 0, 0, 0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(0, 0, 0, 0.1);
        }

        .sidebar-header h1 {
            font-family: 'Lora', serif;
            font-size: 28px;
            margin-bottom: 4px;
        }

        .sidebar-header .subtitle {
            font-size: 14px;
            opacity: 0.8;
            font-weight: 300;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-section {
            margin-bottom: 24px;
        }

        .nav-section-title {
            padding: 0 20px 8px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.6;
        }

        .nav-item {
            display: block;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-item:hover,
        .nav-item.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: var(--primary-medium);
            color: white;
        }

        .nav-item.active {
            background-color: rgba(242, 171, 177, 0.2);
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            min-height: 100vh;
            background-color: var(--background);
        }

        .main-header {
            background: white;
            padding: 16px 24px;
            border-bottom: 1px solid rgba(242, 171, 177, 0.2);
            display: flex;
            justify-content: between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(101, 41, 41, 0.05);
        }

        .main-header h1 {
            font-family: 'Lora', serif;
            font-size: 24px;
            color: var(--text-primary);
            flex: 1;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background-color: var(--primary-light);
            border-radius: 12px;
            border: 1px solid rgba(242, 171, 177, 0.3);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary-medium), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 500;
            font-size: 14px;
            color: var(--text-primary);
        }

        .user-role {
            font-size: 12px;
            color: var(--text-secondary);
            text-transform: capitalize;
        }

        .logout-btn {
            background: linear-gradient(135deg, var(--primary-medium), var(--primary-dark));
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(242, 171, 177, 0.4);
        }

        .content-area {
            padding: 24px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid rgba(242, 171, 177, 0.2);
            box-shadow: 0 4px 12px rgba(101, 41, 41, 0.08);
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(101, 41, 41, 0.15);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .stat-title {
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-medium), rgba(242, 171, 177, 0.7));
            color: white;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-dark);
            margin-bottom: 4px;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 14px;
        }

        /* Content Sections */
        .content-section {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid rgba(242, 171, 177, 0.2);
            box-shadow: 0 4px 12px rgba(101, 41, 41, 0.08);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid rgba(242, 171, 177, 0.2);
        }

        .section-title {
            font-family: 'Lora', serif;
            font-size: 20px;
            color: var(--text-primary);
        }

        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        .data-table th,
        .data-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid rgba(242, 171, 177, 0.2);
        }

        .data-table th {
            background-color: var(--primary-light);
            font-weight: 600;
            color: var(--text-primary);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table tr:hover {
            background-color: rgba(242, 171, 177, 0.05);
        }

        /* Status Badges */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active { background-color: rgba(34, 197, 94, 0.1); color: var(--success); }
        .status-pending { background-color: rgba(245, 158, 11, 0.1); color: var(--warning); }
        .status-completed { background-color: rgba(59, 130, 246, 0.1); color: var(--info); }
        .status-cancelled { background-color: rgba(239, 68, 68, 0.1); color: var(--error); }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-medium), var(--primary-dark));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(242, 171, 177, 0.4);
        }

        .btn-secondary {
            background-color: var(--primary-light);
            color: var(--text-primary);
            border: 1px solid rgba(242, 171, 177, 0.3);
        }

        .btn-secondary:hover {
            background-color: var(--primary-medium);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

            .mobile-overlay.active {
                display: block;
            }

            .mobile-menu-btn {
                display: block;
                background: none;
                border: none;
                color: var(--text-primary);
                font-size: 20px;
                cursor: pointer;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .content-area {
                padding: 16px;
            }
        }

        @media (min-width: 769px) {
            .mobile-menu-btn {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-layout">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h1>Clea</h1>
                <div class="subtitle"><?= $sidebarTitle ?? 'Dashboard' ?></div>
            </div>

            <nav class="sidebar-nav">
                <?= $sidebarMenu ?? '' ?>
            </nav>
        </aside>

        <!-- Mobile Overlay -->
        <div class="mobile-overlay" id="mobileOverlay"></div>

        <!-- Main Content -->
        <main class="main-content">
            <header class="main-header">
                <button class="mobile-menu-btn" id="mobileMenuBtn">☰</button>
                <h1><?= $pageTitle ?? 'Dashboard' ?></h1>
                <div class="header-actions">
                    <div class="user-menu">
                        <div class="user-avatar">
                            <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                        </div>
                        <div class="user-info">
                            <div class="user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuário') ?></div>
                            <div class="user-role"><?= htmlspecialchars($_SESSION['user_role'] ?? 'user') ?></div>
                        </div>
                    </div>
                    <form action="auth.php" method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="logout">
                        <button type="submit" class="logout-btn">Sair</button>
                    </form>
                </div>
            </header>

            <div class="content-area">
                <?= $content ?? '' ?>
            </div>
        </main>
    </div>

    <script>
        // Mobile menu toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const sidebar = document.getElementById('sidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');

            function toggleMobileMenu() {
                sidebar.classList.toggle('mobile-open');
                mobileOverlay.classList.toggle('active');
            }

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', toggleMobileMenu);
            }

            if (mobileOverlay) {
                mobileOverlay.addEventListener('click', toggleMobileMenu);
            }

            // Set active navigation item
            const currentPath = window.location.search.match(/url=([^&]+)/);
            if (currentPath) {
                const navItems = document.querySelectorAll('.nav-item');
                navItems.forEach(item => {
                    if (item.getAttribute('href').includes(currentPath[1])) {
                        item.classList.add('active');
                    }
                });
            }
        });
    </script>
</body>
</html>