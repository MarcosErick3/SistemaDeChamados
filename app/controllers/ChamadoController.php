<?php

class ChamadoController
{
    private $service;

    public function __construct(ChamadoService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $status = $_GET['status'] ?? null;
        $local = $_GET['local'] ?? null;
        $descricao = $_GET['descricao'] ?? null;

        if ($status || $local || $descricao) {
            $chamados = $this->service->filtrar($status, $local, $descricao);
        } else {
            $chamados = $this->service->listar();
        }

        require_once __DIR__ . '/../views/chamados/index.php';
    }

    public function store()
    {
        $equipamentoId = $_POST['equipamento_id'] ?? null;

        if (empty($equipamentoId)) {
            die('Erro: selecione um equipamento válido informando um número de série cadastrado.');
        }

        $builder = new ChamadoBuilder();

        $chamado = $builder
            ->equipamentoId($equipamentoId)
            ->criadoPor($_POST['criado_por'] ?? null)
            ->status($_POST['status'] ?? 'ABERTO')
            ->tipoChamado($_POST['tipo_chamado'] ?? null)
            ->prioridade($_POST['prioridade'] ?? null)
            ->tecnico($_POST['tecnico'] ?? null)
            ->assunto($_POST['assunto'] ?? null)
            ->descricao($_POST['descricao'] ?? null)
            ->nomeUsuario($_POST['nome_usuario'] ?? null)
            ->emailUsuario($_POST['email_usuario'] ?? null)
            ->dddUsuario($_POST['ddd_usuario'] ?? null)
            ->telefoneUsuario($_POST['telefone_usuario'] ?? null)
            ->grupoAtendimento($_POST['grupo_atendimento'] ?? null)
            ->tecnicoSupervisor($_POST['tecnico_supervisor'] ?? null)
            ->diagnostico($_POST['diagnostico'] ?? null)
            ->dataAtendimento($_POST['data_atendimento'] ?? null)
            ->solucao($_POST['solucao'] ?? null)
            ->build();

        $command = new CriarChamadoCommand($this->service, $chamado);
        $command->execute();

        header('Location: index.php');
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
