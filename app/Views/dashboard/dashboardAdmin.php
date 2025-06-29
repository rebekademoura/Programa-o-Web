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
                    <?php if (! empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= esc($user['id']) ?></td>
                                <td><?= esc($user['username']) ?></td>
                                <td><?= esc($user['role']) ?></td>
                                <td>
                                    <?php if ($user['role'] !== 'admin'): ?>
                                        <form action="<?= site_url('dashboard/deleteUser/' . $user['id']) ?>" method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Confirma exclusão deste usuário?')">Excluir</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted">--</span>
                                    <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">Nenhum usuário encontrado.</td></tr>
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
</div>

<?= $this->endSection() ?>
