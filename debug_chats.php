<?php
$_GET['action'] = 'api_listar_chats';
session_start();
$_SESSION['usuario_id'] = 1; // mock user 1
require_once 'index.php';
