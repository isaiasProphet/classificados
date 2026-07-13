<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container my-5" style="min-height: 60vh;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4">Meu Perfil</h2>
            
            <?php if (isset($_GET['success']) && $_GET['success'] === 'updated'): ?>
                <div class="alert alert-success">Perfil atualizado com sucesso!</div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <?php if ($_GET['error'] === 'email_exists'): ?>
                    <div class="alert alert-danger">Este email já está em uso por outra conta.</div>
                <?php elseif ($_GET['error'] === 'update_failed'): ?>
                    <div class="alert alert-danger">Erro ao atualizar perfil. Tente novamente mais tarde.</div>
                <?php elseif ($_GET['error'] === 'invalid_current_password'): ?>
                    <div class="alert alert-danger">A senha atual informada está incorreta.</div>
                <?php elseif ($_GET['error'] === 'weak_password'): ?>
                    <div class="alert alert-danger">A nova senha deve ter pelo menos 8 caracteres, incluindo letras maiúsculas, minúsculas, números e caracteres especiais.</div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="card shadow-sm border-0" style="border-radius: 12px; background: #fff;">
                <div class="card-body p-4">
                    <form action="index.php?action=atualizar_perfil" method="POST">
                        <div class="mb-3">
                            <label for="nome" class="form-label fw-semibold" style="color: #4a4a4a;">Nome Completo</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($usuario->getNome()) ?>" required style="border-radius: 8px;">
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold" style="color: #4a4a4a;">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario->getEmail()) ?>" required style="border-radius: 8px;">
                        </div>

                        <div class="mb-3">
                            <label for="telefone" class="form-label fw-semibold" style="color: #4a4a4a;">Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone" value="<?= htmlspecialchars($usuario->getTelefone()) ?>" style="border-radius: 8px;">
                        </div>
                        
                        <div class="mb-3">
                            <label for="igreja_id" class="form-label fw-semibold" style="color: #4a4a4a;">Igreja</label>
                            <select class="form-control" id="igreja_id" name="igreja_id" style="border-radius: 8px;">
                                <option value="0">Selecione a sua igreja</option>
                                <?php if (!empty($igrejas)): ?>
                                    <?php foreach ($igrejas as $igreja): ?>
                                        <option value="<?= $igreja->getId() ?>" <?= $usuario->getIgrejaId() == $igreja->getId() ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($igreja->getNome()) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="cargo_igreja" class="form-label fw-semibold" style="color: #4a4a4a;">Cargo na Igreja (Opcional)</label>
                            <input type="text" class="form-control" id="cargo_igreja" name="cargo_igreja" value="<?= htmlspecialchars($usuario->getCargoIgreja()) ?>" style="border-radius: 8px;">
                        </div>

                        <div class="mb-3">
                            <label for="sobre_mim" class="form-label fw-semibold" style="color: #4a4a4a;">Sobre Mim (Opcional)</label>
                            <textarea class="form-control" id="sobre_mim" name="sobre_mim" rows="3" style="border-radius: 8px;"><?= htmlspecialchars($usuario->getSobreMim()) ?></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="senha_atual" class="form-label fw-semibold" style="color: #4a4a4a;">Senha Atual <span class="text-muted fw-normal" style="font-size: 0.85em;">(obrigatória se for alterar a senha)</span></label>
                            <input type="password" class="form-control" id="senha_atual" name="senha_atual" style="border-radius: 8px;">
                        </div>

                        <div class="mb-4">
                            <label for="senha" class="form-label fw-semibold" style="color: #4a4a4a;">Nova Senha <span class="text-muted fw-normal" style="font-size: 0.85em;">(deixe em branco para não alterar)</span></label>
                            <input type="password" class="form-control" id="senha" name="senha" minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" title="A senha deve ter pelo menos 8 caracteres, incluir uma letra maiúscula, uma minúscula, um número e um caractere especial." style="border-radius: 8px;">
                            <small class="text-muted" style="font-size: 0.8rem;">Mín. 8 caracteres, com maiúsculas, minúsculas, números e símbolos.</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" style="border-radius: 8px; font-weight: 500; background-color: #3b82f6; border: none;">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
