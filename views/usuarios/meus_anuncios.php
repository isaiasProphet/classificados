<?php require_once __DIR__ . '/../layout/header.php'; ?>

<section class="container" style="padding-top: 40px;">
    <div class="section-header">
        <h2>Meus Anúncios</h2>
    </div>

    <?php if (empty($anuncios)): ?>
        <div class="empty-state animate-fade-in">
            <div class="empty-state-icon">📦</div>
            <p>Você ainda não possui nenhum anúncio publicado.</p>
            <p style="margin-top: 8px; font-size: 0.85rem;">Que tal <a href="index.php?action=create" style="color: var(--olx-purple); text-decoration: underline;">criar um agora</a>?</p>
        </div>
    <?php else: ?>
        <div class="grid">
            <?php foreach ($anuncios as $index => $anuncio): ?>
                <?php 
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
                            <a href="index.php?action=edit&id=<?= $anuncio->getId() ?>" class="ad-card-action" style="margin-right: 12px; color: var(--olx-orange);" onclick="event.stopPropagation();">✎ Editar</a>
                            <a href="index.php?action=show&id=<?= $anuncio->getId() ?>" class="ad-card-action">Ver detalhes →</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
