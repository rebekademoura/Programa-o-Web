<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <h2 class="mb-4">Categorias</h2>
    <div class="d-flex justify-content-end mb-3">
        <a href="<?= site_url('categorias/create') ?>" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> Nova Categoria
        </a>
    </div>

    <?php if (! empty($categorias)): ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nome</th>
                        <th scope="col" class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $categoria): ?>
                        <tr>
                            <td><?= esc($categoria['id']) ?></td>
                            <td><?= esc($categoria['nome']) ?></td>
                            <td class="text-end">
                                <a href="<?= site_url('categorias/edit/' . $categoria['id']) ?>" class="btn btn-outline-warning btn-sm me-1">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <a href="<?= site_url('categorias/delete/' . $categoria['id']) ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Confirma exclusão desta categoria?')">
                                    <i class="bi bi-trash-fill"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            Nenhuma categoria encontrada.
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
