<?php include './app/views/layouts/header.php'; ?>

<div class="container mt-5">
    <h2>Configurações do Grupo: <?= htmlspecialchars($group['name']) ?></h2>
    <p>Status do sorteio: <strong><?= $group['draw_completed'] ? 'Sorteio realizado' : 'Aguardando sorteio' ?></strong></p>

    <!-- Participantes do grupo -->
    <h3>Participantes</h3>
    <ul class="list-group mb-3">
        <?php foreach ($participants as $participant): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= htmlspecialchars($participant['name']) ?> (<?= htmlspecialchars($participant['email']) ?>)

                <!-- Mostrar os botões de edição e exclusão apenas se o sorteio ainda não foi realizado -->
                <?php if (!$group['draw_completed']): ?>
                    <div>
                        <!-- Botão de editar -->
                        <button class="btn btn-warning btn-sm me-1" onclick="showEditForm(<?= htmlspecialchars(json_encode($participant)) ?>)">Editar</button>

                        <!-- Botão de excluir -->
                        <form action="/participant/delete" method="POST" style="display: inline;">
                            <input type="hidden" name="participant_id" value="<?= $participant['id'] ?>">
                            <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Mostrar o formulário de edição de participante apenas se o sorteio ainda não foi realizado -->
    <?php if (!$group['draw_completed']): ?>
        <!-- Formulário de Edição de Participante -->
        <div id="edit-form" style="display: none;" class="mb-3">
            <h3>Editar Participante</h3>
            <form action="/participant/edit" method="POST">
                <input type="hidden" name="participant_id" id="edit-id">
                <input type="hidden" name="group_id" value="<?= $group['id'] ?>">

                <!-- Nome -->
                <div class="mb-3">
                    <label for="edit-name" class="form-label">Nome</label>
                    <input type="text" id="edit-name" name="name" class="form-control" required>
                </div>

                <!-- E-mail -->
                <div class="mb-3">
                    <label for="edit-email" class="form-label">E-mail</label>
                    <input type="email" id="edit-email" name="email" class="form-control" required>
                </div>

                <!-- Botões -->
                <button type="submit" class="btn btn-success">Salvar Alterações</button>
                <button type="button" class="btn btn-secondary" onclick="hideEditForm()">Cancelar</button>
            </form>
        </div>
    <?php endif; ?>

    <!-- Mostrar o formulário de adicionar participante apenas se o sorteio ainda não foi realizado -->
    <?php if (!$group['draw_completed']): ?>
        <h3>Adicionar Participante</h3>
        <form action="/group/addMember" method="POST" class="mb-3">
            <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar</button>
        </form>
    <?php endif; ?>

    <!-- Botão para realizar o sorteio -->
    <?php if (!$group['draw_completed']): ?>
        <form action="/group/draw" method="POST">
            <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
            <button type="submit" class="btn btn-success">Realizar Sorteio</button>
        </form>
    <?php else: ?>
        <p class="text-success"><strong>O sorteio já foi realizado. Nenhuma alteração pode ser feita.</strong></p>
    <?php endif; ?>

    <!-- Botão para excluir o grupo (apenas se o sorteio não foi realizado) -->
    <?php if (!$group['draw_completed']): ?>
        <form action="/group/delete" method="POST" class="mt-4">
            <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
            <button type="submit" class="btn btn-danger">Excluir Grupo</button>
        </form>
    <?php endif; ?>    

    <!-- Botão de voltar ao dashboard -->
    <a href="/dashboard" class="btn btn-secondary mt-3">Voltar ao Dashboard</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Exibir o formulário de edição com os dados do participante
    function showEditForm(participant) {
        document.getElementById('edit-id').value = participant.id;
        document.getElementById('edit-name').value = participant.name;
        document.getElementById('edit-email').value = participant.email;
        document.getElementById('edit-form').style.display = 'block';
        window.scrollTo(0, document.getElementById('edit-form').offsetTop);
    }

    // Ocultar o formulário de edição
    function hideEditForm() {
        document.getElementById('edit-form').style.display = 'none';
    }
</script>

<?php include './app/views/layouts/footer.php'; ?>
