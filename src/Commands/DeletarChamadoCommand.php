<?php

class DeletarChamadoCommand implements Command
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
        return $this->service->deletar($this->id);
    }
}
