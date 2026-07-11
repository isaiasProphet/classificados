<?php require_once __DIR__ . '/../layout/header.php'; ?>

<section class="auth-section" style="max-width: 420px; margin: 50px auto; padding: 0 20px;">
    <h2 style="text-align: center; margin-bottom: 24px;">Crie sua conta</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert-error">
            <?php 
                if ($_GET['error'] === 'email_exists') {
                    echo "Este e-mail já está em uso.";
                } else {
                    echo "Ocorreu um erro ao criar a conta.";
                }
            ?>
        </div>
    <?php endif; ?>

    <div class="form-card animate-fade-in">
        <form action="index.php?action=store_user" method="POST">
            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" class="form-control" required placeholder="Seu nome">
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="seu@email.com">
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" class="form-control" required placeholder="••••••••" minlength="6">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; margin-top: 10px; padding: 12px;">Cadastrar</button>
            
            <div style="text-align: center; margin-top: 20px; font-size: 0.9rem;">
                Já tem uma conta? <a href="index.php?action=login" style="color: var(--olx-purple); font-weight: 600;">Faça login</a>
            </div>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
