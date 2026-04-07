<?php

class FinalizarChamadoCommand implements Command
{
    private $service;
    private $id;
    private $solucao;
    private $pdfPath;

    public function __construct(ChamadoService $service, $id, $solucao, $pdfPath = null)
    {
        $this->service = $service;
        $this->id = $id;
        $this->solucao = $solucao;
        $this->pdfPath = $pdfPath;
    }

    public function execute()
    {
        return $this->service->finalizar($this->id, $this->solucao, $this->pdfPath);
    }
}