<?php
session_start();
$_SESSION['usuario_id'] = 1;
session_write_close();
$cookie = 'PHPSESSID=' . session_id();

$ch = curl_init('http://localhost/index.php?action=api_listar_chats');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIE, $cookie);
$response = curl_exec($ch);
echo "API LISTAR CHATS:\n$response\n\n";

$ch2 = curl_init('http://localhost/index.php?action=listar_mensagens&anuncioId=1&outroUsuarioId=2');
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_COOKIE, $cookie);
$response2 = curl_exec($ch2);
echo "API LISTAR MENSAGENS:\n$response2\n";
