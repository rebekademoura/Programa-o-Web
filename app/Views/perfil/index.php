<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <!--formulário para edição de username e email-->
        <div class="container, border">
            <form action="<?= site_url('perfil/update') ?>" enctype="multipart/form-data" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Nome de usuário</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?=esc(session()->get('usuario')['username']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" value="<?=esc(session()->get('usuario')['email']) ?>" required>
                </div>
                <button type="submit" class="btn btn-info">Atualizar</button>
            </form>
        </div>

    <!--formulário para senha-->
        <div class="container border mt-3 ">
            <form action="<?= site_url('perfil/updateSenha') ?>" method="POST">
                <div class="mb-3">
                    <label for="senhaAtual" class="form-label">Senha atual</label>
                    <input type="text" class="form-control" id="senhaAtual" name="senhaAtual" required>
                </div>
                <div class="mb-3">
                    <label for="novaSenha" class="form-label">Nova senha</label>
                    <input type="text" class="form-control" id="novaSenha" name="novaSenha" required>
                </div>
                <div class="mb-3">
                    <label for="confirmeSenha" class="form-label">Confirme nova senha</label>
                    <input type="text" class="form-control" id="confirmeSenha" name="confirmeSenha" required>
                </div>
                <button type="submit" class="btn btn-info">Atualizar senha</button>
            </form>
        </div>


    <br>

    <!--formulário para imagem-->
        <div class="container border ">
            <?php if(!empty(session()->get('usuario')['foto_perfil'])): ?>
                <img src="<?= site_url('/uploads/'). esc(session()->get('usuario')['foto_perfil']) ?>" alt="Foto de Perfil">

            <?php else: ?>
                <p>Sem foto no perfil</p>
            <?php endif; ?>
            <form action="<?= site_url('perfil/updateFoto') ?>" method="POST" enctype='multipart/form-data'>
                <div class="mb-3">
                    <label for="foto_perfil" class="form-label">Adicione sua foto</label>
                    <input type="file" class="form-control" id="foto_perfil" name="foto_perfil" required accept='image/*'>
                </div>
                <button type="submit" class="btn btn-info">Atualizar foto</button>
            </form>
        </div>









<?= $this->endSection() ?>