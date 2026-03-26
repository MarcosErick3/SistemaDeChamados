<?php

class EquipamentoController
{
    private $dao;

    public function __construct(EquipamentoDAO $dao)
    {
        $this->dao = $dao;
    }

    public function buscarPorNumeroSerie()
    {
        header('Content-Type: application/json; charset=utf-8');

        $numeroSerie = $_GET['numero_serie'] ?? '';

        if (empty($numeroSerie)) {
            echo json_encode([
                'success' => false,
                'message' => 'Número de série não informado.'
            ]);
            exit;
        }

        $equipamento = $this->dao->buscarPorNumeroSerie($numeroSerie);

        if (!$equipamento) {
            echo json_encode([
                'success' => false,
                'message' => 'Equipamento não encontrado.'
            ]);
            exit;
        }

        echo json_encode([
            'success' => true,
            'data' => $equipamento
        ]);
        exit;
    }
}