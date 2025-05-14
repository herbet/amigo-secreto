<?php include './app/views/layouts/header.php'; ?>

<h2>Criar Novo Grupo</h2>

<form action="/group/add" method="POST">
    <!-- Nome do Grupo -->
    <div class="mb-3">
        <label for="name" class="form-label">Nome do Grupo</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>

    <!-- Botão para criar o grupo -->
    <button type="submit" class="btn btn-primary">Criar Grupo</button>
</form>

<!-- Botão para voltar ao dashboard -->
<a href="/dashboard" class="btn btn-secondary mt-3">Voltar ao Dashboard</a>

<?php include './app/views/layouts/footer.php'; ?>
