<?php include './app/views/layouts/header.php'; ?>
<div class="alert alert-danger text-center" role="alert">
    <h4 class="alert-heading">Ops! Algo deu errado.</h4>
    <p><?= $message ?></p>
    <hr>
    <a href="<?= $returnUrl ?>" class="btn btn-primary">Voltar</a>
</div>

<?php include './app/views/layouts/footer.php'; ?>
