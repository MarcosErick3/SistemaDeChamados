<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ServiceDesk - Perfil</title>
    <link rel="stylesheet" href="css/index.css?v=2">
</head>

<body>
    <?php
    $topbarActive = 'perfil';
    $topbarUser = $user ?? null;
    require __DIR__ . '/partials/barra-superior.php';
    ?>

    <div class="container">
        <h1 class="page-title">Perfil do Usuario</h1>

        <div class="section">
            <div class="section-title">Informacoes Pessoais</div>
            <div class="profile-info">
                <div class="info-item">
                    <label>Nome:</label>
                    <p><?= htmlspecialchars($user['nome']) ?></p>
                </div>
                <div class="info-item">
                    <label>Email:</label>
                    <p><?= htmlspecialchars($user['email']) ?></p>
                </div>
                <div class="info-item">
                    <label>Telefone:</label>
                    <p><?= htmlspecialchars($user['telefone'] ?? 'Nao informado') ?></p>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Acoes</div>
            <div class="actions">
                <a href="index.php" class="btn btn-primary">Voltar aos Chamados</a>
                <a href="index.php?action=sair" class="btn btn-danger">Sair da Conta</a>
            </div>
        </div>
    </div>
</body>

</html>
