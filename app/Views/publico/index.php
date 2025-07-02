<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<style>
  /* Painel de filtros */
  .filters-panel {
    background: #ffffff;
    border-radius: .75rem;
    box-shadow: 0 .5rem 1rem rgba(0, 0, 0, 0.05);
    padding: 1.5rem;
  }
  .filters-panel h5 {
    font-weight: 600;
    color: #0d6efd;
    margin-bottom: 1rem;
  }
  .filters-panel .form-label {
    font-weight: 500;
    color: #495057;
  }
  .filters-panel .form-control,
  .filters-panel .form-select {
    border-radius: .5rem;
    padding: .5rem 1rem;
  }
  .filters-panel .input-group-text {
    background: transparent;
    border: none;
    padding-right: .5rem;
    color: #6c757d;
  }
  .filters-panel .btn-filter {
    border-radius: 2rem;
    padding: .5rem 1.5rem;
  }

  /* Cartões (mantidos iguais) */
  .card-custom {
    border: none;
    border-radius: .75rem;
    overflow: hidden;
    box-shadow: 0 .25rem .5rem rgba(0,0,0,0.05);
    transition: transform .2s, box-shadow .2s;
  }
  .card-custom:hover {
    transform: translateY(-4px);
    box-shadow: 0 1rem 1.5rem rgba(0,0,0,0.1);
  }
  .card-custom .card-img-top {
    height: 180px;
    object-fit: cover;
  }
  .btn-rounded {
    border-radius: 2rem;
  }
</style>

