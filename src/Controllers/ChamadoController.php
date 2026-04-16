<?php

class ChamadoController
{
    private $service;
    private $tecnicoService;
    private $pdfService;
    private $finalizacaoService;
    private $comentarioService;

    public function __construct(ChamadoService $service, TecnicoService $tecnicoService, ChamadoFinalizacaoService $finalizacaoService, ?PdfService $pdfService = null, ?ChamadoComentarioService $comentarioService = null)
    {
        $this->service = $service;
        $this->tecnicoService = $tecnicoService;
        $this->pdfService = $pdfService;
        $this->finalizacaoService = $finalizacaoService;
        $this->comentarioService = $comentarioService;
    }

    public function listar()
    {
        if (isset($_GET['meus_chamados'])) {
            $query = $_GET;
            unset($query['meus_chamados']);
            $query['action'] = 'chamados';
            header('Location: index.php?' . http_build_query($query));
            exit;
        }

        $status = $_GET['status'] ?? null;
        $chamadoId = $this->normalizarInteiroPositivo($_GET['chamado_id'] ?? null);
        $numeroSerie = $this->normalizarTextoOpcional($_GET['numero_serie'] ?? null);

        if ($status || $chamadoId || $numeroSerie) {
            $chamados = $this->service->filtrar($status, $numeroSerie, $chamadoId);
        } else {
            $chamados = $this->service->listar();
        }

        $tecnicos = $this->tecnicoService->listar();

        require_once __DIR__ . '/../views/chamados/listar.php';
    }

    public function index()
    {
        $this->listar();
    }

    public function detalhes()
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

        $comentarios = [];
        if ($this->comentarioService) {
            $comentarios = $this->comentarioService->listarComentarios((int) $id);
        }

