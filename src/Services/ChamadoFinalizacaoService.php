<?php

class ChamadoFinalizacaoService
{
    private $dao;

    public function __construct(ChamadoFinalizacaoDAO $dao)
    {
        $this->dao = $dao;
    }

    public function criar(ChamadoFinalizacao $finalizacao)
    {
        return $this->dao->create($finalizacao);
    }

    public function buscarPorChamadoId($chamadoId)
    {
        return $this->dao->findByChamadoId($chamadoId);
    }

    public function atualizar(ChamadoFinalizacao $finalizacao)
    {
        return $this->dao->update($finalizacao);
    }

    public function salvar(ChamadoFinalizacao $finalizacao)
    {
        return $this->dao->save($finalizacao);
    }
}
