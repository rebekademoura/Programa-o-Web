<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-5">
  <!-- Cabeçalho -->
  <div class="text-center mb-5">
    <h2 class="fw-bold">Produtos da Loja de <?= esc($vendedor['username']) ?></h2>
    <p class="text-muted">Confira os itens exclusivos deste vendedor</p>
  </div>

  <!-- Grid de Cards -->
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
    <?php foreach ($produtos as $produto): ?>
      <div class="col">
        <div class="card h-100 shadow-sm border-0 rounded-3">
          
          <?php if (!empty($produto['foto_capa'])): ?>
            <img src="<?= base_url('uploads/fotos/' . $produto['foto_capa']) ?>"
                 class="card-img-top rounded-top"
                 style="height: 200px; object-fit: cover;"
                 alt="<?= esc($produto['nome']) ?>">
          <?php else: ?>
            <div class="bg-secondary d-flex align-items-center justify-content-center rounded-top"
                 style="height: 200px;">
              <i class="bi bi-image fs-1 text-white"></i>
            </div>
          <?php endif; ?>

          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?= esc($produto['nome']) ?></h5>
            <p class="card-text text-primary fs-5 fw-semibold">
              R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
            </p>
            <a href="<?= site_url('produtos/detalhes/' . $produto['id']) ?>"
               class="btn btn-outline-primary mt-auto">
              Ver Detalhes
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?= $this->endSection() ?>