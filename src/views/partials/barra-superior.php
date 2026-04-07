<?php
$topbarActive = $topbarActive ?? 'listar';
$topbarUser = $topbarUser ?? ($_SESSION['user'] ?? null);

function classeNavTopo($pagina, $paginaAtiva)
{
    return 'nav-item' . ($pagina === $paginaAtiva ? ' active' : '');
}
?>
<div class="topbar">
    <div class="topbar-content">
        <div class="topbar-logo">
            <span class="logo-text">ServiceDesk</span>
        </div>
        <nav class="topbar-nav">
            <a href="index.php?action=listar" class="<?= classeNavTopo('listar', $topbarActive) ?>">
                Pesquisa
            </a>
            <a href="index.php?action=listar&meus_chamados=1" class="<?= classeNavTopo('meus_chamados', $topbarActive) ?>">
                Meus Chamados
            </a>
            <a href="index.php?action=historico" class="<?= classeNavTopo('historico', $topbarActive) ?>">
                Historico
            </a>
            <a href="index.php?action=perfil" class="<?= classeNavTopo('perfil', $topbarActive) ?>">
                Perfil
            </a>
        </nav>
        <div class="topbar-user">
            <?php if (!empty($topbarUser)): ?>
                <span class="user-badge">
                    <span class="user-name"><?= htmlspecialchars($topbarUser['nome']) ?></span>
                </span>
                <a href="index.php?action=sair" class="nav-item logout">
                    Sair
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
