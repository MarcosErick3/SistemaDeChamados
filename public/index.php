<?php

spl_autoload_register(function ($class) {
    $directories = [
        __DIR__ . '/../config',
        __DIR__ . '/../src/Models',
        __DIR__ . '/../src/Builders',
        __DIR__ . '/../src/DAO',
        __DIR__ . '/../src/Services',
        __DIR__ . '/../src/Commands',
        __DIR__ . '/../src/Controllers',
    ];

    foreach ($directories as $directory) {
        $file = $directory . '/' . $class . '.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

$database = new Database();
$conn = $database->connect();

$chamadoDao = new ChamadoDAO($conn);
$equipamentoDao = new EquipamentoDAO($conn);
$tecnicoDao = new TecnicoDAO($conn);

$service = new ChamadoService($chamadoDao);
$tecnicoService = new TecnicoService($tecnicoDao);
$pdfService = new PdfService();

$chamadoController = new ChamadoController($service, $tecnicoService, $pdfService);
$equipamentoController = new EquipamentoController($equipamentoDao);
$loginController = new LoginController($tecnicoDao);

$action = $_GET['action'] ?? 'listar';

session_start();
if (!isset($_SESSION['user']) && !in_array($action, ['entrar', 'login'], true)) {
    header('Location: index.php?action=entrar');
    exit;
}

switch ($action) {
    case 'listar':
        $chamadoController->listar();
        break;

    case 'salvar':
    case 'store':
        $chamadoController->salvar();
        break;

    case 'buscarEquipamento':
        $equipamentoController->buscarPorNumeroSerie();
        break;

    case 'entrar':
    case 'login':
        $loginController->entrar();
        break;

    case 'sair':
    case 'logout':
        $loginController->sair();
        break;

    case 'perfil':
        $loginController->perfil();
        break;

    case 'historico':
        $chamadoController->historico();
        break;

    case 'detalhes':
    case 'show':
        $chamadoController->detalhes();
        break;

    case 'atualizar':
    case 'update':
        $chamadoController->atualizar();
        break;

    case 'index':
    default:
        $chamadoController->listar();
        break;
}
