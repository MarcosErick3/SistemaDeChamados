<?php
$topbarActive = !empty($_GET['meus_chamados']) ? 'meus_chamados' : 'listar';
$topbarUser = $_SESSION['user'] ?? null;
$pageTitle = 'ServiceDesk - Ordem de Servico';
ob_start();
?>

<div class="container">
    <h1 class="page-title">
        ServiceDesk - Ordem de Servico
        <?php if (!empty($_GET['meus_chamados'])): ?>
            <?= ' - Meus Chamados' ?>
        <?php endif; ?>
    </h1>

    <form method="POST" action="index.php?action=salvar">
        <div class="section">
            <div class="section-title">Dados do Equipamento</div>

            <input type="hidden" name="equipamento_id" id="equipamento_id">

            <div class="grid-2">
                <div class="field">
                    <label>Numero Serie</label>
                    <input type="text" name="numero_serie" id="numero_serie" placeholder="Ex.: SN-2026-001" required>
                </div>

                <div class="field">
                    <label>Numero Patrimonio</label>
                    <input type="text" name="numero_patrimonio" id="numero_patrimonio" readonly>
                </div>

                <div class="field">
                    <label>Descricao</label>
                    <input type="text" name="descricao_equipamento" id="descricao_equipamento" readonly>
                </div>

                <div class="field">
                    <label>Equipamento</label>
                    <input type="text" name="equipamento" id="equipamento" readonly>
                </div>

                <div class="field">
                    <label>Unidade</label>
                    <input type="text" name="unidade" id="unidade" readonly>
                </div>

                <div class="field">
                    <label>Local</label>
                    <input type="text" name="local_equipamento" id="local_equipamento" readonly>
                </div>

                <div class="field">
                    <label>Cidade</label>
                    <input type="text" name="cidade" id="cidade" readonly>
                </div>

                <div class="field">
                    <label>UF</label>
                    <input type="text" name="uf" id="uf" readonly>
                </div>
            </div>

            <div id="mensagemEquipamento" style="margin-top:10px; font-weight:bold; color:#8b0000;"></div>
        </div>

        <div class="section">
            <div class="section-title">Ordem de Servico</div>
            <div class="grid-3">
                <div class="field">
                    <label>Criado por</label>
                    <input type="text" name="criado_por" value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['nome']) : 'Tecnico' ?>" readonly>
                </div>

                <div class="field">
                    <label>Status</label>
                    <select name="status" onchange="toggleTratamentoFields()">
                        <option value="ABERTO" selected>ABERTO</option>
                        <option value="EM ANDAMENTO">EM ANDAMENTO</option>
                        <option value="FINALIZADO">FINALIZADO</option>
                    </select>
                </div>

                <div class="field">
                    <label>Tecnico Responsavel</label>
                    <select name="tecnico_id" required>
                        <option value="">Selecione o tecnico</option>
                        <?php foreach ($tecnicos as $tecnico): ?>
                            <option value="<?= htmlspecialchars($tecnico['id']) ?>"><?= htmlspecialchars($tecnico['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field">
                    <label>Tipo do Chamado</label>
                    <select name="tipo_chamado">
                        <option value="">Selecione</option>
                        <option value="Hardware">Hardware</option>
                        <option value="Software">Software</option>
                        <option value="Rede">Rede</option>
                        <option value="Sistema">Sistema</option>
                    </select>
                </div>

                <div class="field">
                    <label>Prioridade</label>
                    <select name="prioridade">
                        <option value="">Selecione</option>
                        <option value="Baixa">Baixa</option>
                        <option value="Media">Media</option>
                        <option value="Alta">Alta</option>
                    </select>
                </div>

                <div class="field">
                    <label>Data Atendimento</label>
                    <input type="date" name="data_atendimento">
                </div>

                <div class="field full">
                    <label>Assunto</label>
                    <input type="text" name="assunto" required>
                </div>

                <div class="field full">
                    <label>Descricao do Problema</label>
                    <textarea name="descricao" required></textarea>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Dados do Usuario</div>
            <div class="grid-2">
                <div class="field">
                    <label>Nome</label>
                    <input type="text" name="nome_usuario">
                </div>

                <div class="field">
                    <label>E-mail</label>
                    <input type="email" name="email_usuario">
                </div>

                <div class="field">
                    <label>DDD</label>
                    <input type="text" name="ddd_usuario" inputmode="numeric" maxlength="5" placeholder="Ex.: 11">
                </div>

                <div class="field">
                    <label>Telefone</label>
                    <input type="text" name="telefone_usuario" inputmode="numeric" maxlength="20" placeholder="Ex.: 999999999">
                </div>
            </div>
        </div>

        <div class="section" id="tratamento-section" style="display: none;">
            <div class="section-title">Informacoes de Tratamento</div>
            <div class="grid-2">
                <div class="field full">
                    <label>Solucao</label>
                    <textarea name="solucao"></textarea>
                </div>
            </div>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Salvar Chamado</button>
        </div>
    </form>

    <?php if (!empty($_GET['created']) && !empty($_GET['chamado_numero'])): ?>
        <div class="alert alert-success" style="margin-bottom: 20px;">
            Chamado <strong>#<?= htmlspecialchars($_GET['chamado_numero']) ?></strong> criado com sucesso.
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const numeroSerieInput = document.getElementById('numero_serie');
        const equipamentoIdInput = document.getElementById('equipamento_id');
        const numeroPatrimonioInput = document.getElementById('numero_patrimonio');
        const descricaoEquipamentoInput = document.getElementById('descricao_equipamento');
        const equipamentoInput = document.getElementById('equipamento');
        const unidadeInput = document.getElementById('unidade');
        const localEquipamentoInput = document.getElementById('local_equipamento');
        const cidadeInput = document.getElementById('cidade');
        const ufInput = document.getElementById('uf');
        const mensagemEquipamento = document.getElementById('mensagemEquipamento');
        const formChamado = document.querySelector('form[action="index.php?action=salvar"]');

        function limparCamposEquipamento() {
            equipamentoIdInput.value = '';
            numeroPatrimonioInput.value = '';
            descricaoEquipamentoInput.value = '';
            equipamentoInput.value = '';
            unidadeInput.value = '';
            localEquipamentoInput.value = '';
            cidadeInput.value = '';
            ufInput.value = '';
        }

        async function buscarEquipamento() {
            const numeroSerie = numeroSerieInput.value.trim();

            limparCamposEquipamento();
            mensagemEquipamento.textContent = '';

            if (numeroSerie === '') {
                return;
            }

            try {
                const response = await fetch('index.php?action=buscarEquipamento&numero_serie=' + encodeURIComponent(numeroSerie));
                const data = await response.json();

                if (!data.success) {
                    mensagemEquipamento.textContent = data.message;
                    return;
                }

                const equipamento = data.data;

                equipamentoIdInput.value = equipamento.id;
                numeroPatrimonioInput.value = equipamento.numero_patrimonio;
                descricaoEquipamentoInput.value = equipamento.descricao;
                equipamentoInput.value = equipamento.equipamento;
                unidadeInput.value = equipamento.unidade;
                localEquipamentoInput.value = equipamento.local;
                cidadeInput.value = equipamento.cidade;
                ufInput.value = equipamento.uf;

                mensagemEquipamento.textContent = 'Equipamento encontrado com sucesso.';
            } catch (erro) {
                console.error('Erro na busca:', erro);
                mensagemEquipamento.textContent = 'Erro ao buscar equipamento.';
            }
        }

        numeroSerieInput.addEventListener('blur', buscarEquipamento);

        formChamado.addEventListener('submit', function(event) {
            if (!equipamentoIdInput.value) {
                event.preventDefault();
                alert('Informe um numero de serie valido antes de salvar o chamado.');
                numeroSerieInput.focus();
            }
        });

        function toggleTratamentoFields() {
            const status = document.querySelector('select[name="status"]').value;
            const tratamentoSection = document.getElementById('tratamento-section');
            tratamentoSection.style.display = status === 'FINALIZADO' ? 'block' : 'none';
        }

        toggleTratamentoFields();
    });
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layout.php';
