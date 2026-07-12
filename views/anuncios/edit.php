<?php require_once __DIR__ . '/../layout/header.php'; ?>

<section style="max-width: 700px; margin: 40px auto; padding: 0 20px;">
    <h2 style="text-align: center; margin-bottom: 24px;">Editar Anúncio</h2>
    
    <div class="form-card animate-fade-in">
        <form action="index.php?action=update" method="POST" id="formAnuncio">
            <input type="hidden" name="id" value="<?= htmlspecialchars($anuncio->getId()) ?>">
            
            <div class="form-group">
                <label for="titulo">Título do Anúncio</label>
                <input type="text" id="titulo" name="titulo" class="form-control" required value="<?= htmlspecialchars($anuncio->getTitulo()) ?>">
            </div>

            <div class="form-group">
                <label for="preco">Preço (R$)</label>
                <input type="number" id="preco" name="preco" class="form-control" step="0.01" min="0" required value="<?= htmlspecialchars($anuncio->getPreco()) ?>">
            </div>

            <div class="form-group">
                <label for="categoriaId">Categoria</label>
                <select id="categoriaId" class="form-control" required>
                    <option value="">Selecione uma categoria...</option>
                    <?php if (!empty($categorias)): ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= $categoria->getId() ?>" <?= $categoriaId == $categoria->getId() ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categoria->getNome()) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="subCategoriaId">Sub Categoria</label>
                <select id="subCategoriaId" name="subCategoriaId" class="form-control" required>
                    <option value="<?= htmlspecialchars($anuncio->getSubCategoriaId()) ?>" selected>Categoria atual mantida. Selecione acima para trocar.</option>
                </select>
            </div>

            <div class="form-group">
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" class="form-control" rows="5" required><?= htmlspecialchars($anuncio->getDescricao()) ?></textarea>
            </div>

            <div class="form-group">
                <label for="status">Status do Anúncio</label>
                <?php $statusAtual = $anuncio->getStatus()->value; ?>
                <select id="status" name="status" class="form-control" required>
                    <option value="ativo" <?= $statusAtual === 'ativo' ? 'selected' : '' ?>>🟢 Ativo</option>
                    <option value="pausado" <?= $statusAtual === 'pausado' ? 'selected' : '' ?>>⏸️ Pausado</option>
                    <option value="vendido" <?= $statusAtual === 'vendido' ? 'selected' : '' ?>>✅ Vendido</option>
                    <option value="pendente_aprovacao" <?= $statusAtual === 'pendente_aprovacao' ? 'selected' : '' ?>>⏳ Pendente de Aprovação</option>
                </select>
                <small style="display: block; margin-top: 6px; color: var(--text-muted); font-size: 0.82rem;">
                    Altere o status para pausar, reativar ou marcar como vendido.
                </small>
            </div>

            <div class="form-group">
                <label>Fotos do Anúncio</label>
                <div class="alert alert-info" style="background: #e0f2fe; color: #0284c7; padding: 12px; border-radius: 8px; font-size: 0.9rem; border-left: 4px solid #0284c7;">
                    A funcionalidade de adicionar ou remover fotos após a publicação será disponibilizada em uma atualização futura. Por enquanto, as fotos originais serão mantidas.
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 16px; align-items: center; margin-top: 30px;">
                <a href="index.php?action=meus_anuncios" style="color: var(--text-secondary); font-weight: 500; font-size: 0.95rem;">Cancelar</a>
                <button type="submit" class="btn-anunciar">Salvar Alterações</button>
            </div>
            
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoriaSelect = document.getElementById('categoriaId');
        const subCategoriaSelect = document.getElementById('subCategoriaId');

        categoriaSelect.addEventListener('change', function() {
            const catId = this.value;
            
            subCategoriaSelect.innerHTML = '<option value="">Carregando...</option>';
            subCategoriaSelect.disabled = true;

            if (catId) {
                fetch(`index.php?action=get_subcategorias&categoria_id=${catId}`)
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
        
        // Se já tiver categoriaId (edição), forçar o carregamento inicial das subcategorias
        if (categoriaSelect.value) {
            const currentSubCatId = "<?= htmlspecialchars($anuncio->getSubCategoriaId()) ?>";
            fetch(`index.php?action=get_subcategorias&categoria_id=${categoriaSelect.value}`)
                .then(response => response.json())
                .then(data => {
                    subCategoriaSelect.innerHTML = '<option value="">Selecione uma sub categoria...</option>';
                    if (data.length > 0) {
                        data.forEach(sub => {
                            const option = document.createElement('option');
                            option.value = sub.id;
                            option.textContent = sub.nome;
                            if (sub.id == currentSubCatId) {
                                option.selected = true;
                            }
                            subCategoriaSelect.appendChild(option);
                        });
                        subCategoriaSelect.disabled = false;
                    }
                });
        }
    });
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
