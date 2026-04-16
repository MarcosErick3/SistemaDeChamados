<?php

require_once __DIR__ . '/../DAO/ChamadoComentarioDAO.php';
require_once __DIR__ . '/../Models/ChamadoComentario.php';

class ChamadoComentarioService
{
    private $dao;

    public function __construct($conn)
    {
        $this->dao = new ChamadoComentarioDAO($conn);
    }

    public function adicionarComentario($chamadoId, $usuarioId, $usuarioNome, $comentario)
    {
        $comentarioObj = new ChamadoComentario($chamadoId, $usuarioId, $usuarioNome, $comentario);
        return $this->dao->criar($comentarioObj);
    }

    public function listarComentarios($chamadoId)
    {
        return $this->dao->listarPorChamado($chamadoId);
    }

    public function removerComentario($id)
    {
        return $this->dao->deletar($id);
    }
}
