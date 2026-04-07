<?php

class ChamadoBuilder
{
    private $chamado;

    public function __construct()
    {
        $this->chamado = new Chamado();
        $this->chamado->setStatus('ABERTO');
    }

    public function numeroSerie($valor)
    {
        $this->chamado->setNumeroSerie($valor);
        return $this;
    }
    public function numeroPatrimonio($valor)
    {
        $this->chamado->setNumeroPatrimonio($valor);
        return $this;
    }
    public function descricaoEquipamento($valor)
    {
        $this->chamado->setDescricaoEquipamento($valor);
        return $this;
    }
    public function equipamento($valor)
    {
        $this->chamado->setEquipamento($valor);
        return $this;
    }
    public function unidade($valor)
    {
        $this->chamado->setUnidade($valor);
        return $this;
    }
    public function local($valor)
    {
        $this->chamado->setLocal($valor);
        return $this;
    }
    public function cidade($valor)
    {
        $this->chamado->setCidade($valor);
        return $this;
    }
    public function uf($valor)
    {
        $this->chamado->setUf($valor);
        return $this;
    }

    public function criadoPor($valor)
    {
        $this->chamado->setCriadoPor($valor);
        return $this;
    }
    public function status($valor)
    {
        if (!empty($valor)) $this->chamado->setStatus($valor);
        return $this;
    }
    public function tipoChamado($valor)
    {
        $this->chamado->setTipoChamado($valor);
        return $this;
    }
    public function prioridade($valor)
    {
        $this->chamado->setPrioridade($valor);
        return $this;
    }
    public function tecnico($valor)
    {
        $this->chamado->setTecnico($valor);
        return $this;
    }

    public function tecnicoId($valor)
    {
        $this->chamado->setTecnicoId($valor);
        return $this;
    }

    public function assunto($valor)
    {
        $this->chamado->setAssunto($valor);
        return $this;
    }
    public function descricao($valor)
    {
        $this->chamado->setDescricao($valor);
        return $this;
    }

    public function nomeUsuario($valor)
    {
        $this->chamado->setNomeUsuario($valor);
        return $this;
    }
    public function emailUsuario($valor)
    {
        $this->chamado->setEmailUsuario($valor);
        return $this;
    }
    public function dddUsuario($valor)
    {
        $this->chamado->setDddUsuario($valor);
        return $this;
    }
    public function telefoneUsuario($valor)
    {
        $this->chamado->setTelefoneUsuario($valor);
        return $this;
    }

    public function tecnicoSupervisor($valor)
    {
        $this->chamado->setTecnicoSupervisor($valor);
        return $this;
    }
    public function dataAtendimento($valor)
    {
        $this->chamado->setDataAtendimento($valor);
        return $this;
    }
    public function solucao($valor)
    {
        $this->chamado->setSolucao($valor);
        return $this;
    }

    public function equipamentoId($valor)
{
    $this->chamado->setEquipamentoId($valor);
    return $this;
}

    public function pdfPath($valor)
    {
        $this->chamado->setPdfPath($valor);
        return $this;
    }

    public function build()
    {
        return $this->chamado;
    }
}
