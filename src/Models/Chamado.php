<?php

class Chamado
{
    private $id;
    private $numeroSerie;
    private $numeroPatrimonio;
    private $descricaoEquipamento;
    private $equipamento;
    private $unidade;
    private $local;
    private $cidade;
    private $uf;

    private $tipoChamado;
    private $prioridade;
    private $tecnico;
    private $assunto;
    private $descricao;

    private $nomeUsuario;
    private $emailUsuario;
    private $dddUsuario;
    private $telefoneUsuario;

    private $tecnicoSupervisor;
    private $dataAtendimento;
    private $solucao;

    private $dataAbertura;
    private $dataFinalizacao;

    private $equipamentoId;
    private $tecnicoId;
    private $criadoPor;
    private $status;

    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNumeroSerie()
    {
        return $this->numeroSerie;
    }
    public function setNumeroSerie($valor)
    {
        $this->numeroSerie = $valor;
    }

    public function getNumeroPatrimonio()
    {
        return $this->numeroPatrimonio;
    }
    public function setNumeroPatrimonio($valor)
    {
        $this->numeroPatrimonio = $valor;
    }

    public function getDescricaoEquipamento()
    {
        return $this->descricaoEquipamento;
    }
    public function setDescricaoEquipamento($valor)
    {
        $this->descricaoEquipamento = $valor;
    }

    public function getEquipamento()
    {
        return $this->equipamento;
    }
    public function setEquipamento($valor)
    {
        $this->equipamento = $valor;
    }

    public function getUnidade()
    {
        return $this->unidade;
    }
    public function setUnidade($valor)
    {
        $this->unidade = $valor;
    }

    public function getLocal()
    {
        return $this->local;
    }
    public function setLocal($valor)
    {
        $this->local = $valor;
    }

    public function getCidade()
    {
        return $this->cidade;
    }
    public function setCidade($valor)
    {
        $this->cidade = $valor;
    }

    public function getUf()
    {
        return $this->uf;
    }
    public function setUf($valor)
    {
        $this->uf = $valor;
    }

    public function getCriadoPor()
    {
        return $this->criadoPor;
    }
    public function setCriadoPor($valor)
    {
        $this->criadoPor = $valor;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($valor)
    {
        $this->status = $valor;
    }

    public function getTecnicoId()
    {
        return $this->tecnicoId;
    }

    public function setTecnicoId($valor)
    {
        $this->tecnicoId = $valor;
    }

    public function getTipoChamado()
    {
        return $this->tipoChamado;
    }
    public function setTipoChamado($valor)
    {
        $this->tipoChamado = $valor;
    }

    public function getPrioridade()
    {
        return $this->prioridade;
    }
    public function setPrioridade($valor)
    {
        $this->prioridade = $valor;
    }

    public function getTecnico()
    {
        return $this->tecnico;
    }
    public function setTecnico($valor)
    {
        $this->tecnico = $valor;
    }

    public function getAssunto()
    {
        return $this->assunto;
    }
    public function setAssunto($valor)
    {
        $this->assunto = $valor;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }
    public function setDescricao($valor)
    {
        $this->descricao = $valor;
    }

    public function getNomeUsuario()
    {
        return $this->nomeUsuario;
    }
    public function setNomeUsuario($valor)
    {
        $this->nomeUsuario = $valor;
    }

    public function getEmailUsuario()
    {
        return $this->emailUsuario;
    }
    public function setEmailUsuario($valor)
    {
        $this->emailUsuario = $valor;
    }

    public function getDddUsuario()
    {
        return $this->dddUsuario;
    }
    public function setDddUsuario($valor)
    {
        $this->dddUsuario = $valor;
    }

    public function getTelefoneUsuario()
    {
        return $this->telefoneUsuario;
    }
    public function setTelefoneUsuario($valor)
    {
        $this->telefoneUsuario = $valor;
    }

    public function getTecnicoSupervisor()
    {
        return $this->tecnicoSupervisor;
    }
    public function setTecnicoSupervisor($valor)
    {
        $this->tecnicoSupervisor = $valor;
    }

    public function getDataAtendimento()
    {
        return $this->dataAtendimento;
    }
    public function setDataAtendimento($valor)
    {
        $this->dataAtendimento = $valor;
    }

    public function getSolucao()
    {
        return $this->solucao;
    }
    public function setSolucao($valor)
    {
        $this->solucao = $valor;
    }

    public function getDataAbertura()
    {
        return $this->dataAbertura;
    }
    public function setDataAbertura($valor)
    {
        $this->dataAbertura = $valor;
    }

    public function getDataFinalizacao()
    {
        return $this->dataFinalizacao;
    }
    public function setDataFinalizacao($valor)
    {
        $this->dataFinalizacao = $valor;
    }

    public function getEquipamentoId()
    {
        return $this->equipamentoId;
    }
    public function setEquipamentoId($equipamentoId)
    {
        $this->equipamentoId = $equipamentoId;
    }
}
