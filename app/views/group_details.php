<?php include './app/views/layouts/header.php'; ?>

<h2>Detalhes do Grupo: <?= htmlspecialchars($group['name']) ?></h2>
    <p>Status do sorteio: <strong><?= $group['draw_completed'] ? 'Sorteio realizado' : 'Aguardando sorteio' ?></strong></p>

    <!-- Se o sorteio foi realizado, mostrar o amigo secreto -->
    <?php if ($group['draw_completed']): ?>
        <h3>Seu Amigo Secreto</h3>
        <p id="friend-name">----</p>
        <button id="toggle-friend" class="btn btn-primary">
            <i id="eye-icon" class="fa fa-eye"></i> Mostrar
        </button>
        <script>
            document.getElementById('toggle-friend').addEventListener('click', function () {
                const friendName = document.getElementById('friend-name');
                const icon = document.getElementById('eye-icon');
                
                if (friendName.textContent === '----') {
                    friendName.textContent = "<?= htmlspecialchars($secret_friend['name'] ?? 'Não disponível') ?>";
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
        <p class="text-danger">O sorteio ainda não foi realizado.</p>
    <?php endif; ?>

    <!-- Lista de participantes -->
    <h3 class="mt-4">Participantes</h3>
    <ul class="list-group">
        <?php foreach (Group::getMembers($group['id']) as $participant): ?>
            <li class="list-group-item">
            <?= htmlspecialchars($participant['name']) ?> (<?= htmlspecialchars($participant['email']) ?>)
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Voltar ao dashboard -->
    <a href="/dashboard" class="btn btn-secondary mt-4">Voltar ao Dashboard</a>

<?php include './app/views/layouts/footer.php'; ?>
