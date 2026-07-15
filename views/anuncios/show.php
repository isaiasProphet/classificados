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
                    <span>Publicado em: <?= $anuncio->getDataCriacao()->format('d/m/Y H:i') ?></span>
                    
                </div>
                  <div style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 20px; display: flex; gap: 15px;">
                    
                    <span> 
                        
                        Anunciado por: <?= $usuario->getNome(); ?>
                    </span>
                </div>


                 <div style="margin-top: auto; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <?php if (isset($_SESSION['usuario_id']) && $_SESSION['usuario_id'] !== $anuncio->getUsuarioId()): ?>
                    <button class="btn-primary" onclick="abrirChat()" style="width: 100%; padding: 15px; font-size: 1.1rem; text-align: center; display: flex; justify-content: center; align-items: center; gap: 10px; margin-bottom: 15px; background: var(--olx-orange); border: none; border-radius: 30px; cursor: pointer; transition: background 0.2s;">
                        💬 Chat com o vendedor
                    </button>
                <?php elseif (!isset($_SESSION['usuario_id'])): ?>
                    <button class="btn-primary" onclick="window.location.href='index.php?action=login'" style="width: 100%; padding: 15px; font-size: 1.1rem; text-align: center; display: flex; justify-content: center; align-items: center; gap: 10px; margin-bottom: 15px; background: var(--olx-orange); border: none; border-radius: 30px; cursor: pointer; transition: background 0.2s;">
                        💬 Faça login para usar o Chat
                    </button>
                <?php endif; ?>
                <button id="btn-telefone" onclick="mostrarTelefone()" class="btn-outline" style="width: 100%; padding: 15px; font-size: 1.1rem; text-align: center; display: flex; justify-content: center; align-items: center; gap: 10px; color: var(--olx-purple); border: 2px solid var(--olx-purple); background: transparent; border-radius: 30px; cursor: pointer; transition: background 0.2s, color 0.2s;" onmouseover="this.style.background='var(--olx-purple)'; this.style.color='white';" onmouseout="this.style.background='transparent'; this.style.color='var(--olx-purple)';">
                    📞 Ver telefone
                </button>
            </div> 

            </div>

        </div>
    </div>
</div>

<!-- Modal do Chat -->
<div id="chatModal" style="display: none; position: fixed; bottom: 20px; right: 20px; width: 350px; background: var(--bg-card); border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.2); z-index: 1000; display: flex; flex-direction: column; overflow: hidden;" class="hidden-modal">
    <div style="background: var(--olx-purple); color: white; padding: 15px; display: flex; justify-content: space-between; align-items: center;">
        <h4 style="margin: 0; font-size: 1.1rem;">Chat com o vendedor</h4>
        <button onclick="fecharChat()" style="background: transparent; border: none; color: white; font-size: 1.2rem; cursor: pointer;">&times;</button>
    </div>
    
    <div id="chatMessages" style="flex: 1; padding: 15px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; height: 300px; background: var(--bg-body);">
        <!-- Mensagens serão carregadas aqui via JS -->
    </div>
    
    <div style="padding: 10px; border-top: 1px solid var(--border-color); background: var(--bg-card); display: flex; gap: 10px;">
        <input type="text" id="chatInput" placeholder="Digite sua mensagem..." style="flex: 1; padding: 10px; border: 1px solid var(--border-color); border-radius: 20px; outline: none;">
        <button onclick="enviarMensagem()" style="background: var(--olx-purple); color: white; border: none; padding: 10px 15px; border-radius: 20px; cursor: pointer;">Enviar</button>
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

    // Lógica do Chat
    const chatModal = document.getElementById('chatModal');
    const chatMessages = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    chatModal.style.display = 'none';

    function abrirChat() {
        chatModal.style.display = 'flex';
        chatModal.classList.remove('hidden-modal');
        carregarMensagens();
    }

    function fecharChat() {
        chatModal.style.display = 'none';
        chatModal.classList.add('hidden-modal');
    }

    function carregarMensagens() {
        const anuncioId = <?= $anuncio->getId() ?>;
        const outroUsuarioId = <?= $anuncio->getUsuarioId() ?>;
        
        fetch(`index.php?action=listar_mensagens&anuncioId=${anuncioId}&outroUsuarioId=${outroUsuarioId}`)
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    chatMessages.innerHTML = '';
                    const myId = <?= isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0 ?>;
                    const vendedorNome = <?= json_encode($usuario->getNome()) ?>;
                    
                    data.mensagens.forEach(msg => {
                        const isMine = Number(msg.remetente_usuario_id) === myId;
                        const align = isMine ? 'align-self: flex-end; background: var(--olx-purple); color: white;' : 'align-self: flex-start; background: #e0e0e0; color: #333;';
                        const senderName = isMine ? 'Você' : vendedorNome;
                        
                        chatMessages.innerHTML += `
                            <div style="max-width: 80%; padding: 10px 15px; border-radius: 15px; ${align}">
                                <div style="font-size: 0.75rem; font-weight: bold; margin-bottom: 4px; opacity: 0.9;">${senderName}</div>
                                <div>${msg.texto}</div>
                                <div style="font-size: 0.7rem; margin-top: 5px; opacity: 0.8; text-align: right;">${msg.data_envio}</div>
                            </div>
                        `;
                    });
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            })
            .catch(err => console.error(err));
    }

    function enviarMensagem() {
        const texto = chatInput.value.trim();
        if(!texto) return;
        
        const anuncioId = <?= $anuncio->getId() ?>;
        const destinatarioUsuarioId = <?= $anuncio->getUsuarioId() ?>;
        
        fetch('index.php?action=enviar_mensagem', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ anuncioId, texto, destinatarioUsuarioId })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                chatInput.value = '';
                carregarMensagens();
            } else {
                alert('Erro ao enviar mensagem: ' + data.error);
            }
        })
        .catch(err => console.error(err));
    }

    function mostrarTelefone() {
        const telefone = <?= json_encode($usuario->getTelefone()) ?>;
        if (telefone && telefone.trim() !== "") {
            document.getElementById('btn-telefone').innerHTML = '📞 ' + telefone;
        } else {
            alert('Não existe número cadastrado para este anunciante. Por favor, fale pelo chat!');
        }
    }
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
