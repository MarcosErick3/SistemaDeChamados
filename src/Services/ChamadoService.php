<?php

class ChamadoService
{
    private $dao;

    public function __construct(ChamadoDAO $dao)
    {
        $this->dao = $dao;
    }

    public function criar(Chamado $chamado)
    {
        return $this->dao->criarChamado($chamado);
    }

    public function listar()
    {
        return $this->dao->listarTudo();
    }

    public function buscarPorId($id)
    {
        return $this->dao->findById($id);
    }

    public function atualizarStatus($id, $status)
    {
        return $this->dao->updateStatus($id, $status);
    }

    public function iniciar($id)
    {
        return $this->dao->updateStatus($id, 'EM ANDAMENTO');
    }

    public function iniciarAtendimento($id)
    {
        return $this->iniciar($id);
    }

    public function finalizar($id, $solucao, $pdfPath = null)
    {
        return $this->dao->updateStatus($id, 'FINALIZADO', $solucao, $pdfPath);
    }

    public function deletar($id)
    {
        return $this->dao->delete($id);
    }

    public function atualizarPdfPath($id, $pdfPath)
    {
        return $this->dao->updatePdfPath($id, $pdfPath);
    }

    public function filtrar($status = null, $numeroSerie = null, $chamadoId = null)
    {
        return $this->dao->filtrar($status, $numeroSerie, $chamadoId);
    }

    public function filtrarPorTecnico($tecnicoId)
    {
        return $this->dao->filtrarPorTecnico($tecnicoId);
    }

    public function listarHistorico()
    {
        return $this->dao->listarHistorico();
    }

    public function filtrarHistorico($tecnicoId = null, $numeroSerie = null, $chamadoId = null, $status = null)
    {
        return $this->dao->filtrarHistorico($tecnicoId, $numeroSerie, $chamadoId, $status);
    }
}
