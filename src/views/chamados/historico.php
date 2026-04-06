<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <title>ServiceDesk - Histórico</title>
</head>

<body>

    <div class="topbar">
        <div class="topbar-content">
            <div class="topbar-logo">
                <span class="logo-icon">📋</span>
                <span class="logo-text">ServiceDesk</span>
            </div>
            <nav class="topbar-nav">
                <a href="index.php?action=index" class="nav-item">
                    <span class="nav-icon">🔍</span>
                    <span>Pesquisa</span>
                </a>
                <a href="index.php?action=index&meus_chamados=1" class="nav-item">
                    <span class="nav-icon">📝</span>
                    <span>Meus Chamados</span>
                </a>
                <a href="index.php?action=historico" class="nav-item active">
                    <span class="nav-icon">📚</span>
                    <span>Histórico</span>
                </a>
                <a href="index.php?action=perfil" class="nav-item">
                    <span class="nav-icon">👤</span>
                    <span>Perfil</span>
                </a>
            </nav>
            <div class="topbar-user">
                <?php if(isset($_SESSION['user'])): ?>
                    <span class="user-badge">
                        <span class="user-icon">👨‍💼</span>
                        <span class="user-name"><?= htmlspecialchars($_SESSION['user']['nome']) ?></span>
                    </span>
                    <a href="index.php?action=logout" class="nav-item logout">
                        <span class="nav-icon">🚪</span>
                        <span>Sair</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="breadcrumb-bar">
        <div class="breadcrumb">
            <a href="index.php?action=index">📊 Início</a>
            <span class="separator">/</span>
            <span>Histórico</span>
        </div>
    </div>

    <div class="container">
        <h1 class="page-title">ServiceDesk - Histórico de Chamados</h1>

        <div class="section">
            <div class="section-title">Filtro de Histórico</div>
            <form method="GET" action="index.php?action=historico" class="filter-form">
                <input type="hidden" name="action" value="historico">
                
                <div class="field">
                    <label>Status</label>
                    <select name="status">
                        <option value="" <?= empty($_GET['status']) ? 'selected' : '' ?>>Todos</option>
                        <option value="ABERTO" <?= (($_GET['status'] ?? '') === 'ABERTO') ? 'selected' : '' ?>>ABERTO</option>
                        <option value="EM ANDAMENTO" <?= (($_GET['status'] ?? '') === 'EM ANDAMENTO') ? 'selected' : '' ?>>EM ANDAMENTO</option>
                        <option value="FINALIZADO" <?= (($_GET['status'] ?? '') === 'FINALIZADO') ? 'selected' : '' ?>>FINALIZADO</option>
                    </select>
                </div>

                <div class="field">
                    <label>Técnico</label>
                    <select name="tecnico_filter">
                        <option value="">Todos</option>
                        <?php foreach ($tecnicos as $tecnico): ?>
                            <option value="<?= htmlspecialchars($tecnico['id']) ?>" 
                                <?= (($_GET['tecnico_filter'] ?? '') === (string)$tecnico['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($tecnico['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field">
                    <label>Nº Série</label>
                    <input type="text" name="numero_serie" placeholder="Buscar por número de série" value="<?= htmlspecialchars($_GET['numero_serie'] ?? '') ?>">
                </div>

                <div class="field">
                    <label>ID/Número do Chamado</label>
                    <input type="text" name="chamado_id" placeholder="Ex.: 1 ou 00001" value="<?= htmlspecialchars($_GET['chamado_id'] ?? '') ?>">
                </div>

                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
        </div>

        <div class="section">
            <div class="section-title">Histórico de Chamados</div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Assunto</th>
                            <th>Local</th>
                            <th>Técnico</th>
                            <th>Prioridade</th>
                            <th>Criado em</th>
                            <th>Finalizado em</th>
                            <th>Solução</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($chamados)): ?>
                            <?php foreach ($chamados as $chamado): ?>
                                <tr>
                                    <td>
                                        <a href="index.php?action=show&id=<?= $chamado['id'] ?>">#<?= str_pad($chamado['id'] ?? 0, 5, '0', STR_PAD_LEFT) ?></a>
                                    </td>
                                    <td>
                                        <a href="index.php?action=show&id=<?= $chamado['id'] ?>"><?= htmlspecialchars($chamado['assunto'] ?? '') ?></a>
                                    </td>
                                    <td><?= htmlspecialchars($chamado['local'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($chamado['tecnico_nome'] ?? $chamado['tecnico'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($chamado['prioridade'] ?? '') ?></td>
                                    <td><?= isset($chamado['data_abertura']) ? date('d/m/Y H:i', strtotime($chamado['data_abertura'])) : 'N/A' ?></td>
                                    <td><?= isset($chamado['data_finalizacao']) ? date('d/m/Y H:i', strtotime($chamado['data_finalizacao'])) : 'N/A' ?></td>
                                    <td><?= htmlspecialchars(substr($chamado['solucao'] ?? '', 0, 50)) . (strlen($chamado['solucao'] ?? '') > 50 ? '...' : '') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 20px;">Nenhum chamado finalizado encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
