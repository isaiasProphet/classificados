<?php require_once __DIR__ . '/../layout/header.php'; ?>

<section class="auth-section" style="max-width: 420px; margin: 50px auto; padding: 0 20px;">
    <h2 style="text-align: center; margin-bottom: 24px;">Acesse sua conta</h2>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert-error">
            <?php 
                if ($_GET['error'] === 'invalid_credentials') {
                    echo "E-mail ou senha incorretos.";
                } elseif ($_GET['error'] === 'not_logged_in') {
                    echo "Você precisa estar logado para acessar esta página.";
                } else {
                    echo "Ocorreu um erro.";
                }
            ?>
        </div>
    <?php endif; ?>

    <div class="form-card animate-fade-in">
        <form action="index.php?action=authenticate" method="POST">
            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="seu@email.com">
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" class="form-control" required placeholder="••••••••">
            </div>

            <button type="submit" class="btn-primary" style="width: 100%; margin-top: 10px; padding: 12px;">Entrar</button>
            
            <div style="text-align: center; margin-top: 20px; font-size: 0.9rem;">
                Não tem uma conta? <a href="index.php?action=register" style="color: var(--olx-purple); font-weight: 600;">Cadastre-se</a>
            </div>
        </form>
    </div>
</section>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
