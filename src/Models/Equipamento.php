<?php

class Equipamento
{
    private $id;
    private $numeroSerie;
    private $numeroPatrimonio;
    private $descricao;
    private $equipamento;
    private $unidade;
    private $local;
    private $cidade;
    private $uf;

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getNumeroSerie() { return $this->numeroSerie; }
    public function setNumeroSerie($numeroSerie) { $this->numeroSerie = $numeroSerie; }

    public function getNumeroPatrimonio() { return $this->numeroPatrimonio; }
    public function setNumeroPatrimonio($numeroPatrimonio) { $this->numeroPatrimonio = $numeroPatrimonio; }

    public function getDescricao() { return $this->descricao; }
    public function setDescricao($descricao) { $this->descricao = $descricao; }

    public function getEquipamento() { return $this->equipamento; }
    public function setEquipamento($equipamento) { $this->equipamento = $equipamento; }

    public function getUnidade() { return $this->unidade; }
    public function setUnidade($unidade) { $this->unidade = $unidade; }

    public function getLocal() { return $this->local; }
    public function setLocal($local) { $this->local = $local; }

    public function getCidade() { return $this->cidade; }
    public function setCidade($cidade) { $this->cidade = $cidade; }

    public function getUf() { return $this->uf; }
    public function setUf($uf) { $this->uf = $uf; }
}