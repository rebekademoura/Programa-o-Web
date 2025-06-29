<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

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
        <form action="<?= site_url('loja/finalizarCompra') ?>" method="post">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check2-circle me-1"></i> Finalizar Compra
            </button>
        </form>
    </div>
</div>

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
