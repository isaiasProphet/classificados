<?php require_once __DIR__ . '/header.php'; ?>


<h2>Listar Bairros</h2>

<div class="card shadow-sm mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <!-- Adicionada a ID "tabela-bairros" para o controle do JavaScript -->
            <table class="table table-striped table-hover align-middle mb-0" id="tabela-bairros">
                <thead>
                    <tr>
                        <th style="width: 10%;">ID</th>
                        <th>Nome</th>
                        <th style="width: 20%;" class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bairros as $b): ?>
                        <!-- Adicionada a classe "linha-bairro" em cada linha -->
                        <tr class="linha-bairro">
                            <td><?= htmlspecialchars($b->getId()) ?></td>
                            <td><?= htmlspecialchars($b->getNome()) ?></td>
                            <td class="text-end">
                                <a href="index.php?action=admin_bairro_edit&id=<?= htmlspecialchars($b->getId()) ?>" class="btn btn-sm btn-primary">Editar</a>
                                <a href="index.php?action=admin_bairro_delete&id=<?= htmlspecialchars($b->getId()) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir este bairro?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Elementos da Paginação (Bootstrap 5) controlados pelo JavaScript -->
<nav class="d-flex justify-content-between align-items-center mb-4">
    <span class="text-muted small" id="info-paginas">Mostrando 0-0 de 0 bairros</span>
    
    <ul class="pagination mb-0" id="paginacao-bairros">
        <!-- Os botões de páginas e setas serão gerados dinamicamente pelo JavaScript -->
    </ul>
</nav>

<!-- Botão Adicionar -->
<a href="index.php?action=admin_bairro_add" class="btn btn-primary">
    Adicionar
</a>

<!-- Script de Paginação -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const itensPorPagina = 5; // Defina quantos bairros quer exibir por página
    const linhas = document.querySelectorAll('.linha-bairro');
    const totalItens = linhas.length;
    const totalPaginas = Math.ceil(totalItens / itensPorPagina);
    const containerPaginacao = document.getElementById('paginacao-bairros');
    let paginaAtual = 1;

    // Se houver poucas linhas para paginar, não exibe a navegação
    if (totalItens <= itensPorPagina) {
        document.getElementById('info-paginas').innerText = `Mostrando 1-${totalItens} de ${totalItens} bairros`;
        return;
    }

    function mostrarPagina(pagina) {
        if (pagina < 1) pagina = 1;
        if (pagina > totalPaginas) pagina = totalPaginas;
        paginaAtual = pagina;

        // Oculta todas as linhas e exibe apenas as da página ativa
        linhas.forEach((linha, index) => {
            const inicio = (pagina - 1) * itensPorPagina;
            const fim = inicio + itensPorPagina;
            
            if (index >= inicio && index < fim) {
                linha.style.display = "";
            } else {
                linha.style.display = "none";
            }
        });

        // Atualiza o texto informativo no rodapé
        const de = (pagina - 1) * itensPorPagina + 1;
        const ate = Math.min(pagina * itensPorPagina, totalItens);
        document.getElementById('info-paginas').innerText = `Mostrando ${de}-${ate} de ${totalItens} bairros`;

        // Renderiza novamente os botões numéricos para aplicar a classe 'active' correta
        renderizarBotoes();
    }

    function renderizarBotoes() {
        containerPaginacao.innerHTML = '';

        // Botão Anterior
        const btnAnterior = document.createElement('li');
        btnAnterior.className = `page-item ${paginaAtual === 1 ? 'disabled' : ''}`;
        btnAnterior.innerHTML = `<a class="page-link" href="#" aria-label="Anterior">&laquo;</a>`;
        btnAnterior.addEventListener('click', (e) => {
            e.preventDefault();
            if (paginaAtual > 1) mostrarPagina(paginaAtual - 1);
        });
        containerPaginacao.appendChild(btnAnterior);

        // Botões Numéricos
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

        // Botão Próximo
        const btnProximo = document.createElement('li');
        btnProximo.className = `page-item ${paginaAtual === totalPaginas ? 'disabled' : ''}`;
        btnProximo.innerHTML = `<a class="page-link" href="#" aria-label="Próximo">&raquo;</a>`;
        btnProximo.addEventListener('click', (e) => {
            e.preventDefault();
            if (paginaAtual < totalPaginas) mostrarPagina(paginaAtual + 1);
        });
        containerPaginacao.appendChild(btnProximo);
    }

    // Inicializa exibindo a primeira página
    mostrarPagina(1);
});
</script>






<?php require_once __DIR__ . '/footer.php'; ?>
