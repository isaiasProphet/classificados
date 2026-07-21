<?php require_once __DIR__ . '/header.php'; ?>


<h2>Listar Igrejas</h2>

<div class="card shadow-sm mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <!-- Adicionada a ID "tabela-igrejas" para o controle do JavaScript -->
            <table class="table table-striped table-hover align-middle mb-0" id="tabela-igrejas">
                <thead>
                    <tr>
                        <th style="width: 10%;">ID</th>
                        <th>Nome</th>
                        <th>Pastor Presidente</th>
                        <th>Bairro</th>
                        <th style="width: 20%;" class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($igrejas as $igreja): ?>
                        <!-- Adicionada a classe "linha-igreja" em cada linha -->
                        <tr class="linha-igreja">
                            <td><?= htmlspecialchars($igreja->getId()) ?></td>
                            <td><?= htmlspecialchars($igreja->getNome()) ?></td>
                            <td><?= htmlspecialchars($igreja->getPastorPresidente()) ?></td>
                            <td><?= htmlspecialchars($igreja->getBairroNome() ?? 'Sem Bairro') ?></td>
                            <td class="text-end">
                                <a href="index.php?action=admin_igreja_edit&id=<?= htmlspecialchars($igreja->getId()) ?>" class="btn btn-sm btn-primary">Editar</a>
                                <a href="index.php?action=admin_igreja_delete&id=<?= htmlspecialchars($igreja->getId()) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir esta igreja?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Elementos da Paginação (Bootstrap 5) -->
<nav class="d-flex justify-content-between align-items-center mb-4">
    <span class="text-muted small" id="info-paginas">Mostrando 0-0 de 0 igrejas</span>
    
    <ul class="pagination mb-0" id="paginacao-igrejas">
        <!-- Os botões numéricos e as setas serão injetados aqui pelo JavaScript -->
    </ul>
</nav>

<!-- Botão Adicionar corrigido para HTML válido (removido o button de dentro do link) -->
<a href="index.php?action=admin_igreja_add" class="btn btn-primary">
    Adicionar Igreja
</a>

<!-- Script de Paginação -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const itensPorPagina = 5; // Defina a quantidade de igrejas por página aqui
    const linhas = document.querySelectorAll('.linha-igreja');
    const totalItens = linhas.length;
    const totalPaginas = Math.ceil(totalItens / itensPorPagina);
    const containerPaginacao = document.getElementById('paginacao-igrejas');
    let paginaAtual = 1;

    // Se houver poucas linhas, apenas mostra a info básica e não gera os botões
    if (totalItens <= itensPorPagina) {
        document.getElementById('info-paginas').innerText = `Mostrando 1-${totalItens} de ${totalItens} igrejas`;
        return;
    }

    function mostrarPagina(pagina) {
        if (pagina < 1) pagina = 1;
        if (pagina > totalPaginas) pagina = totalPaginas;
        paginaAtual = pagina;

        // Oculta todas as linhas e exibe apenas as do intervalo da página ativa
        linhas.forEach((linha, index) => {
            const inicio = (pagina - 1) * itensPorPagina;
            const fim = inicio + itensPorPagina;
            
            if (index >= inicio && index < fim) {
                linha.style.display = "";
            } else {
                linha.style.display = "none";
            }
        });

        // Atualiza a legenda informativa de rodapé
        const de = (pagina - 1) * itensPorPagina + 1;
        const ate = Math.min(pagina * itensPorPagina, totalItens);
        document.getElementById('info-paginas').innerText = `Mostrando ${de}-${ate} de ${totalItens} igrejas`;

        // Reconstrói os botões para aplicar o estado ativo corretamente
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

    // Inicializa na página 1
    mostrarPagina(1);
});
</script>




<?php require_once __DIR__ . '/footer.php'; ?>
