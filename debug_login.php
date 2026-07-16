<?php
session_start();
$_SESSION['usuario_id'] = 1;
$_SESSION['usuario_nome'] = 'ISAIAS BRAGA NUNES';
echo "Logged in as Admin. <a href='index.php?action=admin_bairros_list'>Go to Bairros List</a>";
