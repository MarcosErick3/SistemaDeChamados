<?php

class ChamadoComentario
{
    private $id;
    private $chamadoId;
    private $usuarioId;
    private $usuarioNome;
    private $comentario;
    private $dataCriacao;

    public function __construct($chamadoId = null, $usuarioId = null, $usuarioNome = null, $comentario = null)
    {
        $this->chamadoId = $chamadoId;
        $this->usuarioId = $usuarioId;
        $this->usuarioNome = $usuarioNome;
        $this->comentario = $comentario;
        $this->dataCriacao = date('Y-m-d H:i:s');
    }

    // Getters
    public function getId() { return $this->id; }
    public function getChamadoId() { return $this->chamadoId; }
    public function getUsuarioId() { return $this->usuarioId; }
    public function getUsuarioNome() { return $this->usuarioNome; }
    public function getComentario() { return $this->comentario; }
    public function getDataCriacao() { return $this->dataCriacao; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setChamadoId($chamadoId) { $this->chamadoId = $chamadoId; }
    public function setUsuarioId($usuarioId) { $this->usuarioId = $usuarioId; }
    public function setUsuarioNome($usuarioNome) { $this->usuarioNome = $usuarioNome; }
    public function setComentario($comentario) { $this->comentario = $comentario; }
    public function setDataCriacao($dataCriacao) { $this->dataCriacao = $dataCriacao; }
}