<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 card p-4 border rounded border-4">

            <h2 class="mb-4">Redefinir senha</h2>

            <form action="<?= site_url('esqueciasenha') ?>" method="POST">

                <div class="mb-3">
                    <label for="email" class="form-label">Digite seu email:</label>
                    <input type="email" name="email" id="email" value="<?= old('email') ?>" required class="form-control">
                </div>
                <button type="submit" class="btn btn-primary ">Enviar link de redefinição</button>
                <br>
                <a href="<?= site_url('login') ?>" class="btn btn-link">Voltar para o login</a>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
