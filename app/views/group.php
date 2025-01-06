<?php include './app/views/layouts/header.php'; ?>

<h2>Detalhes do Grupo: <?= htmlspecialchars($group['name']) ?></h2>

<!-- Mostrar informações gerais do grupo -->
<p><strong>Criado por:</strong> <?= htmlspecialchars($group['created_by_name']) ?></p>
<p><strong>Status do sorteio:</strong> <?= $group['draw_completed'] ? 'Sorteio realizado' : 'Aguardando sorteio' ?></p>

<!-- Mostrar "Seu Amigo Secreto" apenas para participantes -->
<?php if ($is_participant): ?>
    <h3>Seu Amigo Secreto</h3>
    <?php if ($group['draw_completed']): ?>
        <p id="friend-name">----</p>
        <button id="toggle-friend" class="btn btn-primary">
            <i id="eye-icon" class="fa fa-eye"></i> Mostrar
        </button>
        <script>
            document.getElementById('toggle-friend').addEventListener('click', function () {
                const friendName = document.getElementById('friend-name');
                const icon = document.getElementById('eye-icon');
                
                if (friendName.textContent === '----') {
                    friendName.textContent = "<?= htmlspecialchars($secret_friend['name']) ?>";
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    friendName.textContent = '----';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        </script>
    <?php else: ?>
        <p>O sorteio ainda não foi realizado.</p>
    <?php endif; ?>
<?php endif; ?>

<h3 class="mt-4">Participantes</h3>
<ul class="list-group mt-3">
    <?php foreach ($participants as $participant): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>
                <?= htmlspecialchars($participant['name']) ?> (<?= htmlspecialchars($participant['email']) ?>)
            </span>
            <?php if ($is_creator && !$group['draw_completed']): ?>
                <div class="d-flex">
                    <!-- Botão Editar -->
                    <button class="btn btn-warning btn-sm me-2" onclick="toggleEditForm('editForm-<?= $participant['participant_id'] ?>')">Editar</button>
                    <!-- Botão Excluir -->
                    <form action="/participant/delete" method="POST" class="d-inline">
                        <input type="hidden" name="participant_id" value="<?= $participant['participant_id'] ?>">
                        <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                    </form>
                </div>
            <?php endif; ?>
        </li>

        <!-- Formulário de edição escondido -->
        <?php if (!$group['draw_completed']): ?>
            <div id="editForm-<?= $participant['participant_id'] ?>" class="mt-3" style="display: none;">
                <form action="/participant/edit" method="POST" class="d-flex flex-column">
                    <input type="hidden" name="participant_id" value="<?= $participant['participant_id'] ?>">
                    <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
                    <div class="form-group">
                        <label for="name-<?= $participant['participant_id'] ?>">Nome</label>
                        <input type="text" class="form-control" id="name-<?= $participant['participant_id'] ?>" name="name" value="<?= htmlspecialchars($participant['name']) ?>" required>
                    </div>
                    <div class="form-group mt-2">
                        <label for="email-<?= $participant['participant_id'] ?>">E-mail</label>
                        <input type="email" class="form-control" id="email-<?= $participant['participant_id'] ?>" name="email" value="<?= htmlspecialchars($participant['email']) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Salvar Alterações</button>
                </form>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>

<?php if ($is_creator): ?>
    <?php if (!$group['draw_completed']): ?>
    <h3 class="mt-4">Adicionar Participantes</h3>
    <form method="POST" action="/group/addParticipant">
        <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Nome</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Adicionar</button>
    </form>
    <?php endif; ?>    

    <div class="mt-4">
        <?php if (!$group['draw_completed']): ?>
            <form action="/group/sorteio" method="POST">
                <input type="hidden" name="group_id" value="<?= $group['id'] ?>">
                <button type="submit" class="btn btn-success">Realizar Sorteio</button>
            </form>
        <?php else: ?>
            <p class="text-success">O sorteio já foi realizado!</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<script>
    function toggleEditForm(formId) {
        const form = document.getElementById(formId);
        if (form.style.display === "none") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    }    
</script>

<?php include './app/views/layouts/footer.php'; ?>
