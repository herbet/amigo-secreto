<?php include './app/views/layouts/header.php'; ?>

<div class="container text-center mt-5">
    <h1>Bem-vindo ao Sistema de Amigo Secreto!</h1>
    <p class="lead mt-3">Gerencie seus grupos de amigo secreto, adicione participantes, e realize sorteios com facilidade.</p>

    <div class="mt-4">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/dashboard" class="btn btn-primary btn-lg">Ir para o Dashboard</a>
        <?php else: ?>
            <a href="/login" class="btn btn-primary btn-lg">Login</a>
            <a href="/register" class="btn btn-secondary btn-lg">Registrar</a>
        <?php endif; ?>
    </div>
</div>

<?php include './app/views/layouts/footer.php'; ?>
