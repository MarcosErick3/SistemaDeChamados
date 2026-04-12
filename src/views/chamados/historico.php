<?php
$topbarActive = 'historico';
$topbarUser = $_SESSION['user'] ?? null;
$pageTitle = 'ServiceDesk - Historico';
ob_start();
?>

<div class="container">
    <h1 class="page-title">ServiceDesk - Historico de Chamados</h1>

    <?php if (!empty($_GET['deleted'])): ?>
        <div class="alert alert-success" style="margin-bottom: 20px;">
            Chamado exclu&iacute;do com sucesso.
        </div>
    <?php endif; ?>

    <div class="section">
        <div class="section-title">Filtro de Historico</div>
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
                <label>Tecnico</label>
                <select name="tecnico_filter">
                    <option value="">Todos</option>
                    <?php foreach ($tecnicos as $tecnico): ?>
                        <option value="<?= htmlspecialchars($tecnico['id']) ?>" <?= (($_GET['tecnico_filter'] ?? '') === (string) $tecnico['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($tecnico['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="field">
                <label>Numero Serie</label>
                <input type="text" name="numero_serie" placeholder="Buscar por numero de serie" value="<?= htmlspecialchars($_GET['numero_serie'] ?? '') ?>">
            </div>

            <div class="field">
                <label>ID/Numero do Chamado</label>
                <input type="text" name="chamado_id" placeholder="Ex.: 1 ou 00001" value="<?= htmlspecialchars($_GET['chamado_id'] ?? '') ?>">
            </div>

            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>
    </div>

    <div class="section">
        <div class="section-title">Historico de Chamados</div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Assunto</th>
                        <th>Local</th>
                        <th>Tecnico</th>
                        <th>Prioridade</th>
                        <th>Criado em</th>
                        <th>Finalizado em</th>
                        <th>Solucao</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($chamados)): ?>
                        <?php foreach ($chamados as $chamado): ?>
                            <tr>
                                <td>
                                    <a href="index.php?action=detalhes&id=<?= $chamado['id'] ?>">#<?= str_pad($chamado['id'] ?? 0, 5, '0', STR_PAD_LEFT) ?></a>
                                </td>
                                <td>
                                    <a href="index.php?action=detalhes&id=<?= $chamado['id'] ?>"><?= htmlspecialchars($chamado['assunto'] ?? '') ?></a>
                                </td>
                                <td><?= htmlspecialchars($chamado['local'] ?? '') ?></td>
                                <td><?= htmlspecialchars($chamado['tecnico_nome'] ?? $chamado['tecnico'] ?? '') ?></td>
                                <td><?= htmlspecialchars($chamado['prioridade'] ?? '') ?></td>
                                <td><?php $abertura = strtotime($chamado['data_abertura'] ?? ''); echo $abertura ? date('d/m/Y H:i', $abertura) : 'N/A'; ?></td>
                                <td><?php $finalizacao = strtotime($chamado['data_finalizacao'] ?? ''); echo $finalizacao ? date('d/m/Y H:i', $finalizacao) : 'N/A'; ?></td>
                                <td><?= htmlspecialchars(substr($chamado['solucao'] ?? '', 0, 50)) . (strlen($chamado['solucao'] ?? '') > 50 ? '...' : '') ?></td>
                                <td>
                                    <a href="index.php?action=detalhes&id=<?= $chamado['id'] ?>" class="btn btn-secondary" style="margin-right: 8px;">Ver</a>
                                    <a href="index.php?action=deletar&id=<?= $chamado['id'] ?>" class="btn btn-danger" onclick="return confirm('Deseja realmente excluir este chamado?');">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 20px;">Nenhum chamado encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
