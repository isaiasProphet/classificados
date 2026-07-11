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
    <link rel="stylesheet" href="views/assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container nav-content">
            <a href="index.php" class="brand">Classificados<span class="highlight">Pro</span></a>

            <div class="search-bar">
                <input type="text" placeholder="Buscar produtos, veículos, serviços...">
                <button type="button" aria-label="Buscar">
                    &#128269;
                </button>
            </div>

            <ul class="nav-links">
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <li><span style="color: rgba(255,255,255,0.8); font-size: 0.9rem;">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span></li>
                    <li><a href="index.php?action=create" class="btn-anunciar">&#10010; Anunciar</a></li>
                    <li><a href="index.php?action=logout" style="color: rgba(255,255,255,0.7); font-size: 0.85rem;">Sair</a></li>
                <?php else: ?>
                    <li><a href="index.php?action=login">Entrar</a></li>
                    <li><a href="index.php?action=register" class="btn-outline">Cadastrar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <main>
