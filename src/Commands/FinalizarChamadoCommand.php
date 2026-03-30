<?php

class FinalizarChamadoCommand implements Command
{
    private $service;
    private $id;
    private $solucao;

    public function __construct(ChamadoService $service, $id, $solucao)
    {
        $this->service = $service;
        $this->id = $id;
        $this->solucao = $solucao;
    }

    public function execute()
    {
        return $this->service->finalizar($this->id, $this->solucao);
    }
}