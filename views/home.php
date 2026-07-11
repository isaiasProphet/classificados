<?php require_once 'layout/header.php'; ?>

<!-- Seção de Categorias -->
<section class="categories-section">
    <div class="container">
        <div class="categories-grid">
            <a href="#" class="category-item">
                <div class="category-icon">🚗</div>
                <span class="category-name">Autos</span>
            </a>
            <a href="#" class="category-item">
                <div class="category-icon">🏠</div>
                <span class="category-name">Imóveis</span>
            </a>
            <a href="#" class="category-item">
                <div class="category-icon">📱</div>
                <span class="category-name">Eletrônicos</span>
            </a>
            <a href="#" class="category-item">
                <div class="category-icon">👕</div>
                <span class="category-name">Moda</span>
            </a>
            <a href="#" class="category-item">
                <div class="category-icon">🏡</div>
                <span class="category-name">Casa</span>
            </a>
            <a href="#" class="category-item">
                <div class="category-icon">⚽</div>
                <span class="category-name">Esportes</span>
            </a>
            <a href="#" class="category-item">
                <div class="category-icon">🎮</div>
                <span class="category-name">Games</span>
            </a>
            <a href="#" class="category-item">
                <div class="category-icon">📚</div>
                <span class="category-name">Livros</span>
            </a>
            <a href="#" class="category-item">
                <div class="category-icon">🐶</div>
                <span class="category-name">Pets</span>
            </a>
            <a href="#" class="category-item">
                <div class="category-icon">💼</div>
                <span class="category-name">Empregos</span>
            </a>
            <a href="#" class="category-item">
                <div class="category-icon">🔧</div>
                <span class="category-name">Serviços</span>
            </a>
        </div>
    </div>
</section>

<!-- Anúncios Recentes -->
<section class="container" style="padding-top: 10px;">
    <div class="section-header">
        <h2>Anúncios Recentes</h2>
    </div>

    <?php if (empty($anuncios)): ?>
        <div class="empty-state animate-fade-in">
            <div class="empty-state-icon">📦</div>
            <p>Nenhum anúncio encontrado no momento.</p>
            <p style="margin-top: 8px; font-size: 0.85rem;">Seja o primeiro a anunciar!</p>
        </div>
    <?php else: ?>
      <div class="grid">
            <?php foreach ($anuncios as $index => $anuncio): ?>
                <?php 
                // Se o anúncio for nulo, um array comum ou não tiver o método, pula para o próximo sem quebrar a página
                if (empty($anuncio) || !is_object($anuncio) || !method_exists($anuncio, 'getCapaPath')) {
                    continue;
                }
                ?>
                <div class="ad-card animate-fade-in" onclick="window.location='index.php?action=show&id=<?= $anuncio->getId() ?>';" style="animation-delay: <?= $index * 0.07 ?>s; cursor: pointer;">
                    <div class="ad-card-image">
                        <span class="ad-card-status">
                            <?= htmlspecialchars($anuncio->getStatus()->value ?? '') ?>
                        </span>
                        <?php 
                        $capa = $anuncio->getCapaPath();
                        
                        if (!empty($capa)): 
                        ?>
                            <img src="<?= htmlspecialchars($capa) ?>" alt="">
                        <?php else: ?>
                            📷 
                        <?php endif; ?>
                    </div>
                    <div class="ad-card-body">
                        <div class="ad-card-price">
                            R$ <?= number_format($anuncio->getPreco(), 2, ',', '.') ?>
                        </div>
                        <div class="ad-card-title">
                            <?= htmlspecialchars($anuncio->getTitulo()) ?>
                        </div>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            <?= htmlspecialchars($anuncio->getDescricao()) ?>
                        </p>
                        <div class="ad-card-footer">
                            <span class="ad-card-views">
                                👁️ <?= $anuncio->getVisualizacoes() ?> visitas
                            </span>
                            <a href="index.php?action=show&id=<?= $anuncio->getId() ?>" class="ad-card-action">Ver detalhes →</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>



    <?php endif; ?>
</section>

<?php require_once 'layout/footer.php'; ?>
