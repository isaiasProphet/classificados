<?php
$_GET['action'] = 'listar_mensagens';
$_GET['anuncioId'] = 1;
$_GET['outroUsuarioId'] = 2;
session_start();
$_SESSION['usuario_id'] = 1;
require_once 'index.php';
