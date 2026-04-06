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
        <div class="topbar-content">
            <div class="topbar-logo">
                <span class="logo-icon">📋</span>
                <span class="logo-text">ServiceDesk</span>
            </div>
            <nav class="topbar-nav">
                <a href="index.php?action=index" class="nav-item <?= (empty($_GET['meus_chamados']) && empty($_GET['historico'])) ? 'active' : '' ?>">
                    <span class="nav-icon">🔍</span>
                    <span>Pesquisa</span>
                </a>
                <a href="index.php?action=index&meus_chamados=1" class="nav-item <?= (!empty($_GET['meus_chamados'])) ? 'active' : '' ?>">
                    <span class="nav-icon">📝</span>
                    <span>Meus Chamados</span>
                </a>
                <a href="index.php?action=historico" class="nav-item <?= (isset($_GET['action']) && $_GET['action'] === 'historico') ? 'active' : '' ?>">
                    <span class="nav-icon">📚</span>
                    <span>Histórico</span>
                </a>
                <a href="index.php?action=perfil" class="nav-item <?= (isset($_GET['action']) && $_GET['action'] === 'perfil') ? 'active' : '' ?>">
                    <span class="nav-icon">👤</span>
                    <span>Perfil</span>
                </a>
            </nav>
            <div class="topbar-user">
                <?php if(isset($_SESSION['user'])): ?>
                    <span class="user-badge">
                        <span class="user-icon">👨‍💼</span>
                        <span class="user-name"><?= htmlspecialchars($_SESSION['user']['nome']) ?></span>
                    </span>
                    <a href="index.php?action=logout" class="nav-item logout">
                        <span class="nav-icon">🚪</span>
                        <span>Sair</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="breadcrumb-bar">
        <div class="breadcrumb">
            <a href="index.php?action=index">📊 Início</a>
            <?php if(!empty($_GET['meus_chamados'])): ?>
                <span class="separator">/</span>
                <span>Meus Chamados</span>
            <?php else: ?>
                <span class="separator">/</span>
                <span>Ordem de Serviço</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <h1 class="page-title">
            ServiceDesk - Ordem de Serviço
            <?php 
                if (!empty($_GET['meus_chamados'])) {
                    echo ' - Meus Chamados';
                }
            ?>
        </h1>

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
                        <input type="text" name="criado_por" value="<?= isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']['nome']) : 'Técnico' ?>" readonly>
                    </div>

                    <div class="field">
                        <label>Status</label>
                        <select name="status">
                            <option value="ABERTO">ABERTO</option>
                            <option value="EM ANDAMENTO" selected>EM ANDAMENTO</option>
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