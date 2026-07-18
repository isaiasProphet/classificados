<?php require_once __DIR__ . '/header.php'; ?>

<h2>Listar Anúncios</h2>

<div class="card shadow-sm mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0" id="tabela-anuncios">
                <thead>
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th>Título</th>
                        <th>Preço</th>
                        <th>Status</th>
                        <th>Visualizações</th>
                        <th>Data Criação</th>
                        <th style="width: 15%;" class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($anuncios as $anuncio): ?>
                        <tr class="linha-anuncio">
                            <td><?= htmlspecialchars($anuncio->getId()) ?></td>
                            <td><?= htmlspecialchars($anuncio->getTitulo()) ?></td>
                            <td>R$ <?= number_format($anuncio->getPreco(), 2, ',', '.') ?></td>
                            <td>
                                <?php if ($anuncio->getStatus()->value === 'ativo'): ?>
                                    <span class="badge bg-success">Ativo</span>
                                <?php elseif ($anuncio->getStatus()->value === 'pendente'): ?>
                                    <span class="badge bg-warning text-dark">Pendente</span>
                                <?php elseif ($anuncio->getStatus()->value === 'vendido'): ?>
                                    <span class="badge bg-info">Vendido</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= htmlspecialchars(ucfirst($anuncio->getStatus()->value)) ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($anuncio->getVisualizacoes()) ?></td>
                            <td><?= htmlspecialchars($anuncio->getDataCriacao()->format('d/m/Y H:i')) ?></td>
                            <td class="text-end">
                                <a href="index.php?action=show&id=<?= htmlspecialchars($anuncio->getId()) ?>" class="btn btn-sm btn-info text-white" target="_blank">Ver</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<nav class="d-flex justify-content-between align-items-center mb-4">
    <span class="text-muted small" id="info-paginas">Mostrando 0-0 de 0 anúncios</span>
    
    <ul class="pagination mb-0" id="paginacao-anuncios">
    </ul>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const itensPorPagina = 5;
    const linhas = document.querySelectorAll('.linha-anuncio');
    const totalItens = linhas.length;
    const totalPaginas = Math.ceil(totalItens / itensPorPagina);
    const containerPaginacao = document.getElementById('paginacao-anuncios');
    let paginaAtual = 1;

    if (totalItens <= itensPorPagina) {
        document.getElementById('info-paginas').innerText = `Mostrando 1-${totalItens} de ${totalItens} anúncios`;
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
        document.getElementById('info-paginas').innerText = `Mostrando ${de}-${ate} de ${totalItens} anúncios`;

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
