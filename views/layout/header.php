<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Classificados — Compre, venda e divulgue.">
    <title>Classificados — Compre e Venda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    

    <link rel="stylesheet" href="views/assets/css/style.css">
</head>
<body>
    <nav class="navbar" style="padding-top: 0; padding-bottom: 0;">
        <div class="container nav-content">
            <a href="index.php" class="brand">Classificados</a>

            <form action="index.php" method="GET" class="search-bar" style="margin-bottom: 0;">
                <input type="text" name="search" placeholder="Buscar produtos, veículos, serviços..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button type="submit" aria-label="Buscar">
                    &#128269;
                </button>
            </form>

            <?php if (isset($_SESSION['usuario_id'])): 
                require_once __DIR__ . '/../../dao/UsuarioDAO.php';
                require_once __DIR__ . '/../../dao/MensagemDAO.php';
                $usuarioDAO = new UsuarioDAO();
                $usuarioLogado = $usuarioDAO->readById($_SESSION['usuario_id']);
                $isCliente = ($usuarioLogado && $usuarioLogado->getPermissoes() === PermissaoUsuario::CLIENTE);
                $mensagemDAO = new MensagemDAO();
                $unreadCount = $mensagemDAO->countUnread($_SESSION['usuario_id']);
            ?>
            <div class="nav-user-bar nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: rgba(255,255,255,0.9); font-size: 0.95rem; font-weight: 500;">
                    <?php if ($usuarioLogado && $usuarioLogado->getFotoPerfilPath()): ?>
                        <img src="<?= htmlspecialchars($usuarioLogado->getFotoPerfilPath()) ?>" alt="Foto" class="rounded-circle" style="width: 28px; height: 28px; object-fit: cover; border: 1px solid rgba(255,255,255,0.5);">
                    <?php else: ?>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.5); font-size: 0.8rem;">
                            <?= mb_substr(htmlspecialchars($_SESSION['usuario_nome']), 0, 1) ?>
                        </div>
                    <?php endif; ?>
                    Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm user-dropdown-menu" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="index.php?action=meu_perfil" style="color: #1a1a2e !important;">Meu Perfil</a></li>
                    <?php if ($usuarioLogado && $usuarioLogado->getPermissoes() === PermissaoUsuario::ADMIN): ?>
                        <li><a class="dropdown-item" href="index.php?action=admin" style="color: #1a1a2e !important;">Admin</a></li>
                    <?php endif; ?>
                    <?php if (!$isCliente): ?>
                        <li><a class="dropdown-item" href="index.php?action=meus_anuncios" style="color: #1a1a2e !important;">Meus anúncios</a></li>
                    <?php endif; ?>
                    <?php if ($isCliente): ?>
                    <li><a class="dropdown-item d-flex align-items-center justify-content-between" href="index.php?action=listar_chats" style="color: #1a1a2e !important;">Chat<?php if ($unreadCount > 0): ?><span class="chat-unread-badge"><?= $unreadCount ?></span><?php endif; ?></a></li>
                    <?php endif; ?>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="index.php?action=logout" style="color: #1a1a2e !important;">Sair</a></li>
                </ul>
            </div>
            <?php endif; ?>

            <ul class="nav-links mb-0 p-0" style="display: flex; align-items: center;">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <?php if (!$isCliente): ?>
                        <li style="position: relative;"> 
                            <a href="index.php?action=listar_chats" style="position: relative; display: inline-flex; align-items: center; gap: 5px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16">
                                <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0m4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                <path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9 9 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.4 10.4 0 0 1-.524 2.318l-.003.011a11 11 0 0 1-.244.637c-.079.186.074.394.273.362a22 22 0 0 0 .693-.125m.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6-3.004 6-7 6a8 8 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a11 11 0 0 0 .398-2"/>
                            </svg>    
                            Chat<?php if ($unreadCount > 0): ?><span class="chat-unread-badge"><?= $unreadCount ?></span><?php endif; ?></a>
                        </li>
                        <li><a href="index.php?action=create" class="btn-anunciar">&#10010; Anunciar</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a href="index.php?action=login">Entrar</a></li>
                    <li><a href="index.php?action=register" class="btn-outline">Cadastrar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <main>
