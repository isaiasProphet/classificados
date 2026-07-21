<?php require_once __DIR__ . '/header.php'; ?>

<div class="row justify-content-center my-4">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0" style="border-radius: 12px; background: #fff;">
            <div class="card-body p-4">
                <h2 class="mb-4 text-primary" style="font-weight: 700;">Incluir Bairro</h2>
                
                <form action="index.php?action=admin_bairro_store" method="POST">
                    <div class="mb-3">
                        <label for="nome" class="form-label fw-semibold" style="color: #4a4a4a;">Nome do Bairro</label>
                        <input type="text" class="form-control" id="nome" name="nome" required style="border-radius: 8px;" placeholder="Ex: Centro">
                    </div>
                    
                    <div class="mb-3">
                        <label for="cidadeId" class="form-label fw-semibold" style="color: #4a4a4a;">Cidade</label>
                        <input type="text" id="cidade-search" class="form-control mb-2" placeholder="🔍 Digite para filtrar as cidades..." style="border-radius: 8px;">
                        
                        <select class="form-select" id="cidadeId" name="cidadeId" required size="8" style="border-radius: 8px; font-size: 0.95rem;">
                            <option value="">Selecione uma cidade...</option>
                            <?php foreach ($cidades as $cidade): ?>
                                <option value="<?= htmlspecialchars($cidade['id']) ?>">
                                    <?= htmlspecialchars($cidade['nome']) ?> - <?= htmlspecialchars($cidade['uf']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text text-muted">A lista acima mostra todas as cidades do Brasil. Use a barra de pesquisa para encontrar a sua rapidamente.</div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="index.php?action=admin_bairros_list" class="btn btn-light me-md-2" style="border-radius: 8px; font-weight: 500;">Cancelar</a>
                        <button type="submit" class="btn btn-primary" style="border-radius: 8px; font-weight: 500; background-color: var(--olx-purple); border: none; padding: 10px 24px;">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('cidade-search');
    const selectElement = document.getElementById('cidadeId');
    const originalOptions = Array.from(selectElement.options);

    searchInput.addEventListener('input', function() {
        const query = searchInput.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
        
        // Limpar o select
        selectElement.innerHTML = '';
        
        // Filtrar e preencher
        const filtered = originalOptions.filter(option => {
            if (option.value === "") return true; // Mantém a opção padrão/placeholder
            const text = option.textContent.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            return text.includes(query);
        });
        
        filtered.forEach(option => selectElement.appendChild(option));
    });
});
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
