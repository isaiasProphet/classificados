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
                    <form action="index.php?action=atualizar_perfil" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-4 text-center">
                            <label for="foto_perfil" class="form-label d-block fw-semibold" style="color: #4a4a4a; cursor: pointer;">
                                <?php if ($usuario->getFotoPerfilPath()): ?>
                                    <img src="<?= htmlspecialchars($usuario->getFotoPerfilPath()) ?>" alt="Foto de Perfil" class="rounded-circle mb-2" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid var(--olx-purple);">
                                <?php else: ?>
                                    <div class="rounded-circle mb-2 d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px; background: rgba(0,0,0,0.1); border: 3px solid var(--olx-purple); font-size: 3rem; color: var(--olx-purple);">
                                        <?= mb_substr(htmlspecialchars($usuario->getNome()), 0, 1) ?>
                                    </div>
                                <?php endif; ?>
                                <div class="text-primary mt-1" style="font-size: 0.9rem;">Alterar foto de perfil</div>
                            </label>
                            <input type="file" class="form-control d-none" id="foto_perfil" name="foto_perfil" accept="image/jpeg, image/png, image/webp" onchange="previewImage(event)">
                        </div>

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

<script>
function previewImage(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const label = document.querySelector('label[for="foto_perfil"]');
            const img = label.querySelector('img');
            if (img) {
                img.src = e.target.result;
            } else {
                const div = label.querySelector('div.rounded-circle');
                if (div) {
                    const newImg = document.createElement('img');
                    newImg.src = e.target.result;
                    newImg.alt = "Foto de Perfil";
                    newImg.className = "rounded-circle mb-2";
                    newImg.style.width = "120px";
                    newImg.style.height = "120px";
                    newImg.style.objectFit = "cover";
                    newImg.style.border = "3px solid var(--olx-purple)";
                    div.parentNode.replaceChild(newImg, div);
                }
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
