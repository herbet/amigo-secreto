<?php include './app/views/layouts/header.php'; ?>

<h2>Registrar Novo Usuário</h2>

<form action="/register" method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" id="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Senha</label>
        <input type="password" id="password" name="password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirmar Senha</label>
        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-primary">Registrar</button>
</form>

<a href="/login" class="btn btn-secondary mt-3">Já tem uma conta? Entrar</a>
</div>


<?php include './app/views/layouts/footer.php'; ?>
