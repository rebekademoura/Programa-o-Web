<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 card p-4 border rounded border-4">

            <h2 class="mb-4">Redefinir senha</h2>

            <form action="<?= site_url('redefinirsenha'.'/'.$token) ?>" method="POST">

                <div class="mb-3">
                    <label for="password" class="form-label">Digite uma nova senha:</label>
                    <input type="password" name="password" id="password" value="<?= old('password') ?>" required class="form-control">
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirme a nova senha:</label>
                    <input type="password" name="confirm_password" id="confirm_password" value="<?= old('confirm_password') ?>" required class="form-control">
                </div>
                <button type="submit" class="btn btn-primary ">Redefinir senha</button>[]
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
