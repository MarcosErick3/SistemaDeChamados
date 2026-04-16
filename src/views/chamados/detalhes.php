<?php
$topbarActive = 'historico';
$topbarUser = $_SESSION['user'] ?? null;
$pageTitle = 'ServiceDesk - Chamado #' . str_pad($chamado['id'] ?? 0, 5, '0', STR_PAD_LEFT);
$pageStyles = ['css/chamados.css?v=1'];
$dataAbertura = $chamado['data_abertura'] ?? $chamado['data_criacao'] ?? null;
ob_start();
?>

<div class="container">
    <h1 class="page-title">Chamado #<?= str_pad($chamado['id'] ?? 0, 5, '0', STR_PAD_LEFT) ?></h1>

    <div class="section">
        <div class="section-title">Detalhes do Chamado</div>
        <form method="POST" action="index.php?action=atualizar" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $chamado['id'] ?>">
            <div class="grid-2">
                <div class="field">
                    <label>ID</label>
                    <input type="text" readonly value="#<?= str_pad($chamado['id'] ?? 0, 5, '0', STR_PAD_LEFT) ?>">
                </div>
                <div class="field">
                    <label>Status</label>
                    <select name="status" onchange="alternarSolucao()">
                        <option value="ABERTO" <?= ($chamado['status'] === 'ABERTO') ? 'selected' : '' ?>>ABERTO</option>
                        <option value="EM ANDAMENTO" <?= ($chamado['status'] === 'EM ANDAMENTO') ? 'selected' : '' ?>>EM ANDAMENTO</option>
                        <option value="FINALIZADO" <?= ($chamado['status'] === 'FINALIZADO') ? 'selected' : '' ?>>FINALIZADO</option>
                    </select>
                </div>
                <div class="field">
                    <label>Tecnico</label>
                    <input type="text" readonly value="<?= htmlspecialchars($chamado['tecnico_nome'] ?? $chamado['tecnico'] ?? '') ?>">
                </div>
                <div class="field">
                    <label>Prioridade</label>
                    <input type="text" readonly value="<?= htmlspecialchars($chamado['prioridade'] ?? '') ?>">
                </div>
                <div class="field">
                    <label>Numero Serie</label>
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
                    <input type="text" readonly value="<?= $dataAbertura ? date('d/m/Y H:i', strtotime($dataAbertura)) : 'N/A' ?>">
                </div>
                <div class="field">
                    <label>Finalizado em</label>
                    <input type="text" readonly value="<?= isset($chamado['data_finalizacao']) ? date('d/m/Y H:i', strtotime($chamado['data_finalizacao'])) : 'N/A' ?>">
                </div>
            </div>

            <div class="section">
                <div class="section-title">Informacoes do Chamado</div>
                <div class="field full">
                    <label>Assunto</label>
                    <input type="text" readonly value="<?= htmlspecialchars($chamado['assunto'] ?? '') ?>">
                </div>
                <div class="field full">
                    <label>Descricao</label>
                    <textarea readonly><?= htmlspecialchars($chamado['descricao'] ?? '') ?></textarea>
                </div>
                <div class="field full is-hidden" id="solucao-field">
                    <label>Solucao</label>
                    <textarea name="solucao"><?= htmlspecialchars($chamado['solucao'] ?? '') ?></textarea>
                </div>
                <div class="field full is-hidden" id="pdf-field">
                    <label>Anexar PDF preenchido</label>
                    <input type="file" name="pdf" accept=".pdf">
                </div>
                <div class="field full" id="solucao-view">
                    <label>Solucao</label>
                    <textarea readonly><?= htmlspecialchars($chamado['solucao'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Dados do Usuario</div>
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
                <?php if (!empty($chamado['pdf_path'])): ?>
                    <a href="<?= htmlspecialchars($chamado['pdf_path']) ?>" class="btn btn-info" target="_blank">Baixar PDF</a>
                <?php endif; ?>
            </div>
        </form>

        
    </div>

    <!-- Seção de Comentários -->
    <div class="section">
        <div class="section-title">Comentários</div>

        <!-- Formulário para adicionar comentário -->
        <form method="POST" action="index.php?action=adicionarComentario" class="details-comment-form">
            <input type="hidden" name="chamado_id" value="<?= $chamado['id'] ?>">
            <div class="field">
                <label for="comentario">Novo Comentário</label>
                <textarea name="comentario" id="comentario" rows="3" placeholder="Digite seu comentário..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar Comentário</button>
        </form>

        <!-- Lista de comentários -->
        <div class="comentarios-list">
            <?php if (!empty($comentarios)): ?>
                <?php foreach ($comentarios as $comentario): ?>
                    <div class="comentario-item details-comment-item">
                        <div class="details-comment-header">
                            <strong><?= htmlspecialchars($comentario->getUsuarioNome()) ?></strong>
                            <small class="details-comment-meta">
                                <?= date('d/m/Y H:i', strtotime($comentario->getDataCriacao())) ?>
                                <?php if ((int) $comentario->getUsuarioId() === (int) ($_SESSION['user']['id'] ?? 0)): ?>
                                    <a href="index.php?action=deletarComentario&id=<?= $comentario->getId() ?>&chamado_id=<?= $chamado['id'] ?>"
                                       onclick="return confirm('Deseja excluir este comentario?')"
                                       class="details-comment-delete">Excluir</a>
                                <?php endif; ?>
                            </small>
                        </div>
                        <div class="details-comment-body"><?= htmlspecialchars($comentario->getComentario()) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="details-comment-empty">Nenhum comentario ainda.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function alternarSolucao() {
        const statusSelect = document.querySelector('select[name="status"]');
        const solucaoField = document.getElementById('solucao-field');
        const solucaoView = document.getElementById('solucao-view');
        const pdfField = document.getElementById('pdf-field');

        if (statusSelect.value === 'FINALIZADO') {
            solucaoField.classList.remove('is-hidden');
            pdfField.classList.remove('is-hidden');
            solucaoView.classList.add('is-hidden');
        } else {
            solucaoField.classList.add('is-hidden');
            pdfField.classList.add('is-hidden');
            solucaoView.classList.remove('is-hidden');
        }
    }

    alternarSolucao();
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';

