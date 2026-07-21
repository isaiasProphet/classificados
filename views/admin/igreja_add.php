<?php require_once __DIR__ . '/header.php'; ?>

<h2>Incluir Igreja</h2>
<form action="index.php?action=admin_igreja_store" method="POST">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome da Igreja</label>
        <input type="text" class="form-control" id="nome" name="nome" required>
    </div>
    <div class="mb-3">
        <label for="pastorPresidente" class="form-label">Pastor Presidente</label>
        <input type="text" class="form-control" id="pastorPresidente" name="pastorPresidente">
    </div>
    <div class="mb-3">
        <label for="bairro_id" class="form-label">Bairro</label>
        <select class="form-select" id="bairro_id" name="bairro_id" required>
            <option value="">Selecione um bairro</option>
            <?php foreach ($bairros as $bairro): ?>
                <option value="<?= htmlspecialchars($bairro->getId()) ?>"><?= htmlspecialchars($bairro->getNome()) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Salvar</button>
</form>

<?php require_once __DIR__ . '/footer.php'; ?>
