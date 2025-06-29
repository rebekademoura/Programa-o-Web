<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Conteúdo principal full width -->
<div class="container-fluid px-5 py-5">
  <div class="row gx-5">

    <!-- Filtros (3 colunas) -->
    <aside class="col-lg-3">
      <div class="p-4 bg-light h-100 rounded">
        <h5 class="mb-3">Filtros</h5>
        <form method="get">
          <div class="mb-3">
            <label class="form-label">Buscar</label>
            <input type="text" name="filtroNome" class="form-control" placeholder="Buscar por nome"
                   value="<?= esc($filtroNome ?? '') ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Faixa de preço</label>
            <select name="filtroPreco" class="form-select">
              <option value="">Todas</option>
              <option value="baixo" <?= $filtroPreco==='baixo'?'selected':'' ?>>Abaixo R$99</option>
              <option value="medio" <?= $filtroPreco==='medio'?'selected':'' ?>>R$100–R$499</option>
              <option value="alto" <?= $filtroPreco==='alto'?'selected':'' ?>>Acima R$500</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Categoria</label>
            <select name="id_categoria" class="form-select">
              <option value="">Todas</option>
              <?php foreach($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($idCategoria==$cat['id'])?'selected':''?>>
                  <?= esc($cat['nome']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </form>
      </div>
    </aside>

    <!-- Produtos (9 colunas) -->
    <section class="col-lg-9">
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        <?php foreach($produtos as $produto): ?>
          <div class="col">
            <div class="card h-100 shadow-sm">
              <?php if(!empty($produto['foto_capa'])): ?>
                <img src="<?= base_url('uploads/fotos/'.$produto['foto_capa']) ?>"
                     class="card-img-top" style="height:180px;object-fit:cover;">
              <?php else: ?>
                <div class="bg-secondary d-flex align-items-center justify-content-center"
                     style="height:180px;">
                  <i class="bi bi-image fs-1 text-white"></i>
                </div>
              <?php endif; ?>
              <div class="card-body d-flex flex-column">
                <h6 class="card-title"><?= esc($produto['nome']) ?></h6>
                <p class="text-primary fw-bold mb-3">
                  R$ <?= number_format($produto['preco'],2,',','.') ?>
                </p>
                <a href="<?= site_url('produtos/detalhes/'.$produto['id']) ?>"
                   class="btn btn-outline-primary mt-auto">
                  Detalhes
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Paginação -->
      <div class="mt-5 d-flex justify-content-center">
        <?= $pager->links() ?>
      </div>
    </section>

  </div>
</div>

<?= $this->endSection() ?>
