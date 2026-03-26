<?php

require_once '../config/Database.php';

require_once '../app/models/Chamado.php';
require_once '../app/models/Equipamento.php';

require_once '../app/builders/ChamadoBuilder.php';

require_once '../app/dao/ChamadoDAO.php';
require_once '../app/dao/EquipamentoDAO.php';

require_once '../app/services/ChamadoService.php';

require_once '../app/commands/Command.php';
require_once '../app/commands/CriarChamadoCommand.php';
require_once '../app/commands/IniciarChamadoCommand.php';
require_once '../app/commands/FinalizarChamadoCommand.php';

require_once '../app/controllers/ChamadoController.php';
require_once '../app/controllers/EquipamentoController.php';

$database = new Database();
$conn = $database->connect();

$chamadoDao = new ChamadoDAO($conn);
$equipamentoDao = new EquipamentoDAO($conn);

$service = new ChamadoService($chamadoDao);

$chamadoController = new ChamadoController($service);
$equipamentoController = new EquipamentoController($equipamentoDao);

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'store':
        $chamadoController->store();
        break;

    case 'iniciar':
        $chamadoController->iniciar();
        break;

    case 'finalizar':
        $chamadoController->finalizar();
        break;

    case 'delete':
        $chamadoController->delete();
        break;

    case 'buscarEquipamento':
        $equipamentoController->buscarPorNumeroSerie();
        break;

    default:
        $chamadoController->index();
        break;
}
