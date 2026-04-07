<?php

class CriarChamadoCommand implements Command
{
    private $service;
    private $chamado;
    private $pdfService;

    public function __construct(ChamadoService $service, Chamado $chamado, ?PdfService $pdfService = null)
    {
        $this->service = $service;
        $this->chamado = $chamado;
        $this->pdfService = $pdfService;
    }

    public function execute()
    {
        $createdId = $this->service->criar($this->chamado);
        $this->chamado->setId($createdId);

        if ($this->pdfService) {
            try {
                $pdfPath = $this->pdfService->gerarPdfChamado($this->chamado);
                $this->service->atualizarPdfPath($createdId, $pdfPath);
            } catch (Throwable $exception) {
                error_log('Falha ao gerar PDF do chamado #' . $createdId . ': ' . $exception->getMessage());
            }
        }

        return $createdId;
    }
}
