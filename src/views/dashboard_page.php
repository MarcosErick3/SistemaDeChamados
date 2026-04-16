<?php
$topbarActive = 'dashboard';
$topbarUser = $_SESSION['user'] ?? null;
$pageTitle = 'ServiceDesk - Dashboard';
$pageStyles = ['css/dashboard.css?v=1'];
ob_start();

$statusMap = [
    'ABERTO' => 'Abertos',
    'EM ANDAMENTO' => 'Em andamento',
    'FINALIZADO' => 'Finalizados',
];

$priorityMap = [
    'CRITICA' => 'Critica',
    'ALTA' => 'Alta',
    'MEDIA' => 'Media',
    'BAIXA' => 'Baixa',
];

$formatarTexto = static function ($valor, $fallback = '--') {
    $texto = trim((string) $valor);
    return $texto !== '' ? $texto : $fallback;
};

$formatarData = static function ($valor, $formato = 'd/m/Y H:i') {
    if (empty($valor)) {
        return '--';
    }

    $timestamp = strtotime((string) $valor);
    return $timestamp ? date($formato, $timestamp) : '--';
};

$calcularPercentual = static function ($parte, $total) {
    if ((int) $total <= 0) {
        return 0;
    }

    return (int) round(((int) $parte / (int) $total) * 100);
};

$normalizarStatus = static function ($status) {
    return strtoupper(trim((string) $status));
};

$normalizarPrioridade = static function ($prioridade) {
    $valor = strtoupper(trim((string) $prioridade));
    return $valor !== '' ? $valor : 'MEDIA';
};

$obterDataCriacao = static function (array $chamado) {
    return $chamado['data_criacao'] ?? $chamado['data_abertura'] ?? null;
};

$totalChamados = count($chamados);
$statusCounts = array_fill_keys(array_keys($statusMap), 0);
$priorityCounts = array_fill_keys(array_keys($priorityMap), 0);
$chamadosPorTecnico = [];
$alertas = [];
$diasAtividade = [];

for ($i = 6; $i >= 0; $i--) {
    $dataBase = date('Y-m-d', strtotime("-{$i} days"));
    $diasAtividade[$dataBase] = [
        'label' => date('d/m', strtotime($dataBase)),
        'criados' => 0,
        'finalizados' => 0,
    ];
}

foreach ($chamados as $chamado) {
    $status = $normalizarStatus($chamado['status'] ?? 'ABERTO');
    if (isset($statusCounts[$status])) {
        $statusCounts[$status]++;
    }

    $prioridade = $normalizarPrioridade($chamado['prioridade'] ?? 'MEDIA');
    if (isset($priorityCounts[$prioridade])) {
        $priorityCounts[$prioridade]++;
    }

    $tecnico = $formatarTexto($chamado['tecnico_nome'] ?? $chamado['tecnico'] ?? '', 'Sem atribuicao');
    $chamadosPorTecnico[$tecnico] = ($chamadosPorTecnico[$tecnico] ?? 0) + 1;

    if ($status !== 'FINALIZADO' && in_array($prioridade, ['CRITICA', 'ALTA'], true)) {
        $alertas[] = $chamado;
    }

    $dataCriacaoBase = $obterDataCriacao($chamado);
    $dataCriacaoTimestamp = !empty($dataCriacaoBase) ? strtotime((string) $dataCriacaoBase) : false;
    $dataCriacao = $dataCriacaoTimestamp ? date('Y-m-d', $dataCriacaoTimestamp) : null;
    if ($dataCriacao && isset($diasAtividade[$dataCriacao])) {
        $diasAtividade[$dataCriacao]['criados']++;
    }

    $dataFinalizacaoTimestamp = !empty($chamado['data_finalizacao']) ? strtotime((string) $chamado['data_finalizacao']) : false;
    $dataFinalizacao = $dataFinalizacaoTimestamp ? date('Y-m-d', $dataFinalizacaoTimestamp) : null;
    if ($dataFinalizacao && isset($diasAtividade[$dataFinalizacao])) {
        $diasAtividade[$dataFinalizacao]['finalizados']++;
    }
}

arsort($chamadosPorTecnico);

