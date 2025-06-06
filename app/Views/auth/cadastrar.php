<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container d-flex justify-content-center align-items-center ">
    <div class="" style="width: 100%; max-width: 450px;">
        <h3 class="text-center mb-4">Criar Conta</h3>

        <!-- Mensagens -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (isset($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $erro): ?>
                        <li><?= esc($erro) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('salvarUsuario') ?>" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Nome completo</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= old('username') ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
            </div>

            <div class="mb-3">
                <label for="password_hash" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="passwords" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Criar Conta</button>
            </div>

            <div class="text-center mt-3">
                <small>Já tem uma conta? <a href="<?= site_url('login') ?>">Faça login</a></small>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
