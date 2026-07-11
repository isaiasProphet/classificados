<?php
session_start();
require_once __DIR__ . '/controllers/AnuncioController.php';

$action = $_GET['action'] ?? 'index';

$anuncioController = new AnuncioController();
require_once __DIR__ . '/controllers/UsuarioController.php';
$usuarioController = new UsuarioController();

switch ($action) {
    case 'get_subcategorias':
        require_once __DIR__ . '/controllers/CategoriaController.php';
        $categoriaController = new CategoriaController();
        $categoriaController->getSubcategorias();
        break;
    case 'login':
        $usuarioController->login();
        break;
    case 'authenticate':
        $usuarioController->authenticate();
        break;
    case 'register':
        $usuarioController->register();
        break;
    case 'store_user':
        $usuarioController->store();
        break;
    case 'logout':
        $usuarioController->logout();
        break;
    case 'create':
        $anuncioController->create();
        break;
    case 'store':
        $anuncioController->store();
        break;
    case 'show':
        $anuncioController->show();
        break;
    case 'index':
    default:
        $anuncioController->index();
        break;
}