$chamadosAbertos = $statusCounts['ABERTO'];
$chamadosAndamento = $statusCounts['EM ANDAMENTO'];
$chamadosFinalizados = $statusCounts['FINALIZADO'];
$backlog = $chamadosAbertos + $chamadosAndamento;
$taxaResolucao = $calcularPercentual($chamadosFinalizados, $totalChamados);
$semAtribuicao = $chamadosPorTecnico['Sem atribuicao'] ?? 0;
$tecnicosAtivos = count(array_filter(array_keys($chamadosPorTecnico), static fn($nome) => $nome !== 'Sem atribuicao'));
$principaisTecnicos = array_slice($chamadosPorTecnico, 0, 5, true);
$maxTecnico = !empty($principaisTecnicos) ? max($principaisTecnicos) : 0;
$recentes = array_slice($chamados, 0, 10);
$totalAlertas = count($alertas);
$alertas = array_slice($alertas, 0, 6);
$maiorAtividade = 0;

foreach ($diasAtividade as $dia) {
    $maiorAtividade = max($maiorAtividade, $dia['criados'], $dia['finalizados']);
}
?>

<div class="container dashboard-page">
    <div class="dashboard-shell">
        <section class="dashboard-hero">
            <div>
                <h1 class="page-title">Dashboard operacional</h1>
                <p>Visao geral do ServiceDesk com foco em volume, backlog, prioridades e ritmo de atendimento dos chamados.</p>
                <div class="dashboard-meta">
                    <span class="dashboard-chip">Atualizado em <?= date('d/m/Y H:i') ?></span>
                    <span class="dashboard-chip">Taxa de resolucao: <?= $taxaResolucao ?>%</span>
                    <span class="dashboard-chip">Tecnicos ativos: <?= $tecnicosAtivos ?></span>
                </div>
            </div>

            <div class="dashboard-actions">
                <a href="index.php?action=chamados" class="btn btn-secondary">Abrir chamados</a>
                <a href="index.php?action=historico" class="btn btn-primary">Ver historico</a>
            </div>
        </section>

        <section class="dashboard-kpis">
            <article class="dashboard-kpi">
                <div class="dashboard-kpi-label">Total de chamados</div>
                <div class="dashboard-kpi-value"><?= $totalChamados ?></div>
                <div class="dashboard-kpi-help">Base completa carregada pela dashboard.</div>
            </article>

            <article class="dashboard-kpi">
                <div class="dashboard-kpi-label">Backlog</div>
                <div class="dashboard-kpi-value"><?= $backlog ?></div>
                <div class="dashboard-kpi-help"><?= $chamadosAbertos ?> abertos e <?= $chamadosAndamento ?> em andamento.</div>
            </article>

            <article class="dashboard-kpi">
                <div class="dashboard-kpi-label">Finalizados</div>
                <div class="dashboard-kpi-value"><?= $chamadosFinalizados ?></div>
                <div class="dashboard-kpi-help">Percentual resolvido sobre o total: <?= $taxaResolucao ?>%.</div>
            </article>

            <article class="dashboard-kpi">
                <div class="dashboard-kpi-label">Prioridade critica</div>
                <div class="dashboard-kpi-value"><?= $priorityCounts['CRITICA'] ?></div>
                <div class="dashboard-kpi-help">Chamados que pedem atencao imediata.</div>
            </article>
        </section>

        <section class="dashboard-panels">
            <article class="dashboard-panel">
                <h2 class="dashboard-panel-title">Status dos chamados</h2>
                <p class="dashboard-panel-subtitle">Distribuicao atual da operacao.</p>

                <div class="dashboard-stack">
                    <?php foreach ($statusMap as $statusKey => $statusLabel): ?>
                        <?php $percentual = $calcularPercentual($statusCounts[$statusKey], $totalChamados); ?>
                        <div>
                            <div class="dashboard-row">
                                <span><?= htmlspecialchars($statusLabel) ?></span>
                                <strong><?= $statusCounts[$statusKey] ?> (<?= $percentual ?>%)</strong>
                            </div>
                            <div class="dashboard-meter dashboard-meter-status-<?= strtolower(str_replace(' ', '-', $statusKey)) ?>">
                                <span data-width="<?= $percentual ?>"></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="dashboard-highlight">
                    <div class="dashboard-highlight-card">
                        <span>Sem atribuicao</span>
                        <strong><?= $semAtribuicao ?></strong>
                    </div>
                    <div class="dashboard-highlight-card">
                        <span>Em andamento</span>
                        <strong><?= $chamadosAndamento ?></strong>
                    </div>
                </div>
            </article>

            <article class="dashboard-panel">
                <h2 class="dashboard-panel-title">Prioridades</h2>
                <p class="dashboard-panel-subtitle">Peso das urgencias na fila atual.</p>

                <div class="dashboard-stack">
                    <?php foreach ($priorityMap as $priorityKey => $priorityLabel): ?>
                        <?php $percentual = $calcularPercentual($priorityCounts[$priorityKey], $totalChamados); ?>
                        <div>
                            <div class="dashboard-row">
                                <span><?= htmlspecialchars($priorityLabel) ?></span>
                                <strong><?= $priorityCounts[$priorityKey] ?> (<?= $percentual ?>%)</strong>
                            </div>
                            <div class="dashboard-meter dashboard-meter-prioridade-<?= strtolower($priorityKey) ?>">
                                <span data-width="<?= $percentual ?>"></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="dashboard-highlight">
                    <div class="dashboard-highlight-card">
                        <span>Abertos urgentes</span>
                        <strong><?= $totalAlertas ?></strong>
                    </div>
                    <div class="dashboard-highlight-card">
                        <span>Taxa resolvida</span>
                        <strong><?= $taxaResolucao ?>%</strong>
                    </div>
                </div>
            </article>
        </section>

        <section class="dashboard-panels">
            <article class="dashboard-panel">
                <h2 class="dashboard-panel-title">Carga por tecnico</h2>
                <p class="dashboard-panel-subtitle">Top 5 tecnicos com mais chamados vinculados.</p>

                <?php if (!empty($principaisTecnicos)): ?>
                    <div class="dashboard-stack">
                        <?php foreach ($principaisTecnicos as $tecnico => $quantidade): ?>
                            <?php $percentual = $maxTecnico > 0 ? (int) round(($quantidade / $maxTecnico) * 100) : 0; ?>
                            <div>
                                <div class="dashboard-row">
                                    <span><?= htmlspecialchars($tecnico) ?></span>
                                    <strong><?= $quantidade ?></strong>
                                </div>
                                <div class="dashboard-meter dashboard-meter-prioridade-media">
                                    <span data-width="<?= $percentual ?>"></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="dashboard-empty">Nao ha chamados suficientes para montar a carga por tecnico.</div>
                <?php endif; ?>
            </article>

            <article class="dashboard-panel">
                <h2 class="dashboard-panel-title">Atividade dos ultimos 7 dias</h2>
                <p class="dashboard-panel-subtitle">Comparativo entre chamados criados e finalizados.</p>

                <div class="dashboard-activity">
                    <?php foreach ($diasAtividade as $dia): ?>
                        <?php
                        $larguraCriados = $maiorAtividade > 0 ? (int) round(($dia['criados'] / $maiorAtividade) * 100) : 0;
                        $larguraFinalizados = $maiorAtividade > 0 ? (int) round(($dia['finalizados'] / $maiorAtividade) * 100) : 0;
                        ?>
                        <div class="dashboard-activity-row">
                            <div class="dashboard-activity-label"><?= $dia['label'] ?></div>
                            <div class="dashboard-activity-bars">
                                <div class="dashboard-activity-bar">
                                    <span>Criados</span>
                                    <div class="dashboard-activity-track is-created">
                                        <span data-width="<?= $larguraCriados ?>"></span>
                                    </div>
                                    <strong><?= $dia['criados'] ?></strong>
                                </div>
                                <div class="dashboard-activity-bar">
                                    <span>Finalizados</span>
                                    <div class="dashboard-activity-track is-finished">
                                        <span data-width="<?= $larguraFinalizados ?>"></span>
                                    </div>
                                    <strong><?= $dia['finalizados'] ?></strong>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </article>
        </section>

        <section class="section">
            <div class="section-title">Fila prioritaria</div>

            <?php if (!empty($alertas)): ?>
                <div class="dashboard-alert-list">
                    <?php foreach ($alertas as $chamado): ?>
                        <?php
                        $status = $normalizarStatus($chamado['status'] ?? 'ABERTO');
                        $prioridade = $normalizarPrioridade($chamado['prioridade'] ?? 'MEDIA');
                        ?>
                        <article class="dashboard-alert">
                            <div class="dashboard-alert-top">
                                <h3 class="dashboard-alert-title">
                                    #<?= str_pad((string) ($chamado['id'] ?? 0), 5, '0', STR_PAD_LEFT) ?>
                                    - <?= htmlspecialchars($formatarTexto($chamado['assunto'] ?? '', 'Sem assunto')) ?>
                                </h3>
                                <a href="index.php?action=detalhes&id=<?= (int) ($chamado['id'] ?? 0) ?>" class="btn btn-sm btn-primary">Abrir</a>
                            </div>

                            <div class="dashboard-alert-meta">
                                <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $status)) ?>"><?= htmlspecialchars($status) ?></span>
                                <span class="priority-badge priority-<?= strtolower($prioridade) ?>"><?= htmlspecialchars($priorityMap[$prioridade] ?? $prioridade) ?></span>
                                <span>Tecnico: <?= htmlspecialchars($formatarTexto($chamado['tecnico_nome'] ?? $chamado['tecnico'] ?? '', 'Sem atribuicao')) ?></span>
                                <span>Equipamento: <?= htmlspecialchars($formatarTexto($chamado['equipamento'] ?? $chamado['descricao_equipamento'] ?? '', '--')) ?></span>
                                <span>Criado em <?= $formatarData($obterDataCriacao($chamado)) ?></span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="dashboard-empty">Nenhum chamado urgente aberto neste momento.</div>
            <?php endif; ?>
        </section>

        <section class="section">
            <div class="section-title">Ultimos chamados</div>

            <div class="table-responsive">
                <table class="table dashboard-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Assunto</th>
                            <th>Status</th>
                            <th>Prioridade</th>
                            <th>Tecnico</th>
                            <th>Equipamento</th>
                            <th>Data</th>
                            <th>Acao</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentes)): ?>
                            <?php foreach ($recentes as $chamado): ?>
                                <?php
                                $status = $normalizarStatus($chamado['status'] ?? 'ABERTO');
                                $prioridade = $normalizarPrioridade($chamado['prioridade'] ?? 'MEDIA');
                                ?>
                                <tr>
                                    <td><strong>#<?= str_pad((string) ($chamado['id'] ?? 0), 5, '0', STR_PAD_LEFT) ?></strong></td>
                                    <td>
                                        <?= htmlspecialchars($formatarTexto($chamado['assunto'] ?? '', 'Sem assunto')) ?>
                                        <small><?= htmlspecialchars($formatarTexto($chamado['nome_usuario'] ?? '', 'Solicitante nao informado')) ?></small>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $status)) ?>">
                                            <?= htmlspecialchars($status) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="priority-badge priority-<?= strtolower($prioridade) ?>">
                                            <?= htmlspecialchars($priorityMap[$prioridade] ?? $prioridade) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($formatarTexto($chamado['tecnico_nome'] ?? $chamado['tecnico'] ?? '', 'Sem atribuicao')) ?></td>
                                    <td><?= htmlspecialchars($formatarTexto($chamado['equipamento'] ?? $chamado['descricao_equipamento'] ?? '', '--')) ?></td>
                                    <td><?= $formatarData($obterDataCriacao($chamado)) ?></td>
                                    <td>
                                        <a href="index.php?action=detalhes&id=<?= (int) ($chamado['id'] ?? 0) ?>" class="btn btn-sm btn-primary">Ver</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">Nenhum chamado encontrado para exibir na dashboard.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dashboardBars = document.querySelectorAll('.dashboard-meter > span[data-width], .dashboard-activity-track > span[data-width]');

        dashboardBars.forEach(function(bar) {
            const width = Number(bar.dataset.width || 0);
            bar.style.width = width + '%';
        });
    });
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
