<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Clea Casamentos</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .header { background: #2c3e50; color: white; padding: 20px; margin: -20px -20px 20px -20px; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .stat-number { font-size: 2rem; font-weight: bold; color: #3498db; }
        .stat-label { color: #666; margin-top: 5px; }
        .section { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .section h3 { margin-top: 0; color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: bold; }
        .nav { margin-bottom: 20px; }
        .nav a { display: inline-block; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; margin-right: 10px; border-radius: 5px; }
        .nav a:hover { background: #2980b9; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Dashboard Administrativo</h1>
        <p>Gestão completa da plataforma Clea Casamentos</p>
    </div>

    <div class="nav">
        <a href="?url=/admin/dashboard">Dashboard</a>
        <a href="?url=/admin/users">Usuários</a>
        <a href="?url=/admin/vendors">Fornecedores</a>
        <a href="?url=/admin/financial">Financeiro</a>
        <a href="?url=/admin/contracts">Contratos</a>
        <a href="?url=/admin/messages">Mensagens</a>
        <a href="../login.php">Sair</a>
    </div>

    <div class="stats">
        <div class="stat-card">
            <div class="stat-number"><?= $stats['users']['client'] ?? 0 ?></div>
            <div class="stat-label">Clientes</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= $stats['users']['vendor'] ?? 0 ?></div>
            <div class="stat-label">Fornecedores</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= $stats['weddings'] ?? 0 ?></div>
            <div class="stat-label">Casamentos</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= $stats['active_contracts'] ?? 0 ?></div>
            <div class="stat-label">Contratos Ativos</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= $stats['pending_vendors'] ?? 0 ?></div>
            <div class="stat-label">Fornecedores Pendentes</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">R$ <?= number_format($stats['monthly_revenue'] ?? 0, 2, ',', '.') ?></div>
            <div class="stat-label">Receita Mensal</div>
        </div>
    </div>

    <div class="section">
        <h3>Clientes Recentes</h3>
        <table>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Parceiro(a)</th>
                <th>Data do Casamento</th>
                <th>Cadastro</th>
            </tr>
            <?php foreach ($recentClients as $client): ?>
            <tr>
                <td><?= htmlspecialchars($client['name']) ?></td>
                <td><?= htmlspecialchars($client['email']) ?></td>
                <td><?= htmlspecialchars($client['partner_name'] ?? 'Não informado') ?></td>
                <td><?= $client['wedding_date'] ? date('d/m/Y', strtotime($client['wedding_date'])) : 'Não definida' ?></td>
                <td><?= date('d/m/Y', strtotime($client['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="section">
        <h3>Fornecedores Pendentes de Aprovação</h3>
        <table>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Empresa</th>
                <th>Categoria</th>
                <th>Cadastro</th>
            </tr>
            <?php foreach ($pendingVendors as $vendor): ?>
            <tr>
                <td><?= htmlspecialchars($vendor['name']) ?></td>
                <td><?= htmlspecialchars($vendor['email']) ?></td>
                <td><?= htmlspecialchars($vendor['business_name']) ?></td>
                <td><?= htmlspecialchars($vendor['category']) ?></td>
                <td><?= date('d/m/Y', strtotime($vendor['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="section">
        <h3>Contratos Recentes</h3>
        <table>
            <tr>
                <th>Cliente</th>
                <th>Fornecedor</th>
                <th>Data do Casamento</th>
                <th>Valor</th>
                <th>Status</th>
                <th>Data do Contrato</th>
            </tr>
            <?php foreach ($recentContracts as $contract): ?>
            <tr>
                <td><?= htmlspecialchars($contract['client_name']) ?></td>
                <td><?= htmlspecialchars($contract['vendor_name']) ?></td>
                <td><?= date('d/m/Y', strtotime($contract['wedding_date'])) ?></td>
                <td>R$ <?= number_format($contract['total_value'], 2, ',', '.') ?></td>
                <td><?= ucfirst($contract['status']) ?></td>
                <td><?= date('d/m/Y', strtotime($contract['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>