<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ClassificadosPro — O melhor site de classificados do Brasil. Compre e venda de tudo.">
    <title>ClassificadosPro — Compre e Venda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="views/assets/css/style.css">
</head>
<body>
    <nav class="navbar" style="padding-top: 0; padding-bottom: 0;">
        <div class="container nav-content">
            <a href="index.php" class="brand">Classificados<span class="highlight">Pro</span></a>

            <form action="index.php" method="GET" class="search-bar" style="margin-bottom: 0;">
                <input type="text" name="search" placeholder="Buscar produtos, veículos, serviços..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                <button type="submit" aria-label="Buscar">
                    &#128269;
                </button>
            </form>

            <ul class="nav-links mb-0 p-0" style="display: flex; align-items: center;">
                <?php if (isset($_SESSION['usuario_id'])): 
                    require_once __DIR__ . '/../../dao/UsuarioDAO.php';
                    $usuarioDAO = new UsuarioDAO();
                    $usuarioLogado = $usuarioDAO->readById($_SESSION['usuario_id']);
                    $isCliente = ($usuarioLogado && $usuarioLogado->getPermissoes() === PermissaoUsuario::CLIENTE);
                ?>
                    <li class="nav-item dropdown" style="list-style: none; margin-right: 15px;">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: rgba(255,255,255,0.9); font-size: 0.95rem; font-weight: 500;">
                            Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm user-dropdown-menu" aria-labelledby="userDropdown">
                            <?php if (!$isCliente): ?>
                            <li><a class="dropdown-item" href="index.php?action=meus_anuncios" style="color: #1a1a2e !important;">Meus anúncios</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="index.php?action=logout" style="color: #1a1a2e !important;">Sair</a></li>
                        </ul>
                    </li>
                    <?php if (!$isCliente): ?>
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
