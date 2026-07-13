<?php require_once __DIR__ . '/header.php'; ?>


<h2>Listar Igrejas</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Pastor Presidente</th>
            <th>Bairro</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($igrejas as $igreja): ?>
            <tr>
                <td><?= htmlspecialchars($igreja->getId()) ?></td>
                <td><?= htmlspecialchars($igreja->getNome()) ?></td>
                <td><?= htmlspecialchars($igreja->getPastorPresidente()) ?></td>
                <td><?= htmlspecialchars($igreja->getBairroNome() ?? 'Sem Bairro') ?></td>
                <td>
                    <a href="index.php?action=admin_igreja_edit&id=<?= htmlspecialchars($igreja->getId()) ?>" class="btn btn-primary">Editar</a>
                    <a href="index.php?action=admin_igreja_delete&id=<?= htmlspecialchars($igreja->getId()) ?>" class="btn btn-danger">Excluir</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/footer.php'; ?>
