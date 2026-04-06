<?php

require_once '../config/Database.php';

require_once '../src/Models/Chamado.php';
require_once '../src/Models/Equipamento.php';

require_once '../src/Builders/ChamadoBuilder.php';

require_once '../src/DAO/ChamadoDAO.php';
require_once '../src/DAO/EquipamentoDAO.php';

require_once '../src/Services/ChamadoService.php';

require_once '../src/Commands/Command.php';
require_once '../src/Commands/CriarChamadoCommand.php';
require_once '../src/Commands/IniciarChamadoCommand.php';
require_once '../src/Commands/FinalizarChamadoCommand.php';

require_once '../src/Controllers/ChamadoController.php';
require_once '../src/Controllers/EquipamentoController.php';
require_once '../src/Controllers/LoginController.php';
require_once '../src/Models/Tecnico.php';
require_once '../src/DAO/TecnicoDAO.php';
require_once '../src/Services/TecnicoService.php';

$database = new Database();
$conn = $database->connect();

$chamadoDao = new ChamadoDAO($conn);
$equipamentoDao = new EquipamentoDAO($conn);
$tecnicoDao = new TecnicoDAO($conn);

$service = new ChamadoService($chamadoDao);
$tecnicoService = new TecnicoService($tecnicoDao);

$chamadoController = new ChamadoController($service, $tecnicoService);
$equipamentoController = new EquipamentoController($equipamentoDao);
$loginController = new LoginController($tecnicoDao);

$action = $_GET['action'] ?? 'index';

session_start();
if (!isset($_SESSION['user']) && $action !== 'login') {
    header('Location: index.php?action=login');
    exit;
}

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

    case 'login':
        $loginController->login();
        break;

    case 'logout':
        $loginController->logout();
        break;

    case 'perfil':
        $loginController->perfil();
        break;

    case 'historico':
        $chamadoController->historico();
        break;

    case 'show':
        $chamadoController->show();
        break;

    case 'update':
        $chamadoController->update();
        break;

    default:
        $chamadoController->index();
        break;
}
