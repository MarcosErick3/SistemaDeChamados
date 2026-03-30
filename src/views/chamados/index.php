<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <title>ServiceDesk - Ordem de Serviço</title>
</head>

<body>

    <div class="topbar">
        <nav>
            <a href="index.php">Pesquisa</a>
            <a href="index.php">Meus Chamados</a>
            <a href="index.php">Ordem de Serviço</a>
            <a href="index.php">Histórico</a>
            <a href="index.php">Pendências</a>
            <a href="index.php">Início</a>
        </nav>
    </div>

    <div class="container">
        <h1 class="page-title">ServiceDesk - Ordem de Serviço</h1>

        <form method="POST" action="index.php?action=store">
            <div class="section">
                <div class="section-title">Dados do Equipamento</div>

                <input type="hidden" name="equipamento_id" id="equipamento_id">

                <div class="grid-2">
                    <div class="field">
                        <label>Nº Série</label>
                        <input type="text" name="numero_serie" id="numero_serie" placeholder="Ex.: SN-2026-001" required>
                    </div>

                    <div class="field">
                        <label>Nº Patrimônio</label>
                        <input type="text" name="numero_patrimonio" id="numero_patrimonio" readonly>
                    </div>

                    <div class="field">
                        <label>Descrição</label>
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
                <div class="section-title">Ordem de Serviço</div>
                <div class="grid-3">
                    <div class="field">
                        <label>Criado por</label>
                        <input type="text" name="criado_por" value="Marcos Medeiros">
                    </div>

                    <div class="field">
                        <label>Status</label>
                        <select name="status">
                            <option value="ABERTO">ABERTO</option>
                            <option value="EM ANDAMENTO">EM ANDAMENTO</option>
                            <option value="FINALIZADO">FINALIZADO</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>Técnico Responsável</label>
                        <select name="tecnico_id" required>
                            <option value="">Selecione o técnico</option>
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
                            <option value="Média">Média</option>
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
                        <label>Descrição do Problema</label>
                        <textarea name="descricao" required></textarea>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Dados do Usuário</div>
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
                        <input type="text" name="ddd_usuario">
                    </div>

                    <div class="field">
                        <label>Telefone</label>
                        <input type="text" name="telefone_usuario">
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Informações de Tratamento</div>
                <div class="grid-2">
                    <div class="field full">
                        <label>Solução</label>
                        <textarea name="solucao"></textarea>
                    </div>
                       
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary">Salvar Chamado</button>
                </div>
            </div>
        </form>

        <?php if (!empty($_GET['created']) && !empty($_GET['chamado_numero'])): ?>
            <div class="alert alert-success" style="margin-bottom: 20px;">
                Chamado <strong>#<?= htmlspecialchars($_GET['chamado_numero']) ?></strong> criado com sucesso.
            </div>
        <?php endif; ?>

        <div class="section">
            <div class="section-title">Filtro de Chamados</div>
            <form method="GET" action="index.php" class="filter-form">
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
                    <label>ID/Número do Chamado</label>
                    <input type="text" name="chamado_id" placeholder="Ex.: 1 ou 00001" value="<?= htmlspecialchars($_GET['chamado_id'] ?? '') ?>">
                </div>

                <div class="field">
                    <label>Nº Série</label>
                    <input type="text" name="numero_serie" placeholder="Buscar por número de série" value="<?= htmlspecialchars($_GET['numero_serie'] ?? '') ?>">
                </div>

                <button type="submit" class="btn btn-primary">Filtrar</button>
            </form>
        </div>

        <div class="section">
            <div class="section-title">Lista de Chamados</div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Assunto</th>
                            <th>Local</th>
                            <th>Status</th>
                            <th>Técnico</th>
                            <th>Prioridade</th>
                            <th>Solução</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($chamados)): ?>
                            <?php foreach ($chamados as $chamado): ?>
                                <tr>
                                    <td>#<?= str_pad($chamado['id'] ?? 0, 5, '0', STR_PAD_LEFT) ?></td>
                                    <td><?= htmlspecialchars($chamado['assunto'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($chamado['local'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($chamado['status'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($chamado['tecnico_nome'] ?? $chamado['tecnico'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($chamado['prioridade'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($chamado['solucao'] ?? '') ?></td>
                                    <td>
                                        <?php if ($chamado['status'] === 'ABERTO'): ?>
                                            <form method="POST" action="index.php?action=iniciar" class="inline-form">
                                                <input type="hidden" name="id" value="<?= $chamado['id'] ?>">
                                                <button type="submit" class="btn btn-secondary">Iniciar</button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if ($chamado['status'] === 'EM ANDAMENTO'): ?>
                                            <form method="POST" action="index.php?action=finalizar" class="inline-form">
                                                <input type="hidden" name="id" value="<?= $chamado['id'] ?>">
                                                <input type="text" name="solucao" placeholder="Digite a solução" required>
                                                <button type="submit" class="btn btn-success">Finalizar</button>
                                            </form>
                                        <?php endif; ?>

                                        <form method="POST" action="index.php?action=delete" class="inline-form" onsubmit="return confirm('Deseja excluir este chamado?')">
                                            <input type="hidden" name="id" value="<?= $chamado['id'] ?>">
                                            <button type="submit" class="btn btn-danger">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">Nenhum chamado encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
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
            const formChamado = document.querySelector('form[action="index.php?action=store"]');

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

                    console.log('Retorno da busca:', data);

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
                console.log('equipamento_id antes de enviar:', equipamentoIdInput.value);

                if (!equipamentoIdInput.value) {
                    event.preventDefault();
                    alert('Informe um número de série válido antes de salvar o chamado.');
                    numeroSerieInput.focus();
                }
            });
        });
    </script>
</body>

</html>