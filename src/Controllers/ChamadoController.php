<?php

class ChamadoController
{
    private $service;
    private $tecnicoService;

    public function __construct(ChamadoService $service, TecnicoService $tecnicoService)
    {
        $this->service = $service;
        $this->tecnicoService = $tecnicoService;
    }

    public function index()
    {
        $status = $_GET['status'] ?? null;
        $chamadoId = $_GET['chamado_id'] ?? null;
        $numeroSerie = $_GET['numero_serie'] ?? null;
        $meusChamados = $_GET['meus_chamados'] ?? null;

        if (!empty($chamadoId)) {
            $chamadoId = (int) $chamadoId;
            if ($chamadoId <= 0) {
                $chamadoId = null;
            }
        }

        $tecnicoId = null;
        if (isset($_SESSION['user']) && isset($_SESSION['user']['id'])) {
            $tecnicoId = $_SESSION['user']['id'];
        }

        if ($status || $chamadoId || $numeroSerie) {
            $chamados = $this->service->filtrar($status, $numeroSerie, $chamadoId);
        } elseif (!empty($meusChamados) && $tecnicoId) {
            $chamados = $this->service->filtrarPorTecnico($tecnicoId);
        } else {
            $chamados = $this->service->listar();
        }

        $tecnicos = $this->tecnicoService->listar();

        require_once __DIR__ . '/../views/chamados/index.php';
    }

    public function store()
    {
        $equipamentoId = $_POST['equipamento_id'] ?? null;
        $tecnicoId = $_POST['tecnico_id'] ?? null;

        if (empty($equipamentoId)) {
            die('Erro: selecione um equipamento válido informando um número de série cadastrado.');
        }

        if (empty($tecnicoId)) {
            die('Erro: selecione um técnico para o chamado.');
        }

        $tecnico = $this->tecnicoService->buscarPorId($tecnicoId);

        if (!$tecnico) {
            die('Erro: técnico selecionado não encontrado.');
        }

        $builder = new ChamadoBuilder();

        $chamado = $builder
            ->equipamentoId($equipamentoId)
            ->tecnicoId($tecnicoId)
            ->tecnico($tecnico['nome'])
            ->criadoPor($_POST['criado_por'] ?? null)
            ->status($_POST['status'] ?? 'ABERTO')
            ->tipoChamado($_POST['tipo_chamado'] ?? null)
            ->prioridade($_POST['prioridade'] ?? null)
            ->assunto($_POST['assunto'] ?? null)
            ->descricao($_POST['descricao'] ?? null)
            ->nomeUsuario($_POST['nome_usuario'] ?? null)
            ->emailUsuario($_POST['email_usuario'] ?? null)
            ->dddUsuario($_POST['ddd_usuario'] ?? null)
            ->telefoneUsuario($_POST['telefone_usuario'] ?? null)
            ->tecnicoSupervisor($_POST['tecnico_supervisor'] ?? null)
            ->dataAtendimento($_POST['data_atendimento'] ?? null)
            ->solucao($_POST['solucao'] ?? null)
            ->build();

        $command = new CriarChamadoCommand($this->service, $chamado);
        $createdId = $command->execute();

        $numeroChamado = str_pad($createdId, 5, '0', STR_PAD_LEFT);
        header('Location: index.php?created=1&chamado_numero=' . urlencode($numeroChamado));
        exit;
    }

    public function iniciar()
    {
        $id = $_POST['id'] ?? null;

        if ($id) {
            $command = new IniciarChamadoCommand($this->service, $id);
            $command->execute();
        }

        header('Location: index.php');
        exit;
    }

    public function finalizar()
    {
        $id = $_POST['id'] ?? null;
        $solucao = $_POST['solucao'] ?? '';

        if ($id) {
            $command = new FinalizarChamadoCommand($this->service, $id, $solucao);
            $command->execute();
        }

        header('Location: index.php');
        exit;
    }

    public function delete()
    {
        $id = $_POST['id'] ?? null;

        if ($id) {
            $this->service->excluir($id);
        }

        header('Location: index.php');
        exit;
    }

    public function historico()
    {
        $status = trim($_GET['status'] ?? '');
        $chamadoId = trim($_GET['chamado_id'] ?? '');
        $numeroSerie = trim($_GET['numero_serie'] ?? '');
        $tecnicoFilter = trim($_GET['tecnico_filter'] ?? '');

        if ($chamadoId !== '') {
            $chamadoId = (int) $chamadoId;
            if ($chamadoId <= 0) {
                $chamadoId = null;
            }
        } else {
            $chamadoId = null;
        }

        if ($numeroSerie === '') {
            $numeroSerie = null;
        }

        if ($tecnicoFilter === '') {
            $tecnicoFilter = null;
        }

        if ($status === '') {
            $status = null;
        }

        if ($status || $chamadoId || $numeroSerie || $tecnicoFilter) {
            $chamados = $this->service->filtrarHistorico($tecnicoFilter, $numeroSerie, $chamadoId, $status);
        } else {
            $chamados = $this->service->listarHistorico();
        }

        $tecnicos = $this->tecnicoService->listar();

        require_once __DIR__ . '/../views/chamados/historico.php';
    }

    public function show()
    {
        $id = $_GET['id'] ?? null;
        if (empty($id) || !is_numeric($id)) {
            header('Location: index.php?action=historico');
            exit;
        }

        $chamado = $this->service->buscarPorId((int) $id);
        if (!$chamado) {
            header('Location: index.php?action=historico');
            exit;
        }

        require_once __DIR__ . '/../views/chamados/show.php';
    }

    public function update()
    {
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;
        $solucao = $_POST['solucao'] ?? '';

        if (empty($id) || !is_numeric($id)) {
            header('Location: index.php?action=historico');
            exit;
        }

        if ($status === 'FINALIZADO') {
            $this->service->finalizar((int) $id, $solucao);
        } else {
            $this->service->atualizarStatus((int) $id, $status);
        }

        header('Location: index.php?action=show&id=' . (int) $id);
        exit;
    }
}
