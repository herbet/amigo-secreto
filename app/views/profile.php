<?php include './app/views/layouts/header.php'; ?>

<h2>Meu Perfil</h2>

<!-- Exibir mensagem de sucesso, se existir -->
<?php if (!empty($_SESSION['success_message'])): ?>
    <div class="alert alert-success" role="alert">
        <?= htmlspecialchars($_SESSION['success_message']) ?>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<form action="/profile" method="POST">
    <!-- Nome -->
    <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input 
            type="text" 
            id="name" 
            name="name" 
            class="form-control" 
            value="<?= htmlspecialchars($user['name']) ?>" 
            required>
    </div>

    <!-- E-mail -->
    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input 
            type="email" 
            id="email" 
            name="email" 
            class="form-control" 
            value="<?= htmlspecialchars($user['email']) ?>" 
            required>
    </div>

    <!-- Nova senha -->
    <div class="mb-3">
        <label for="password" class="form-label">Nova Senha (opcional)</label>
        <input 
            type="password" 
            id="password" 
            name="password" 
            class="form-control">
    </div>

    <!-- Confirmar nova senha -->
    <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirmar Nova Senha</label>
        <input 
            type="password" 
            id="confirm_password" 
            name="confirm_password" 
            class="form-control">
    </div>

    <!-- Botão de atualizar -->
    <button type="submit" class="btn btn-primary">Atualizar Perfil</button>
</form>

<!-- Botão para voltar ao dashboard -->
<a href="/dashboard" class="btn btn-secondary mt-3">Voltar ao Dashboard</a>

<?php include './app/views/layouts/footer.php'; ?>
