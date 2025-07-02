<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <h1 class="mb-4">Painel do Admin: <?= esc(session()->get('username')) ?></h1>

    <!-- Tabela de Produtos -->
    <div class="card mb-5 shadow-sm">
        <div class="card-header bg-primary text-white">
            Produtos para Aprovação
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($produtos)): ?>
                        <?php foreach ($produtos as $produto): ?>
                            <tr>
                                <td><?= esc($produto['id']) ?></td>
                                <td><?= esc($produto['nome']) ?></td>
                                <td><?= esc(substr($produto['descricao'], 0, 50)) ?>...</td>
                                <td><?= esc($produto['status']) ?></td>
                                <td>
                                    <a href="<?= site_url('dashboard/approve/' . $produto['id']) ?>" class="btn btn-sm btn-success me-1" onclick="return confirm('Confirma aprovação deste produto?')">Aprovar</a>
                                    <a href="<?= site_url('dashboard/reject/' . $produto['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirma reprovação deste produto?')">Reprovar</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">Nenhum produto pendente.</td></tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabela de Categorias -->
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white">
            Categorias Cadastradas
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($categorias)): ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <tr>
                                <td><?= esc($categoria['id']) ?></td>
                                <td><?= esc($categoria['nome']) ?></td>
                                <td>
                                    <a href="<?= site_url('categorias/edit/' . $categoria['id']) ?>" class="btn btn-sm btn-warning me-1">Editar</a>
                                    <form action="<?= site_url('categorias/delete/' . $categoria['id']) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma exclusão desta categoria?')">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center">Nenhuma categoria cadastrada.</td></tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
    <br><br>

    <!-- Tabela de Usuários -->
    <div class="card mb-5 shadow-sm">
        <div class="card-header bg-secondary text-white">
            Usuários Cadastrados
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Role</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
  <tr>
    <td><?= esc($user['id']) ?></td>
    <td><?= esc($user['username']) ?></td>
    <td><?= esc($user['role']) ?></td>
    <td>
      <?php if ($user['role'] !== 'admin_geral'): ?>
                                        <form action="<?= site_url('dashboard/deleteUser/' . $user['id']) ?>" method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma exclusão deste usuário?')">Excluir</button>
                                        </form>
<button
          type="button"
          class="btn btn-sm btn-warning"
          data-bs-toggle="modal"
          data-bs-target="#modalEditarPerfil"
          data-user-id="<?= $user['id'] ?>"
          data-username="<?= esc($user['username'], 'attr') ?>"
          data-email="<?= esc($user['email'], 'attr') ?>">
          Editar
        </button>
      <?php else: ?>
        <span class="text-muted">--</span>
      <?php endif ?>
    </td>
  </tr>
<?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    
</div>
<!-- Modal Editar Perfil -->
<div class="modal fade" id="modalEditarPerfil" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Editar Perfil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
          <!-- Aba Dados (name & email) -->
            <form action="<?= site_url('perfil/update') ?>" method="post">
              <?= csrf_field() ?>
              <input type="hidden" name="user_id" id="userIdDados">
              <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="username" id="usernameDados" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" id="emailDados" class="form-control" required>
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-primary">Salvar Dados</button>
              </div>
            </form>
            <form action="<?= site_url('perfil/updateSenha') ?>" method="post">
              <?= csrf_field() ?>
              <input type="hidden" name="user_id" id="userIdSenha">
              <div class="mb-3">
                <label class="form-label">Senha Atual</label>
                <input type="password" name="senhaAtual" class="form-control">
              </div>
              <div class="mb-3">
                <label class="form-label">Nova Senha</label>
                <input type="password" name="novaSenha" class="form-control">
              </div>
              <div class="mb-3">
                <label class="form-label">Confirmar Senha</label>
                <input type="password" name="confirmeSenha" class="form-control">
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-primary">Alterar Senha</button>
              </div>
            </form>
            <form action="<?= site_url('perfil/updateFoto') ?>" method="post" enctype="multipart/form-data">
              <?= csrf_field() ?>
              <input type="hidden" name="user_id" id="userIdFoto">
              <div class="mb-3">
                <label class="form-label">Foto de Perfil</label>
                <input type="file" name="foto_perfil" class="form-control" accept="image/*">
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-primary">Atualizar Foto</button>
              </div>
            </form>
      </div>

    </div>
  </div>
</div>

<script>
  const modal = document.getElementById('modalEditarPerfil');
  modal.addEventListener('show.bs.modal', event => {
    const btn      = event.relatedTarget;
    const id       = btn.getAttribute('data-user-id');
    const username = btn.getAttribute('data-username');
    const email    = btn.getAttribute('data-email');

    // Preenche todos os form hidden/user fields
    document.getElementById('userIdDados').value  = id;
    document.getElementById('userIdSenha').value  = id;
    document.getElementById('userIdFoto').value   = id;

    document.getElementById('usernameDados').value= username;
    document.getElementById('emailDados').value   = email;

    // limpa campos de senha e foto
    document.querySelector('#pane-senha input[name="senhaAtual"]').value   = '';
    document.querySelector('#pane-senha input[name="novaSenha"]').value    = '';
    document.querySelector('#pane-senha input[name="confirmeSenha"]').value= '';
    document.querySelector('#pane-foto input[name="foto_perfil"]').value   = '';
  });
</script>



<?= $this->endSection() ?>
