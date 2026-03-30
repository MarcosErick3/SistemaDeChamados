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

        if (!empty($chamadoId)) {
            $chamadoId = (int) $chamadoId;
            if ($chamadoId <= 0) {
                $chamadoId = null;
            }
        }

        if ($status || $chamadoId || $numeroSerie) {
            $chamados = $this->service->filtrar($status, $numeroSerie, $chamadoId);
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
}
