<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <h2 class="mb-4">Relatório de Vendas</h2>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Vendido</div>
                <div class="card-body">
                    <h3>R$ <?= number_format($totalVendido, 2, ',', '.') ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Quantidade Total</div>
                <div class="card-body">
                    <h3><?= esc($quantidadeTotal) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <?php
// Calcula total por produto
$resumo = [];
foreach ($vendas as $v) {
    $nomeProd = $v['produto'];
    if (! isset($resumo[$nomeProd])) {
        $resumo[$nomeProd] = ['quant' => 0, 'valor' => 0];
    }
    $resumo[$nomeProd]['quant'] += $v['quantidade'];
    $resumo[$nomeProd]['valor'] += $v['valor_total'];
}
?>
<h4 class="mt-4">Resumo por Produto</h4>
<table class="table mb-4">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Total Quantidade</th>
            <th>Total Valor</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($resumo as $nome => $dados): ?>
            <tr>
                <td><?= esc($nome) ?></td>
                <td><?= esc($dados['quant']) ?></td>
                <td>R$ <?= number_format($dados['valor'], 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


    <?php if (! empty($vendas)): ?>
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Data</th>
                    <th>Comprador</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor Total</th>
                    <th>Pagamento</th>
                    <th>Parcelas</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vendas as $venda): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($venda['criado_em'])) ?></td>
                        <td><?= esc($venda['comprador']) ?></td>
                        <td><?= esc($venda['produto']) ?></td>
                        <td><?= esc($venda['quantidade']) ?></td>
                        <td>R$ <?= number_format($venda['valor_total'], 2, ',', '.') ?></td>
                        <td><?= esc(ucfirst($venda['forma_pagamento'])) ?></td>
                        <td><?= esc($venda['parcelas']) ?>x</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">Nenhuma venda registrada.</p>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
