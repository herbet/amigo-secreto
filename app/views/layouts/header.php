<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Amigo Secreto' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/">Amigo Secreto</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/profile">Meu Perfil</a>
                    </li>
                    <li class="nav-item">
                        <form action="/logout" method="POST" class="d-inline">
                            <button type="submit" class="nav-link btn btn-link text-white" style="text-decoration: none;">Sair</button>
                        </form>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Entrar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container mt-4">
