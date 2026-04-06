<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Sistema de Chamados</title>
    <link rel="stylesheet" href="../public/css/index.css">
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
                <a href="index.php?action=historico" class="nav-item">
                    <span class="nav-icon">📚</span>
                    <span>Histórico</span>
                </a>
                <a href="index.php?action=perfil" class="nav-item active">
                    <span class="nav-icon">👤</span>
                    <span>Perfil</span>
                </a>
            </nav>
            <div class="topbar-user">
                <?php if(isset($user)): ?>
                    <span class="user-badge">
                        <span class="user-icon">👨‍💼</span>
                        <span class="user-name"><?= htmlspecialchars($user['nome']) ?></span>
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
            <span>Perfil</span>
        </div>
    </div>

    <div class="container">
        <h1 class="page-title">Perfil do Usuário</h1>
        
        <div class="section">
            <div class="section-title">Informações Pessoais</div>
            <div class="profile-info">
                <div class="info-item">
                    <label>Nome:</label>
                    <p><?php echo htmlspecialchars($user['nome']); ?></p>
                </div>
                <div class="info-item">
                    <label>Email:</label>
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                <div class="info-item">
                    <label>Telefone:</label>
                    <p><?php echo htmlspecialchars($user['telefone'] ?? 'Não informado'); ?></p>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Ações</div>
            <div class="actions">
                <a href="index.php" class="btn btn-primary">Voltar aos Chamados</a>
                <a href="index.php?action=logout" class="btn btn-danger">Sair da Conta</a>
            </div>
        </div>
    </div>
</body>
</html>