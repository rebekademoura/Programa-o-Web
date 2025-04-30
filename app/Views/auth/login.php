<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 card p-4 border rounded border-4">

            <h2 class="mb-4">Login</h2>

            <!-- Mensagens -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <form action="<?= site_url('autenticar') ?>" method="POST">

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail:</label>
                    <input type="email" name="email" id="email" required class="form-control">
                </div>

                <div class="mb-3">
                    <label for="senha" class="form-label">Senha:</label>
                    <input type="password" name="senha" id="senha" required class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Entrar</button>
                
                <a href="<?= site_url('cadastrar') ?>" class="btn btn-link">Criar conta</a>
            </form>
            <br>
                <a href="<?= site_url('esqueciasenha') ?>">Esqueci minha senha</a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
