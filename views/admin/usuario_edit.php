<?php require_once __DIR__ . '/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Editar Usuário</h2>
    <a href="index.php?action=admin_usuarios_list" class="btn btn-outline-secondary">Voltar</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="index.php?action=admin_usuario_update" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($usuarioEditar->getId()) ?>">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nome" class="form-label">Nome Completo</label>
                    <input type="text" disabled class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($usuarioEditar->getNome()) ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" disabled class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuarioEditar->getEmail()) ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="telefone" class="form-label">Telefone</label>
                    <input type="text" disabled class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($usuarioEditar->getTelefone()) ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label for="permissoes" class="form-label">Permissão</label>
                    <select class="form-select" id="permissoes" name="permissoes" required>
                        <option value="cliente" <?= $usuarioEditar->getPermissoes()->value === 'cliente' ? 'selected' : '' ?>>Cliente</option>
                        <option value="anunciante" <?= $usuarioEditar->getPermissoes()->value === 'anunciante' ? 'selected' : '' ?>>Anunciante</option>
                        <option value="moderador" <?= $usuarioEditar->getPermissoes()->value === 'moderador' ? 'selected' : '' ?>>Moderador</option>
                        <option value="admin" <?= $usuarioEditar->getPermissoes()->value === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="igreja_id" class="form-label">Igreja (Opcional)</label>
                    <select class="form-select" id="igreja_id" name="igreja_id">
                        <option value="">Nenhuma / Outra</option>
                        <?php foreach ($igrejas as $igreja): ?>
                            <option value="<?= htmlspecialchars($igreja->getId()) ?>" <?= $usuarioEditar->getIgrejaId() == $igreja->getId() ? 'selected' : '' ?>>
                                <?= htmlspecialchars($igreja->getNome()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="senha" class="form-label">Nova Senha (deixe em branco para não alterar)</label>
                    <input type="password" class="form-control" id="senha" name="senha">
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <button type="submit" class="btn btn-primary px-4">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>
