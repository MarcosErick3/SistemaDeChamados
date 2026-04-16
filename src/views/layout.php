<?php
$topbarActive = $topbarActive ?? 'chamados';
$topbarUser = $topbarUser ?? ($_SESSION['user'] ?? null);
$pageTitle = $pageTitle ?? 'ServiceDesk';
$pageStyles = $pageStyles ?? [];

function classeNavTopo($pagina, $paginaAtiva)
{
    return 'nav-item' . ($pagina === $paginaAtiva ? ' active' : '');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="css/index.css?v=2">
    <?php foreach ($pageStyles as $stylesheet): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($stylesheet) ?>">
    <?php endforeach; ?>
</head>

<body>
    <div class="topbar">
        <div class="topbar-content">
            <div class="topbar-logo">
                <span class="logo-text">ServiceDesk</span>
            </div>
            <nav class="topbar-nav">
                <a href="index.php?action=dashboard" class="<?= classeNavTopo('dashboard', $topbarActive) ?>">Dashboard</a>
                <a href="index.php?action=chamados" class="<?= classeNavTopo('chamados', $topbarActive) ?>">Chamados</a>
                <a href="index.php?action=historico" class="<?= classeNavTopo('historico', $topbarActive) ?>">Historico</a>
                <a href="index.php?action=perfil" class="<?= classeNavTopo('perfil', $topbarActive) ?>">Perfil</a>
            </nav>
            <div class="topbar-user">
                <?php if (!empty($topbarUser)): ?>
                    <span class="user-badge">
                        <span class="user-name"><?= htmlspecialchars($topbarUser['nome']) ?></span>
                    </span>
                    <a href="index.php?action=sair" class="nav-item logout">Sair</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?= $content ?>

    <footer class="app-footer">
        <div class="container-footer">
            <span>Desenvolvido por Marcos Erick</span>
        </div>
    </footer>
</body>

</html>
