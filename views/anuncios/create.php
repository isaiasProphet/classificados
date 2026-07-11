<?php require_once __DIR__ . '/../layout/header.php'; ?>

<section style="max-width: 700px; margin: 40px auto; padding: 0 20px;">
    <h2 style="text-align: center; margin-bottom: 24px;">Novo Anúncio</h2>
    
    <div class="form-card animate-fade-in">
        <form action="index.php?action=store" method="POST" enctype="multipart/form-data" id="formAnuncio">
            
            <div class="form-group">
                <label for="titulo">Título do Anúncio</label>
                <input type="text" id="titulo" name="titulo" class="form-control" required placeholder="Ex: Bicicleta Caloi Aro 29">
            </div>

            <div class="form-group">
                <label for="preco">Preço (R$)</label>
                <input type="number" id="preco" name="preco" class="form-control" step="0.01" min="0" required placeholder="0.00">
            </div>

            <div class="form-group">
                <label for="categoriaId">Categoria</label>
                <select id="categoriaId" class="form-control" required>
                    <option value="">Selecione uma categoria...</option>
                    <?php if (!empty($categorias)): ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= $categoria->getId() ?>">
                                <?= htmlspecialchars($categoria->getNome()) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="subCategoriaId">Sub Categoria</label>
                <select id="subCategoriaId" name="subCategoriaId" class="form-control" required disabled>
                    <option value="">Selecione primeiro uma categoria...</option>
                </select>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" class="form-control" rows="5" required placeholder="Descreva os detalhes do seu produto..."></textarea>
            </div>

            <!-- ============================================
                 Seção de Upload de Imagens
                 ============================================ -->
            <div class="form-group">
                <label>Fotos do Anúncio <span class="img-upload-hint">(máximo 8 fotos — JPG, PNG ou WebP até 5MB)</span></label>
                
                <div class="img-upload-area" id="imgUploadArea">
                    <div class="img-upload-dropzone" id="dropzone">
                        <div class="dropzone-icon">📷</div>
                        <p class="dropzone-text">Arraste suas fotos aqui ou <span class="dropzone-link">clique para selecionar</span></p>
                        <p class="dropzone-subtext">A primeira foto será usada como capa. Clique na ⭐ para alterar.</p>
                        <input type="file" id="fileInput" name="imagens[]" multiple accept="image/jpeg,image/png,image/webp,image/gif" style="display: none;">
                    </div>

                    <div class="img-preview-grid" id="previewGrid"></div>
                </div>

                <!-- Campo oculto para indicar a imagem principal -->
                <input type="hidden" name="imgPrincipal" id="imgPrincipalInput" value="0">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 16px; align-items: center; margin-top: 30px;">
                <a href="index.php" style="color: var(--text-secondary); font-weight: 500; font-size: 0.95rem;">Cancelar</a>
                <button type="submit" class="btn-anunciar">Publicar Anúncio</button>
            </div>
            
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== Lógica de Categorias =====
        const categoriaSelect = document.getElementById('categoriaId');
        const subCategoriaSelect = document.getElementById('subCategoriaId');

        categoriaSelect.addEventListener('change', function() {
            const categoriaId = this.value;
            
            subCategoriaSelect.innerHTML = '<option value="">Carregando...</option>';
            subCategoriaSelect.disabled = true;

            if (categoriaId) {
                fetch(`index.php?action=get_subcategorias&categoria_id=${categoriaId}`)
                    .then(response => response.json())
                    .then(data => {
                        subCategoriaSelect.innerHTML = '<option value="">Selecione uma sub categoria...</option>';
                        if (data.length > 0) {
                            data.forEach(sub => {
                                const option = document.createElement('option');
                                option.value = sub.id;
                                option.textContent = sub.nome;
                                subCategoriaSelect.appendChild(option);
                            });
                            subCategoriaSelect.disabled = false;
                        } else {
                            subCategoriaSelect.innerHTML = '<option value="">Nenhuma sub categoria encontrada</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar subcategorias:', error);
                        subCategoriaSelect.innerHTML = '<option value="">Erro ao carregar</option>';
                    });
            } else {
                subCategoriaSelect.innerHTML = '<option value="">Selecione primeiro uma categoria...</option>';
            }
        });

        // ===== Lógica de Upload de Imagens =====
        const MAX_IMAGES = 8;
        const MAX_SIZE = 5 * 1024 * 1024; // 5MB
        const ALLOWED = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

        const fileInput = document.getElementById('fileInput');
        const dropzone = document.getElementById('dropzone');
        const previewGrid = document.getElementById('previewGrid');
        const imgPrincipalInput = document.getElementById('imgPrincipalInput');

        let selectedFiles = []; // DataTransfer pattern para manter os arquivos
        let coverIndex = 0;

        // Clique na dropzone abre o seletor de arquivos
        dropzone.addEventListener('click', () => fileInput.click());

        // Drag & Drop
        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });

        dropzone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropzone.classList.remove('dragover');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('dragover');
            handleFiles(e.dataTransfer.files);
        });

        // Seleção via input
        fileInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        function handleFiles(newFiles) {
            const remaining = MAX_IMAGES - selectedFiles.length;
            if (remaining <= 0) {
                showToast('Limite de ' + MAX_IMAGES + ' fotos atingido.');
                return;
            }

            const filesToAdd = Array.from(newFiles).slice(0, remaining);

            for (const file of filesToAdd) {
                if (!ALLOWED.includes(file.type)) {
                    showToast('Formato não suportado: ' + file.name);
                    continue;
                }
                if (file.size > MAX_SIZE) {
                    showToast('Arquivo muito grande: ' + file.name + ' (máx 5MB)');
                    continue;
                }
                selectedFiles.push(file);
            }

            updateFileInput();
            renderPreviews();
        }

        function updateFileInput() {
            // Recria o input de arquivos com o DataTransfer API
            const dt = new DataTransfer();
            selectedFiles.forEach(f => dt.items.add(f));
            fileInput.files = dt.files;

            // Corrigir coverIndex se necessário
            if (coverIndex >= selectedFiles.length) {
                coverIndex = 0;
            }
            imgPrincipalInput.value = coverIndex;
        }

        function renderPreviews() {
            previewGrid.innerHTML = '';

            if (selectedFiles.length === 0) {
                dropzone.style.display = '';
                return;
            }

            // Mostrar dropzone reduzida se tiver espaço
            if (selectedFiles.length < MAX_IMAGES) {
                dropzone.style.display = '';
            } else {
                dropzone.style.display = 'none';
            }

            selectedFiles.forEach((file, index) => {
                const card = document.createElement('div');
                card.className = 'img-preview-card' + (index === coverIndex ? ' is-cover' : '');
                card.setAttribute('data-index', index);

                const reader = new FileReader();
                reader.onload = (e) => {
                    card.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}">
                        <div class="img-preview-overlay">
                            <button type="button" class="img-btn-cover" title="Definir como capa" onclick="setCover(${index})">
                                ${index === coverIndex ? '⭐' : '☆'}
                            </button>
                            <button type="button" class="img-btn-remove" title="Remover foto" onclick="removeImage(${index})">
                                ✕
                            </button>
                        </div>
                        ${index === coverIndex ? '<div class="img-cover-badge">CAPA</div>' : ''}
                    `;
                };
                reader.readAsDataURL(file);

                previewGrid.appendChild(card);
            });

            // Counter
            const counter = document.createElement('div');
            counter.className = 'img-counter';
            counter.textContent = selectedFiles.length + '/' + MAX_IMAGES + ' fotos';
            previewGrid.appendChild(counter);
        }

        // Funções globais para os botões inline
        window.setCover = function(index) {
            coverIndex = index;
            imgPrincipalInput.value = coverIndex;
            renderPreviews();
        };

        window.removeImage = function(index) {
            selectedFiles.splice(index, 1);
            if (coverIndex >= selectedFiles.length) {
                coverIndex = Math.max(0, selectedFiles.length - 1);
            } else if (index < coverIndex) {
                coverIndex--;
            } else if (index === coverIndex) {
                coverIndex = 0;
            }
            updateFileInput();
            renderPreviews();
        };

        function showToast(message) {
            // Toast simples
            let toast = document.getElementById('imgToast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'imgToast';
                toast.className = 'img-toast';
                document.body.appendChild(toast);
            }
            toast.textContent = message;
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 3000);
        }
    });
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
