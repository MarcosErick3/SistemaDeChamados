<?php

class CriarChamadoCommand implements Command
{
    private $service;
    private $chamado;

    public function __construct(ChamadoService $service, Chamado $chamado)
    {
        $this->service = $service;
        $this->chamado = $chamado;
    }

    public function execute()
    {
        return $this->service->criar($this->chamado);
    }
}