<div class="container-fluid px-5 py-5">
  <div class="row gx-5">

    <!-- Filtros (3 colunas) -->
    <aside class="col-lg-3 mb-4 mb-lg-0">
      <div class="filters-panel h-100">
        <h5><i class="bi bi-funnel-fill me-1"></i>Filtros</h5>
        <form method="get">
          <div class="mb-4">
            <label class="form-label" for="filtroNome">Buscar produto</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-search"></i></span>
              <input
                id="filtroNome"
                type="text"
                name="filtroNome"
                class="form-control"
                placeholder="Digite o nome..."
                value="<?= esc($filtroNome ?? '') ?>">
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label" for="filtroPreco">Preço</label>
            <select id="filtroPreco" name="filtroPreco" class="form-select">
              <option value="">Todas as faixas</option>
              <option value="baixo"  <?= $filtroPreco==='baixo'  ?'selected':'' ?>>Até R$ 99</option>
              <option value="medio"  <?= $filtroPreco==='medio'  ?'selected':'' ?>>R$ 100–499</option>
              <option value="alto"   <?= $filtroPreco==='alto'   ?'selected':'' ?>>Acima de R$ 500</option>
            </select>
          </div>

          <div class="mb-4">
            <label class="form-label" for="id_categoria">Categoria</label>
            <select id="id_categoria" name="id_categoria" class="form-select">
              <option value="">Todas as categorias</option>
              <?php foreach($categorias as $cat): ?>
                <option
                  value="<?= $cat['id'] ?>"
                  <?= ($idCategoria==$cat['id'])?'selected':''?>>
                  <?= esc($cat['nome']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <button type="submit" class="btn btn-primary btn-filter w-100">
            <i class="bi bi-filter-circle-fill me-2"></i>Aplicar filtros
          </button>
        </form>
      </div>
    </aside>

    <!-- Produtos (9 colunas) -->
    <section class="col-lg-9">
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        <?php foreach($produtos as $produto): ?>
          <div class="col">
            <div class="card card-custom">
              <?php if(!empty($produto['foto_capa'])): ?>
                <img
                  src="<?= base_url('uploads/fotos/'.$produto['foto_capa']) ?>"
                  class="card-img-top"
                  alt="<?= esc($produto['nome']) ?>">
              <?php else: ?>
                <div class="bg-secondary d-flex align-items-center justify-content-center"
                     style="height:180px;">
                  <i class="bi bi-image fs-1 text-white"></i>
                </div>
              <?php endif; ?>

              <div class="card-body d-flex flex-column">
                <h6 class="card-title mb-2"><?= esc($produto['nome']) ?></h6>
                <p class="text-primary fw-bold mb-4">
                  R$ <?= number_format($produto['preco'],2,',','.') ?>
                </p>

                <div class="mt-auto d-flex gap-2">
                  <a
                    href="<?= site_url('produtos/detalhes/'.$produto['id']) ?>"
                    class="btn btn-outline-primary btn-rounded flex-fill">
                    <i class="bi bi-info-circle me-1"></i> Detalhes
                  </a>
                  <button
                    class="btn btn-primary btn-rounded flex-fill"
                    data-bs-toggle="modal"
                    data-bs-target="#modalCarrinho<?= $produto['id'] ?>">
                    <i class="bi bi-cart-fill me-1"></i> Adicionar
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal de adicionar ao carrinho (igual ao anterior) -->
          <!-- Dentro do seu loop de produtos, substitua o modal anterior por este: -->
<div
  class="modal fade"
  id="modalCarrinho<?= $produto['id'] ?>"
  tabindex="-1"
  aria-labelledby="modalCarrinhoLabel<?= $produto['id'] ?>"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content rounded">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="modalCarrinhoLabel<?= $produto['id'] ?>">
          <?= esc($produto['nome']) ?> – Adicionar ao carrinho
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form
        action="<?= site_url('loja/adicionarAoCarrinho/'.$produto['id']) ?>"
        method="post">
        <?= csrf_field() ?>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Quantidade</label>
            <input
              type="number"
              name="quantidade"
              class="form-control"
              value="1"
              min="1"
              required>
          </div>
          <div class="mb-3">
            <label class="form-label">Forma de pagamento</label>
            <select
              id="forma<?= $produto['id'] ?>"
              name="forma_pagamento"
              class="form-select"
              required>
              <option value="pix">PIX</option>
              <option value="cartao">Cartão</option>
            </select>
          </div>
          <div
            class="mb-3"
            id="parcelasWrapper<?= $produto['id'] ?>"
            style="display: none;">
            <label class="form-label">Parcelas</label>
            <select
              name="parcelas"
              class="form-select">
              <?php for($p=1; $p<=12; $p++): ?>
                <option value="<?= $p ?>"><?= $p ?>x</option>
              <?php endfor; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button
            type="button"
            class="btn btn-outline-secondary btn-rounded"
            data-bs-dismiss="modal">
            Cancelar
          </button>
          <button
            type="submit"
            class="btn btn-primary btn-rounded">
            Confirmar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Adicione este script uma vez, preferencialmente no final da página -->
<script>
  document.querySelectorAll('select[id^="forma"]').forEach(select => {
    select.addEventListener('change', e => {
      const id = e.target.id.replace('forma', '');
      const wrapper = document.getElementById(`parcelasWrapper${id}`);
      wrapper.style.display = e.target.value === 'cartao' ? 'block' : 'none';
    });
  });
</script>

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

<?= $this->section('scripts') ?>
<script>
  document.querySelectorAll('[id^="forma"]').forEach(select => {
    select.addEventListener('change', (e) => {
      const id = e.target.id.replace('forma', '');
      const wrapper = document.getElementById(`parcelasWrapper${id}`);
      wrapper.style.display = e.target.value === 'cartao' ? 'block' : 'none';
    });
  });

  function confirmarAdicionarCarrinho(produtoId) {
    const form       = document.getElementById(`formCarrinho${produtoId}`);
    const quantidade = form.quantidade.value;
    const forma      = document.getElementById(`forma${produtoId}`).value;
    const parcelas   = forma === 'cartao'
      ? document.getElementById(`parcelas${produtoId}`).value
      : 1;

    AdicionarAoCarrinho(produtoId, quantidade, forma, parcelas);

    const modalEl = document.getElementById(`modalCarrinho${produtoId}`);
    const modal   = bootstrap.Modal.getInstance(modalEl);
    modal.hide();
  }
</script>
<?= $this->endSection() ?>
