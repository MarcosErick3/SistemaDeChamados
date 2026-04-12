<?php

class AtualizarStatusCommand implements Command
{
    private $service;
    private $id;
    private $status;

    public function __construct(ChamadoService $service, $id, $status)
    {
        $this->service = $service;
        $this->id = $id;
        $this->status = $status;
    }

    public function execute()
    {
        return $this->service->atualizarStatus($this->id, $this->status);
    }
}
