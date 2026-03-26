<?php

class IniciarChamadoCommand implements Command
{
    private $service;
    private $id;

    public function __construct(ChamadoService $service, $id)
    {
        $this->service = $service;
        $this->id = $id;
    }

    public function execute()
    {
        return $this->service->iniciarAtendimento($this->id);
    }
}