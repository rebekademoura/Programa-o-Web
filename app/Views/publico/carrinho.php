<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php if ($logs = session()->getFlashdata('logs')): ?>
  <script>
  <?php foreach ($logs as $log): ?>
    console.log(<?= json_encode($log, JSON_UNESCAPED_UNICODE) ?>);
  <?php endforeach; ?>
  </script>
<?php endif; ?>

<div class="container-fluid px-5 py-5">
    <h2 class="mb-4">Meu Carrinho</h2>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th scope="col">Produto</th>
                    <th scope="col">Qtde</th>
                    <th scope="col">Parcelas</th>
                    <th scope="col">Preço Total</th>
                    <th scope="col" class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($itens)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Seu carrinho está vazio.</td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($itens as $item): ?>
                <tr>
                    <td class="d-flex align-items-center">
                        <?php if (! empty($item['produto']['foto_capa'])): ?>
                            <img src="<?= base_url('uploads/fotos/' . $item['produto']['foto_capa']) ?>" class="rounded me-3" style="width:60px; height:60px; object-fit:cover;">
                        <?php else: ?>
                            <i class="bi bi-image fs-2 text-muted me-3"></i>
                        <?php endif; ?>
                        <span><?= esc($item['produto']['nome']) ?></span>
                    </td>
                    <td><?= esc($item['quantidade']) ?></td>
                    <td><?= esc($item['parcelas']) ?>x</td>
                    <td class="text-success fw-semibold">R$ <?= number_format($item['preco_total'], 2, ',', '.') ?></td>
                    <td class="text-end">
                        <a href="<?= site_url('loja/removerDoCarrinho/' . $item['id']) ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Remover este item?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-between mt-4">
        <a class='btn btn-success' href="#"
   data-bs-toggle="modal"
   data-bs-target="#modalPagamento">
  Finalizar compra
</a>
    </div>
</div>

<!-- Modal de Pagamento -->
<div class="modal fade" id="modalPagamento" tabindex="-1" aria-labelledby="modalPagamentoLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="modalPagamentoLabel">Finalizar Compra</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= site_url('loja/finalizarCompra') ?>" method="post">
        <?= csrf_field() ?>
        <div class="modal-body">
          <div class="mb-3">
            <label for="forma_pagamento" class="form-label">Forma de pagamento</label>
            <select id="forma_pagamento" name="forma_pagamento" class="form-select" required>
              <option value="pix">PIX</option>
              <option value="cartao">Cartão</option>
            </select>
          </div>
          <div class="mb-3" id="parcelasWrapper" style="display:none;">
            <label for="parcelas" class="form-label">Número de parcelas</label>
            <select id="parcelas" name="parcelas" class="form-select">
              <?php for ($p = 1; $p <= 12; $p++): ?>
                <option value="<?= $p ?>"><?= $p ?>x sem juros</option>
              <?php endfor; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer border-0">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Confirmar Compra</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  // Exibe ou oculta o campo de parcelas conforme seleção
  document.getElementById('forma_pagamento').addEventListener('change', function() {
    document.getElementById('parcelasWrapper').style.display =
      this.value === 'cartao' ? 'block' : 'none';
  });
</script>


<script>
// Selecionar / desselecionar todos
const selectAll = document.getElementById('select-all');
if (selectAll) {
    selectAll.addEventListener('change', function() {
        document.querySelectorAll('.select-item').forEach(cb => cb.checked = this.checked);
    });
}
</script>

<?= $this->endSection() ?>
