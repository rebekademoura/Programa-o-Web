<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-5">
  <h2 class="mb-4">Editar Perfil</h2>
  <div class="row">
    <!-- Formulário de dados pessoais -->
    <div class="col-md-6">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
          <i class="bi bi-person-circle me-1"></i> Dados Pessoais
        </div>
        <div class="card-body">
          <form action="<?= site_url('perfil/update') ?>" method="POST">
            <div class="mb-3">
              <label for="username" class="form-label">Usuário</label>
              <input type="text" id="username" name="username" class="form-control" value="<?= esc(session()->get('usuario')['username']) ?>" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">E-mail</label>
              <input type="email" id="email" name="email" class="form-control" value="<?= esc(session()->get('usuario')['email']) ?>" required>
            </div>
            <button type="submit" class="btn btn-info"><i class="bi bi-save me-1"></i> Salvar</button>
          </form>
        </div>
      </div>

      <!-- Formulário de senha -->
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-white">
          <i class="bi bi-key-fill me-1"></i> Alterar Senha
        </div>
        <div class="card-body">
          <form action="<?= site_url('perfil/updateSenha') ?>" method="POST">
            <div class="mb-3">
              <label for="senhaAtual" class="form-label">Senha Atual</label>
              <input type="password" id="senhaAtual" name="senhaAtual" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="novaSenha" class="form-label">Nova Senha</label>
              <input type="password" id="novaSenha" name="novaSenha" class="form-control" required>
            </div>
            <div class="mb-3">
              <label for="confirmeSenha" class="form-label">Confirme Nova Senha</label>
              <input type="password" id="confirmeSenha" name="confirmeSenha" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning"><i class="bi bi-lock-fill me-1"></i> Atualizar Senha</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Upload de foto de perfil -->
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
          <i class="bi bi-image-fill me-1"></i> Foto de Perfil
        </div>
        <div class="card-body text-center">
          <?php if (! empty(session()->get('usuario')['foto_perfil'])): ?>
            <img src="<?= base_url('uploads/' . session()->get('usuario')['foto_perfil']) ?>" class="rounded-circle mb-3" style="width:150px; height:150px; object-fit:cover;">
          <?php else: ?>
            <i class="bi bi-person-circle" style="font-size: 6rem; color: #ccc;"></i>
          <?php endif ?>
          <form action="<?= site_url('perfil/updateFoto') ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <input type="file" id="foto_perfil" name="foto_perfil" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-secondary"><i class="bi bi-upload me-1"></i> Atualizar Foto</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
m