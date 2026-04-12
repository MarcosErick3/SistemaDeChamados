<?php
$topbarActive = 'perfil';
$topbarUser = $user ?? null;
$pageTitle = 'ServiceDesk - Perfil';
ob_start();
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

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
