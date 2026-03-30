<?php

class TecnicoService
{
    private $dao;

    public function __construct(TecnicoDAO $dao)
    {
        $this->dao = $dao;
    }

    public function criar(Tecnico $tecnico)
    {
        return $this->dao->create($tecnico);
    }

    public function buscarPorId($id)
    {
        return $this->dao->findById($id);
    }

    public function listar()
    {
        return $this->dao->findAll();
    }

    public function atualizar(Tecnico $tecnico)
    {
        return $this->dao->update($tecnico);
    }

    public function excluir($id)
    {
        return $this->dao->delete($id);
    }
}