        require_once __DIR__ . '/../views/chamados/detalhes.php';
    }

    public function dashboard()
    {
        $chamados = $this->service->listarTudo();
        require_once __DIR__ . '/../views/dashboard_page.php';
    }

    public function show()
    {
        $this->detalhes();
    }

    public function salvar()
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

        $chamado = $this->montarChamadoDaRequisicao($equipamentoId, $tecnicoId, $tecnico['nome']);

        $command = CommandFactory::createCriarChamadoCommand($this->service, $chamado, $this->pdfService);
        $createdId = $command->execute();

        if ($chamado->getStatus() === 'FINALIZADO') {
            $finalizacao = new ChamadoFinalizacao();
            $finalizacao->setChamadoId($createdId);
            $finalizacao->setSolucao($_POST['solucao'] ?? null);
            $finalizacao->setPdfPath(null);
            $this->finalizacaoService->salvar($finalizacao);
        }

        $numeroChamado = str_pad($createdId, 5, '0', STR_PAD_LEFT);
        header('Location: index.php?action=chamados&created=1&chamado_numero=' . urlencode($numeroChamado));
        exit;
    }

    public function store()
    {
        $this->salvar();
    }

    public function atualizar()
    {
        $id = $_POST['id'] ?? null;
        $status = $_POST['status'] ?? null;
        $solucao = $_POST['solucao'] ?? '';

        if (empty($id) || !is_numeric($id)) {
            header('Location: index.php?action=historico');
            exit;
        }

        $id = (int) $id;

        if ($status === 'FINALIZADO') {
            $chamado = $this->service->buscarPorId($id);
            $pdfPath = $chamado['pdf_path'] ?? null;
            $uploadedPdfPath = $this->enviarPdf($id);

            if ($uploadedPdfPath !== null) {
                $pdfPath = $uploadedPdfPath;
            }

            $command = CommandFactory::createFinalizarChamadoCommand($this->service, $id, $solucao, $pdfPath);
            $command->execute();

            $finalizacao = new ChamadoFinalizacao();
            $finalizacao->setChamadoId($id);
            $finalizacao->setSolucao($solucao);
            $finalizacao->setPdfPath($pdfPath);
            $this->finalizacaoService->salvar($finalizacao);
        } elseif ($status) {
            $command = CommandFactory::createAtualizarStatusCommand($this->service, $id, $status);
            $command->execute();
        }

        header('Location: index.php?action=detalhes&id=' . $id);
        exit;
    }

    public function update()
    {
        $this->atualizar();
    }

    public function adicionarComentario()
    {
        $chamadoId = $this->normalizarInteiroPositivo($_POST['chamado_id'] ?? null);
        $comentario = trim($_POST['comentario'] ?? '');

        if ($chamadoId === null || $comentario === '') {
            $redirectUrl = $chamadoId !== null
                ? 'index.php?action=detalhes&id=' . $chamadoId
                : 'index.php?action=historico';
            header('Location: ' . $redirectUrl);
            exit;
        }

        $usuarioId = $this->usuarioAtualId();
        $usuarioNome = $this->usuarioAtualNome();

        if ($this->comentarioService && $usuarioNome !== null) {
            $this->comentarioService->adicionarComentario($chamadoId, $usuarioId, $usuarioNome, $comentario);
        }

        header('Location: index.php?action=detalhes&id=' . $chamadoId);
        exit;
    }

    public function deletarComentario()
    {
        $id = $this->normalizarInteiroPositivo($_GET['id'] ?? null);
        $chamadoId = $this->normalizarInteiroPositivo($_GET['chamado_id'] ?? null);

        if ($id === null) {
            header('Location: index.php');
            exit;
        }

        if ($this->comentarioService) {
            $this->comentarioService->removerComentario($id);
        }

        if ($chamadoId === null) {
            header('Location: index.php?action=historico');
            exit;
        }

        header('Location: index.php?action=detalhes&id=' . $chamadoId);
        exit;
    }

    public function deletar()
    {
        $id = $_GET['id'] ?? null;

        if (empty($id) || !is_numeric($id)) {
            header('Location: index.php?action=historico');
            exit;
        }

        $id = (int) $id;
        $command = CommandFactory::createDeletarChamadoCommand($this->service, $id);
        $command->execute();

        header('Location: index.php?action=historico&deleted=1');
        exit;
    }

    public function historico()
    {
        $status = $this->normalizarTextoOpcional($_GET['status'] ?? '');
        $chamadoId = $this->normalizarInteiroPositivo($_GET['chamado_id'] ?? '');
        $numeroSerie = $this->normalizarTextoOpcional($_GET['numero_serie'] ?? '');
        $tecnicoFilter = $this->normalizarTextoOpcional($_GET['tecnico_filter'] ?? '');

        if ($status || $chamadoId || $numeroSerie || $tecnicoFilter) {
            $chamados = $this->service->filtrarHistorico($tecnicoFilter, $numeroSerie, $chamadoId, $status);
        } else {
            $chamados = $this->service->listarHistorico();
        }

        $tecnicos = $this->tecnicoService->listar();

        require_once __DIR__ . '/../views/chamados/historico.php';
    }

    private function enviarPdf($id)
    {
        if (empty($id) || empty($_FILES['pdf']['name'])) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = 'chamado_' . $id . '_finalizado_' . time() . '.pdf';
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['pdf']['tmp_name'], $filePath)) {
            return 'uploads/' . $fileName;
        }

        return null;
    }

    private function montarChamadoDaRequisicao($equipamentoId, $tecnicoId, $tecnicoNome)
    {
        return (new ChamadoBuilder())
            ->equipamentoId($equipamentoId)
            ->numeroSerie($_POST['numero_serie'] ?? null)
            ->numeroPatrimonio($_POST['numero_patrimonio'] ?? null)
            ->descricaoEquipamento($_POST['descricao_equipamento'] ?? null)
            ->equipamento($_POST['equipamento'] ?? null)
            ->unidade($_POST['unidade'] ?? null)
            ->local($_POST['local_equipamento'] ?? null)
            ->cidade($_POST['cidade'] ?? null)
            ->uf($_POST['uf'] ?? null)
            ->tecnicoId($tecnicoId)
            ->tecnico($tecnicoNome)
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
            ->build();
    }

    private function usuarioAtualId()
    {
        $usuarioId = $_SESSION['user']['id'] ?? null;
        return is_numeric($usuarioId) ? (int) $usuarioId : null;
    }

    private function usuarioAtualNome()
    {
        $usuarioNome = trim((string) ($_SESSION['user']['nome'] ?? ''));
        return $usuarioNome !== '' ? $usuarioNome : null;
    }

    private function normalizarInteiroPositivo($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        $normalizedValue = (int) $value;
        return $normalizedValue > 0 ? $normalizedValue : null;
    }

    private function normalizarTextoOpcional($value)
    {
        $normalizedValue = trim((string) $value);
        return $normalizedValue !== '' ? $normalizedValue : null;
    }
}
