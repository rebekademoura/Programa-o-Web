<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Todos os Produtos</h2>

    <form method="get" class="row mb-4">
        <div class="col-md-4">
            <input type="text" name="filtroNome" class="form-control" placeholder="Buscar por nome"
                   value="<?= esc($filtroNome ?? '') ?>">
        </div>
        <div class="col-md-3">
            <select name="filtroPreco" class="form-select">
                <option value="">Faixa de preço</option>
                <option value="baixo" <?= $filtroPreco === 'baixo' ? 'selected' : '' ?>>Abaixo de R$99</option>
                <option value="medio" <?= $filtroPreco === 'medio' ? 'selected' : '' ?>>R$100–R$499</option>
                <option value="alto" <?= $filtroPreco === 'alto' ? 'selected' : '' ?>>Acima de R$500</option>
            </select>
        </div>
        <div class="col-md-3">
            <select name="id_categoria" class="form-select">
                <option value="">Todas as categorias</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $idCategoria == $cat['id'] ? 'selected' : '' ?>>
                        <?= esc($cat['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
    </form>

    <div class="row">
    <?php foreach ($produtos as $produto): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 d-flex flex-column justify-content-between">

                <?php if (!empty($produto['foto_capa'])): ?>
                    <img src="<?= base_url('uploads/fotos/' . $produto['foto_capa']) ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                <?php endif; ?>

                <div class="card-body text-center">
                    <strong class="d-block mb-1"><?= esc($produto['nome']) ?></strong>
                    <p class="card-text text-success mb-0">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                </div>

                <div class="card-footer bg-light border-top">
                    <div class="row">
                        <div class="col-6 text-start">
                            <a href="<?= site_url('produtos/detalhes/' . $produto['id']) ?>"
                               class="btn btn-outline-primary btn-sm w-100">
                                Ver mais detalhes
                            </a>
                        </div>
                        <div class="col-6 text-end">
                            <a href="<?= site_url('carrinho/adicionar/' . $produto['id']) ?>"
                               class="btn btn-success btn-sm w-100">
                                <i class="bi bi-cart-plus"></i> Carrinho
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    <?php endforeach; ?>
</div>



    <?= $pager->links() ?>
</div>

<?= $this->endSection() ?>
