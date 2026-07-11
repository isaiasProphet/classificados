<?php
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/dao/MensagemDAO.php';

$dao = new MensagemDAO();
echo "CHATS:\n";
print_r($dao->readChats(1));

echo "\nMENSAGENS:\n";
print_r($dao->readChat(1, 2, 1));
