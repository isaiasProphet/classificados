<?php require_once __DIR__ . '/../layout/header.php'; ?>

<section class="container" style="padding-top: 40px; padding-bottom: 50px;">
    <!-- Banner do Anunciante / Perfil -->
    <div class="profile-header" style="background: linear-gradient(135deg, var(--olx-purple), #8b2cf5); color: white; padding: 40px 30px; border-radius: var(--radius-lg); margin-bottom: 40px; box-shadow: var(--shadow-md); position: relative; overflow: hidden;">
        <!-- Elemento de fundo abstrato decorativo -->
        <div style="position: absolute; right: -50px; bottom: -50px; width: 250px; height: 250px; background: rgba(255, 255, 255, 0.05); border-radius: 50%; pointer-events: none;"></div>
        <div style="position: absolute; left: 10%; top: -30px; width: 120px; height: 120px; background: rgba(255, 255, 255, 0.03); border-radius: 50%; pointer-events: none;"></div>
        
        <div style="display: flex; gap: 25px; align-items: center; flex-wrap: wrap; position: relative; z-index: 2;">
            <!-- Avatar Inicial -->
            <?php if ($usuario->getFotoPerfilPath()): ?>
                <img src="<?= htmlspecialchars($usuario->getFotoPerfilPath()) ?>" alt="<?= htmlspecialchars($usuario->getNome()) ?>" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid rgba(255, 255, 255, 0.4); box-shadow: var(--shadow-sm);">
            <?php else: ?>
                <div style="width: 100px; height: 100px; border-radius: 50%; background: rgba(255, 255, 255, 0.2); display: flex; align-items: center; justify-content: center; font-size: 2.8rem; font-weight: 700; border: 3px solid rgba(255, 255, 255, 0.4); text-transform: uppercase; box-shadow: var(--shadow-sm);">
                    <?= mb_substr(htmlspecialchars($usuario->getNome()), 0, 1) ?>
                </div>
            <?php endif; ?>
            
            <div style="flex: 1; min-width: 250px;">
                <h1 style="color: white; margin-bottom: 8px; font-size: 2rem; font-weight: 700; letter-spacing: -0.5px;">
                    <?= htmlspecialchars($usuario->getNome()) ?>
                </h1>
                
                <div style="display: flex; flex-wrap: wrap; gap: 15px 25px; font-size: 0.9rem; color: rgba(255, 255, 255, 0.9); margin-bottom: 10px;">
                    <span style="display: inline-flex; align-items: center; gap: 6px;">
                        📅 Membro desde: <?= $usuario->getDataCadastro()->format('d/m/Y') ?>
                    </span>
                    <?php if ($igreja): ?>
                        <span style="display: inline-flex; align-items: center; gap: 6px;">
                            ⛪ <?= htmlspecialchars($igreja->getNome()) ?>
                            <?php if ($usuario->getCargoIgreja()): ?>
                                (<?= htmlspecialchars($usuario->getCargoIgreja()) ?>)
                            <?php endif; ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <?php if ($usuario->getSobreMim()): ?>
            <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid rgba(255, 255, 255, 0.15); position: relative; z-index: 2;">
                <h3 style="font-size: 1.05rem; margin-bottom: 8px; color: rgba(255, 255, 255, 0.9); font-weight: 600;">Sobre mim</h3>
                <p style="color: rgba(255, 255, 255, 0.85); font-size: 0.95rem; line-height: 1.5; margin-bottom: 0; white-space: pre-wrap;"><?= htmlspecialchars($usuario->getSobreMim()) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Lista de Anúncios Ativos -->
    <div>
        <div class="section-header" style="margin-bottom: 30px;">
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">Anúncios de <?= htmlspecialchars($usuario->getNome()) ?></h2>
        </div>

        <?php if (empty($anuncios)): ?>
            <div class="empty-state" style="padding: 50px 20px; text-align: center; background: var(--bg-white); border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
                <div class="empty-state-icon" style="font-size: 3rem; margin-bottom: 15px;">📦</div>
                <p style="font-size: 1.1rem; color: var(--text-secondary); margin-bottom: 0;">Este anunciante não possui nenhum anúncio ativo no momento.</p>
            </div>
        <?php else: ?>
            <div class="grid">
                <?php foreach ($anuncios as $index => $anuncio): ?>
                    <div class="ad-card" onclick="window.location='index.php?action=show&id=<?= $anuncio->getId() ?>';" style="cursor: pointer; animation: fadeInUp 0.4s ease forwards; opacity: 0; animation-delay: <?= $index * 0.05 ?>s;">
                        <div class="ad-card-image">
                            <?php 
                            $capa = $anuncio->getCapaPath();
                            if (!empty($capa)): 
                            ?>
                                <img src="<?= htmlspecialchars($capa) ?>" alt="<?= htmlspecialchars($anuncio->getTitulo()) ?>">
                            <?php else: ?>
                                📷 Sem foto
                            <?php endif; ?>
                        </div>
                        <div class="ad-card-body">
                            <div class="ad-card-price">
                                R$ <?= number_format($anuncio->getPreco(), 2, ',', '.') ?>
                            </div>
                            <div class="ad-card-title" style="min-height: 40px;">
                                <?= htmlspecialchars($anuncio->getTitulo()) ?>
                            </div>
                            <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                <?= htmlspecialchars($anuncio->getDescricao()) ?>
                            </p>
                            <div class="ad-card-footer">
                                <span class="ad-card-views">
                                    👁️ <?= $anuncio->getVisualizacoes() ?> visitas
                                </span>
                                <span class="ad-card-action">Ver detalhes →</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Adicionando a animação keyframe fadeInUp inline se não estiver no style.css -->
<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
