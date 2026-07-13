<?php
session_start();
date_default_timezone_set('America/Sao_Paulo');
require_once __DIR__ . '/controllers/AnuncioController.php';

$action = $_GET['action'] ?? 'index';

$anuncioController = new AnuncioController();
require_once __DIR__ . '/controllers/UsuarioController.php';
$usuarioController = new UsuarioController();

require_once __DIR__ . '/controllers/MensagemController.php';
$mensagemController = new MensagemController();

require_once __DIR__ . '/controllers/AdminController.php';
$adminController = new AdminController();


switch ($action) {
    case 'get_subcategorias':
        require_once __DIR__ . '/controllers/CategoriaController.php';
        $categoriaController = new CategoriaController();
        $categoriaController->getSubcategorias();
        break;
    case 'admin':
        $adminController->admin();
        break;
    case 'admin_igreja_add':
        $adminController->igrejaAdd();
        break;
    case 'admin_igreja_store':
        $adminController->igrejaStore();
        break;
    case 'admin_igreja_list':
        $adminController->igrejaList();
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
    case 'meus_anuncios':
        $usuarioController->meusAnuncios();
        break;
    case 'meu_perfil':
        $usuarioController->perfil();
        break;
    case 'atualizar_perfil':
        $usuarioController->atualizarPerfil();
        break;
    case 'create':
        $anuncioController->create();
        break;
    case 'store':
        $anuncioController->store();
        break;
    case 'edit':
        $anuncioController->edit();
        break;
    case 'update':
        $anuncioController->update();
        break;
    case 'show':
        $anuncioController->show();
        break;
    case 'enviar_mensagem':
        $mensagemController->enviar();
        break;
    case 'listar_mensagens':
        $mensagemController->listar();
        break;
    case 'listar_chats':
        $mensagemController->listarChats();
        break;
    case 'api_listar_chats':
        $mensagemController->apiListarChats();
        break;
    case 'index':
    default:
        $anuncioController->index();
        break;
}
