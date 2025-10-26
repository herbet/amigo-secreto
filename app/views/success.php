<?php include './app/views/layouts/header.php'; ?>

<div class="container mt-5">
    <div class="alert alert-success text-center" role="alert">
        <h4 class="alert-heading">Sucesso!</h4>
        <p><?= $message ?></p>
        <hr>
        <a href="<?= $returnUrl ?>" class="btn btn-success">Voltar</a>
    </div>
</div>

<?php include './app/views/layouts/footer.php'; ?>