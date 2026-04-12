<?php

class ChamadoFinalizacao
{
    private $id;
    private $chamadoId;
    private $solucao;
    private $pdfPath;
    private $dataFinalizacao;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getChamadoId()
    {
        return $this->chamadoId;
    }

    public function setChamadoId($chamadoId)
    {
        $this->chamadoId = $chamadoId;
    }

    public function getSolucao()
    {
        return $this->solucao;
    }

    public function setSolucao($solucao)
    {
        $this->solucao = $solucao;
    }

    public function getPdfPath()
    {
        return $this->pdfPath;
    }

    public function setPdfPath($pdfPath)
    {
        $this->pdfPath = $pdfPath;
    }

    public function getDataFinalizacao()
    {
        return $this->dataFinalizacao;
    }

    public function setDataFinalizacao($dataFinalizacao)
    {
        $this->dataFinalizacao = $dataFinalizacao;
    }
}
