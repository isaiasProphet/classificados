<?php require_once __DIR__ . '/header.php'; ?>

<h2>Listar Usuários</h2>

<div class="card shadow-sm mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0" id="tabela-usuarios">
                <thead>
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Permissão</th>
                        <th>Status</th>
                        <th>Data Cadastro</th>
                        <th style="width: 15%;" class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr class="linha-usuario">
                            <td><?= htmlspecialchars($usuario->getId()) ?></td>
                            <td><?= htmlspecialchars($usuario->getNome()) ?></td>
                            <td><?= htmlspecialchars($usuario->getEmail()) ?></td>
                            <td><?= htmlspecialchars($usuario->getTelefone()) ?></td>
                            <td>
                                <span class="badge bg-<?= $usuario->getPermissoes()->value === 'admin' ? 'danger' : ($usuario->getPermissoes()->value === 'moderador' ? 'warning' : 'primary') ?>">
                                    <?= htmlspecialchars(ucfirst($usuario->getPermissoes()->value)) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($usuario->getAtivo()): ?>
                                    <span class="badge bg-success">Ativo</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($usuario->getDataCadastro()->format('d/m/Y H:i')) ?></td>
                            <td class="text-end">
                                <a href="index.php?action=admin_usuario_edit&id=<?= htmlspecialchars($usuario->getId()) ?>" class="btn btn-sm btn-primary">Editar</a>
                                <a href="index.php?action=admin_usuario_delete&id=<?= htmlspecialchars($usuario->getId()) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir este usuário?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<nav class="d-flex justify-content-between align-items-center mb-4">
    <span class="text-muted small" id="info-paginas">Mostrando 0-0 de 0 usuários</span>
    
    <ul class="pagination mb-0" id="paginacao-usuarios">
    </ul>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const itensPorPagina = 10;
    const linhas = document.querySelectorAll('.linha-usuario');
    const totalItens = linhas.length;
    const totalPaginas = Math.ceil(totalItens / itensPorPagina);
    const containerPaginacao = document.getElementById('paginacao-usuarios');
    let paginaAtual = 1;

    if (totalItens <= itensPorPagina) {
        document.getElementById('info-paginas').innerText = `Mostrando 1-${totalItens} de ${totalItens} usuários`;
        return;
    }

    function mostrarPagina(pagina) {
        if (pagina < 1) pagina = 1;
        if (pagina > totalPaginas) pagina = totalPaginas;
        paginaAtual = pagina;

        linhas.forEach((linha, index) => {
            const inicio = (pagina - 1) * itensPorPagina;
            const fim = inicio + itensPorPagina;
            
            if (index >= inicio && index < fim) {
                linha.style.display = "";
            } else {
                linha.style.display = "none";
            }
        });

        const de = (pagina - 1) * itensPorPagina + 1;
        const ate = Math.min(pagina * itensPorPagina, totalItens);
        document.getElementById('info-paginas').innerText = `Mostrando ${de}-${ate} de ${totalItens} usuários`;

        renderizarBotoes();
    }

    function renderizarBotoes() {
        containerPaginacao.innerHTML = '';

        const btnAnterior = document.createElement('li');
        btnAnterior.className = `page-item ${paginaAtual === 1 ? 'disabled' : ''}`;
        btnAnterior.innerHTML = `<a class="page-link" href="#" aria-label="Anterior">&laquo;</a>`;
        btnAnterior.addEventListener('click', (e) => {
            e.preventDefault();
            if (paginaAtual > 1) mostrarPagina(paginaAtual - 1);
        });
        containerPaginacao.appendChild(btnAnterior);

        for (let i = 1; i <= totalPaginas; i++) {
            const btnNumero = document.createElement('li');
            btnNumero.className = `page-item ${paginaAtual === i ? 'active' : ''}`;
            btnNumero.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            btnNumero.addEventListener('click', (e) => {
                e.preventDefault();
                mostrarPagina(i);
            });
            containerPaginacao.appendChild(btnNumero);
        }

        const btnProximo = document.createElement('li');
        btnProximo.className = `page-item ${paginaAtual === totalPaginas ? 'disabled' : ''}`;
        btnProximo.innerHTML = `<a class="page-link" href="#" aria-label="Próximo">&raquo;</a>`;
        btnProximo.addEventListener('click', (e) => {
            e.preventDefault();
            if (paginaAtual < totalPaginas) mostrarPagina(paginaAtual + 1);
        });
        containerPaginacao.appendChild(btnProximo);
    }

    mostrarPagina(1);
});
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
