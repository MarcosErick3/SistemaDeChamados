<?php

class CommandFactory
{
    public static function createCriarChamadoCommand(ChamadoService $service, Chamado $chamado, ?PdfService $pdfService = null)
    {
        return new CriarChamadoCommand($service, $chamado, $pdfService);
    }

    public static function createIniciarChamadoCommand(ChamadoService $service, $id)
    {
        return new IniciarChamadoCommand($service, $id);
    }

    public static function createFinalizarChamadoCommand(ChamadoService $service, $id, $solucao, $pdfPath = null)
    {
        return new FinalizarChamadoCommand($service, $id, $solucao, $pdfPath);
    }

    public static function createAtualizarStatusCommand(ChamadoService $service, $id, $status)
    {
        return new AtualizarStatusCommand($service, $id, $status);
    }

    public static function createDeletarChamadoCommand(ChamadoService $service, $id)
    {
        return new DeletarChamadoCommand($service, $id);
    }
}
