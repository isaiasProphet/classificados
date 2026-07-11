<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container" style="padding-top: 30px;">
    <div class="ad-details" style="display: flex; gap: 30px; flex-wrap: wrap;">
        <!-- Lado esquerdo: Imagens -->
        <div class="ad-gallery" style="flex: 1.5; min-width: 300px; background: var(--bg-card); padding: 20px; border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
            <?php 
            $capaEncontrada = false;
            foreach ($imagens as $img) {
                if ($img->isImgPrincipal()) {
                    $capaEncontrada = true;
                    echo '<div class="main-image" style="width: 100%; height: 450px; border-radius: var(--radius); overflow: hidden; margin-bottom: 15px; background: var(--bg-primary); display: flex; align-items: center; justify-content: center;">';
                    echo '<img src="' . htmlspecialchars($img->getCaminhoArquivo()) . '" alt="Imagem do Anúncio" style="max-width: 100%; max-height: 100%; object-fit: contain;">';
                    echo '</div>';
                    break;
                }
            }
            if (!$capaEncontrada && !empty($imagens)) {
                 $primeiraImg = $imagens[0];
                 echo '<div class="main-image" style="width: 100%; height: 450px; border-radius: var(--radius); overflow: hidden; margin-bottom: 15px; background: var(--bg-primary); display: flex; align-items: center; justify-content: center;">';
                 echo '<img src="' . htmlspecialchars($primeiraImg->getCaminhoArquivo()) . '" alt="Imagem do Anúncio" style="max-width: 100%; max-height: 100%; object-fit: contain;">';
                 echo '</div>';
            } elseif (empty($imagens)) {
                echo '<div class="main-image" style="width: 100%; height: 450px; border-radius: var(--radius); overflow: hidden; margin-bottom: 15px; background: var(--bg-primary); display: flex; align-items: center; justify-content: center; font-size: 4rem; color: var(--text-muted);">';
                echo '📷 Sem foto';
                echo '</div>';
            }
            ?>
            
            <div class="thumbnails" style="display: flex; gap: 10px; overflow-x: auto; padding-bottom: 10px;">
                <?php foreach ($imagens as $img): ?>
                    <div style="min-width: 80px; width: 80px; height: 80px; border-radius: var(--radius); overflow: hidden; border: 2px solid <?= $img->isImgPrincipal() ? 'var(--olx-orange)' : 'transparent' ?>; cursor: pointer; transition: transform 0.2s;">
                        <img src="<?= htmlspecialchars($img->getCaminhoArquivo()) ?>" alt="Miniatura" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="margin-bottom: 30px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <h3 style="font-size: 1.2rem; margin-bottom: 15px; color: var(--text-primary);">Descrição</h3>
                <p style="font-size: 1rem; line-height: 1.6; color: var(--text-secondary); white-space: pre-wrap;"><?= htmlspecialchars($anuncio->getDescricao()) ?></p>
            </div>
        </div>

        
        <!-- Lado direito: Detalhes -->
        <div class="ad-info" style="flex: 1; min-width: 300px; background: var(--bg-card); padding: 30px; border-radius: var(--radius-lg); border: 1px solid var(--border-color); display: flex; flex-direction: column;">
            <div style="margin-bottom: 20px;">
               
                <h1 style="font-size: 1.8rem; margin-bottom: 15px; color: var(--text-primary);"><?= htmlspecialchars($anuncio->getTitulo()) ?></h1>
                <p style="font-size: 2.2rem; font-weight: 700; color: var(--text-primary); margin-bottom: 20px;">
                    R$ <?= number_format($anuncio->getPreco(), 2, ',', '.') ?>
                </p>
                <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 20px; display: flex; gap: 15px;">
                    <span>📅 Publicado em: <?= $anuncio->getDataCriacao()->format('d/m/Y H:i') ?></span>
                   
                </div>


                 <div style="margin-top: auto; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <button class="btn-primary" style="width: 100%; padding: 15px; font-size: 1.1rem; text-align: center; display: flex; justify-content: center; align-items: center; gap: 10px; margin-bottom: 15px; background: var(--olx-orange); border: none; border-radius: 30px; cursor: pointer; transition: background 0.2s;">
                    💬 Chat com o vendedor
                </button>
                <button class="btn-outline" style="width: 100%; padding: 15px; font-size: 1.1rem; text-align: center; display: flex; justify-content: center; align-items: center; gap: 10px; color: var(--olx-purple); border: 2px solid var(--olx-purple); background: transparent; border-radius: 30px; cursor: pointer; transition: background 0.2s, color 0.2s;" onmouseover="this.style.background='var(--olx-purple)'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='var(--olx-purple)';">
                    📞 Ver telefone
                </button>
            </div> 

            </div>

        </div>
    </div>
</div>

<script>
    // Script para trocar a imagem principal ao clicar na miniatura
    document.querySelectorAll('.thumbnails img').forEach(thumb => {
        thumb.addEventListener('click', function() {
            const mainImg = document.querySelector('.main-image img');
            if (mainImg) {
                mainImg.src = this.src;
            }
            document.querySelectorAll('.thumbnails div').forEach(div => div.style.borderColor = 'transparent');
            this.parentElement.style.borderColor = 'var(--olx-orange)';
        });
        
        // Efeito de hover simples
        thumb.parentElement.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });
        thumb.parentElement.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
