<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <title>ServiceDesk - Chamado #<?= str_pad($chamado['id'] ?? 0, 5, '0', STR_PAD_LEFT) ?></title>
</head>

<body>
    <div class="topbar">
        <div class="topbar-content">
            <div class="topbar-logo">
                <span class="logo-icon">📋</span>
                <span class="logo-text">ServiceDesk</span>
            </div>
            <nav class="topbar-nav">
                <a href="index.php?action=index" class="nav-item <?= (empty($_GET['meus_chamados']) && empty($_GET['action']) || $_GET['action'] === 'index') ? 'active' : '' ?>">
                    <span class="nav-icon">🔍</span>
                    <span>Pesquisa</span>
                </a>
                <a href="index.php?action=index&meus_chamados=1" class="nav-item <?= (!empty($_GET['meus_chamados'])) ? 'active' : '' ?>">
                    <span class="nav-icon">📝</span>
                    <span>Meus Chamados</span>
                </a>
                <a href="index.php?action=historico" class="nav-item <?= (isset($_GET['action']) && $_GET['action'] === 'historico') ? 'active' : '' ?>">
                    <span class="nav-icon">📚</span>
                    <span>Histórico</span>
                </a>
                <a href="index.php?action=perfil" class="nav-item <?= (isset($_GET['action']) && $_GET['action'] === 'perfil') ? 'active' : '' ?>">
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
            <a href="index.php?action=historico">Histórico</a>
            <span class="separator">/</span>
            <span>Chamado #<?= str_pad($chamado['id'] ?? 0, 5, '0', STR_PAD_LEFT) ?></span>
        </div>
    </div>

    <div class="container">
        <h1 class="page-title">Chamado #<?= str_pad($chamado['id'] ?? 0, 5, '0', STR_PAD_LEFT) ?></h1>

        <div class="section">
            <div class="section-title">Detalhes do Chamado</div>
            <form method="POST" action="index.php?action=update">
                <input type="hidden" name="id" value="<?= $chamado['id'] ?>">
                <div class="grid-2">
                    <div class="field">
                        <label>ID</label>
                        <input type="text" readonly value="#<?= str_pad($chamado['id'] ?? 0, 5, '0', STR_PAD_LEFT) ?>">
                    </div>
                    <div class="field">
                        <label>Status</label>
                        <select name="status" onchange="toggleSolucao()">
                            <option value="ABERTO" <?= ($chamado['status'] === 'ABERTO') ? 'selected' : '' ?>>ABERTO</option>
                            <option value="EM ANDAMENTO" <?= ($chamado['status'] === 'EM ANDAMENTO') ? 'selected' : '' ?>>EM ANDAMENTO</option>
                            <option value="FINALIZADO" <?= ($chamado['status'] === 'FINALIZADO') ? 'selected' : '' ?>>FINALIZADO</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Técnico</label>
                        <input type="text" readonly value="<?= htmlspecialchars($chamado['tecnico_nome'] ?? $chamado['tecnico'] ?? '') ?>">
                    </div>
                    <div class="field">
                        <label>Prioridade</label>
                        <input type="text" readonly value="<?= htmlspecialchars($chamado['prioridade'] ?? '') ?>">
                    </div>
                    <div class="field">
                        <label>Nº Série</label>
                        <input type="text" readonly value="<?= htmlspecialchars($chamado['numero_serie'] ?? '') ?>">
                    </div>
                    <div class="field">
                        <label>Equipamento</label>
                        <input type="text" readonly value="<?= htmlspecialchars($chamado['equipamento'] ?? '') ?>">
                    </div>
                    <div class="field">
                        <label>Local</label>
                        <input type="text" readonly value="<?= htmlspecialchars($chamado['local'] ?? '') ?>">
                    </div>
                    <div class="field">
                        <label>Unidade</label>
                        <input type="text" readonly value="<?= htmlspecialchars($chamado['unidade'] ?? '') ?>">
                    </div>
                    <div class="field">
                        <label>Criado em</label>
                        <input type="text" readonly value="<?= isset($chamado['data_abertura']) ? date('d/m/Y H:i', strtotime($chamado['data_abertura'])) : 'N/A' ?>">
                    </div>
                    <div class="field">
                        <label>Finalizado em</label>
                        <input type="text" readonly value="<?= isset($chamado['data_finalizacao']) ? date('d/m/Y H:i', strtotime($chamado['data_finalizacao'])) : 'N/A' ?>">
                    </div>
                </div>

                <div class="section">
                    <div class="section-title">Informações do Chamado</div>
                    <div class="field full">
                        <label>Assunto</label>
                        <input type="text" readonly value="<?= htmlspecialchars($chamado['assunto'] ?? '') ?>">
                    </div>
                    <div class="field full">
                        <label>Descrição</label>
                        <textarea readonly><?= htmlspecialchars($chamado['descricao'] ?? '') ?></textarea>
                    </div>
                    <div class="field full" id="solucao-field" style="display: none;">
                        <label>Solução</label>
                        <textarea name="solucao"><?= htmlspecialchars($chamado['solucao'] ?? '') ?></textarea>
                    </div>
                    <div class="field full" id="solucao-view">
                        <label>Solução</label>
                        <textarea readonly><?= htmlspecialchars($chamado['solucao'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="section">
                    <div class="section-title">Dados do Usuário</div>
                    <div class="grid-2">
                        <div class="field">
                            <label>Nome</label>
                            <input type="text" readonly value="<?= htmlspecialchars($chamado['nome_usuario'] ?? '') ?>">
                        </div>
                        <div class="field">
                            <label>E-mail</label>
                            <input type="text" readonly value="<?= htmlspecialchars($chamado['email_usuario'] ?? '') ?>">
                        </div>
                        <div class="field">
                            <label>DDD</label>
                            <input type="text" readonly value="<?= htmlspecialchars($chamado['ddd_usuario'] ?? '') ?>">
                        </div>
                        <div class="field">
                            <label>Telefone</label>
                            <input type="text" readonly value="<?= htmlspecialchars($chamado['telefone_usuario'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="index.php?action=historico" class="btn btn-secondary">Voltar</a>
                </div>
            </form>
    </div>
</body>

<script>
    function toggleSolucao() {
        const statusSelect = document.querySelector('select[name="status"]');
        const solucaoField = document.getElementById('solucao-field');
        const solucaoView = document.getElementById('solucao-view');
        
        if (statusSelect.value === 'FINALIZADO') {
            solucaoField.style.display = 'block';
            solucaoView.style.display = 'none';
        } else {
            solucaoField.style.display = 'none';
            solucaoView.style.display = 'block';
        }
    }

    // Executar ao carregar
    toggleSolucao();
</script>

</html>
