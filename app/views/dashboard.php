<?php include './app/views/layouts/header.php'; ?>

<h2>Bem-vindo, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h2>

<!-- Grupos Criados -->
<h3 class="mt-4">Grupos Criados por Você</h3>
<?php if (empty($created_groups)): ?>
    <p>Você ainda não criou nenhum grupo.</p>
    <a href="/group/create" class="btn btn-primary">Criar Novo Grupo</a>
<?php else: ?>
    <ul class="list-group mb-3">
        <?php foreach ($created_groups as $group): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= htmlspecialchars($group['name']) ?>
                <div>
                    <a href="/group/<?= $group['id'] ?>/settings" class="btn btn-warning btn-sm">Configurações</a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="/group/create" class="btn btn-primary">Criar Novo Grupo</a>
<?php endif; ?>

<!-- Grupos Participantes -->
<h3 class="mt-4">Grupos em que Você Participa</h3>
    <?php if (empty($participating_groups)): ?>
        <p>Você ainda não está participando de nenhum grupo.</p>
    <?php else: ?>
        <ul class="list-group">
            <?php foreach ($participating_groups as $group): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <?= htmlspecialchars($group['name']) ?>
                        <span class="badge <?= $group['draw_completed'] ? 'bg-success' : 'bg-warning text-dark' ?>">
                            <?= $group['draw_completed'] ? 'Sorteio Realizado' : 'Sorteio Pendente' ?>
                        </span>
                    </div>
                    <div>
                        <a href="/group/<?= $group['id'] ?>/details" class="btn btn-info btn-sm">Ver Detalhes</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

<?php include './app/views/layouts/footer.php'; ?>
