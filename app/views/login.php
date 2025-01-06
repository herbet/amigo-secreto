<?php include './app/views/layouts/header.php'; ?>

<h2>Login</h2>
<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>
<form method="POST" action="/login">
    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Senha</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Entrar</button>
</form>

<?php include './app/views/layouts/footer.php'; ?>
