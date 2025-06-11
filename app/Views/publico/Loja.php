<h2 class="text-center my-4">Produtos da Loja de <?= esc($vendedor['username']) ?></h2>

<div class="container">
    <div class="row">
        <?php foreach ($produtos as $produto): ?>
            <div class="col-md-4 mb-4">
                <div class="card text-center h-100">
                    <?php if (!empty($produto['foto_capa'])): ?>
                        <img src="<?= base_url('uploads/fotos/' . $produto['foto_capa']) ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
                    <?php endif; ?>

                    <div class="card-body">
                        <h6><?= esc($produto['nome']) ?></h6>
                        <p class="text-primary fw-bold">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                        <a href="<?= site_url('produtos/detalhes/' . $produto['id']) ?>" class="btn btn-outline-primary btn-sm">Ver Detalhes</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
 