<?php
$topbarActive = 'perfil';
$topbarUser = $user ?? null;
$pageTitle = 'ServiceDesk - Perfil';
$pageStyles = ['css/perfil.css?v=1'];
ob_start();
?>

<div class="container">
    <h1 class="page-title">👤 Meu Perfil</h1>

    <div class="profile-header">
        <div class="profile-avatar">
            <?= strtoupper(substr($user['nome'], 0, 1)) ?>
        </div>
        <div class="profile-heading">
            <h2 class="profile-name"><?= htmlspecialchars($user['nome']) ?></h2>
            <p class="profile-role">👨‍💼 Técnico do Sistema</p>
        </div>
    </div>

    <div class="profile-grid">
        <div class="profile-card profile-card-email">
            <h3 class="profile-card-title">📧 Email</h3>
            <p class="profile-card-value profile-card-value-break"><?= htmlspecialchars($user['email']) ?></p>
        </div>

        <div class="profile-card profile-card-phone">
            <h3 class="profile-card-title">📱 Telefone</h3>
            <p class="profile-card-value"><?= htmlspecialchars($user['telefone'] ?? '—') ?></p>
        </div>

        <div class="profile-card profile-card-member">
            <h3 class="profile-card-title">📅 Membro Desde</h3>
            <p class="profile-card-value"><?= date('d/m/Y') ?></p>
        </div>
    </div>

    <div class="section profile-actions-section">
        <div class="section-title">⚙️ Ações Rápidas</div>
        <div class="actions profile-actions">
            <a href="index.php?action=dashboard" class="btn btn-primary profile-action">
                📊 Ver Dashboard
            </a>
            <a href="index.php?action=chamados" class="btn btn-secondary profile-action">
                🔍 Voltar aos Chamados
            </a>
            <a href="index.php?action=sair" class="btn btn-danger profile-action">
                🚪 Sair da Conta
            </a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
