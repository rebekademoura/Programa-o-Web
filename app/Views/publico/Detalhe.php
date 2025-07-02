<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Detalhes do Produto -->
<div class="container py-5">
    <div class="row">
        <!-- Coluna do Carrossel -->
        <div class="col-md-6">
            <div id="meuCarrossel" class="carousel slide mb-4" data-bs-ride="carousel">
                <?php if (!empty($fotos)): ?>
                    <div class="carousel-indicators">
                        <?php foreach ($fotos as $index => $foto): ?>
                            <button type="button" data-bs-target="#meuCarrossel" data-bs-slide-to="<?= $index ?>"
                                class="<?= $index === 0 ? 'active' : '' ?>"
                                aria-current="<?= $index === 0 ? 'true' : 'false' ?>"></button>
                        <?php endforeach ?>
                    </div>
                    <div class="carousel-inner">
                        <?php foreach ($fotos as $index => $foto): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <img src="<?= base_url('uploads/fotos/' . $foto['caminho']) ?>"
                                     class="d-block w-100"
                                     style="max-height:400px; object-fit:contain;"
                                     alt="Imagem <?= $index + 1 ?>">
                            </div>
                        <?php endforeach ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#meuCarrossel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#meuCarrossel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden">Próximo</span>
                    </button>
                    <p class="text-center">Nenhuma imagem disponível para este produto.</p>
                <?php endif ?>
            </div>
            <?php if (!empty($fotos)): ?>
        <div class="row">
            <?php foreach ($fotos as $foto): ?>
                <div class="col-md-3 text-center mb-4" style="width: 100px; height: 100px;">
                    <div class="card">
                        <img src="<?= base_url('uploads/fotos/' . $foto['caminho']) ?>" class="card-img-top" style="max-height: 200px; object-fit: cover;">
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php else: ?>
        <p>Nenhuma foto encontrada</p>
    <?php endif ?>
        </div>

        <!-- Coluna das Informações -->
        <div class="col-md-6">
            <h2 class="mb-3"><?= esc($produto['nome']) ?></h2>
            <p><strong>Preço à vista:</strong> R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
            <?php
                $preco = $produto['preco'];
                $precoParcelado = $preco * 1.03;
                $parcela = $precoParcelado / 12;
            ?>
            <p><strong>Parcelado:</strong> 12x de R$ <?= number_format($parcela, 2, ',', '.') ?> (com juros)</p>
            <p><strong>Descrição:</strong><br><?= esc($produto['descricao']) ?></p>
            <p><strong>Disponíveis:</strong> <?= esc($produto['estoque']) ?> unidades</p>

            <form action="<?= site_url('loja/adicionarAoCarrinho/' . $produto['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-6">
                        <label for="quantidade" class="form-label">Quantidade</label>
                        <input type="number" id="quantidade" name="quantidade" class="form-control" value="1" required>
                    </div>

                    <!-- Seleção da forma de pagamento -->
                    <div class="col-6">
                        <label class="form-label">Forma de Pagamento</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="forma_pagamento" id="pagCartao" value="cartao" checked>
                            <label class="form-check-label" for="pagCartao">Cartão</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="forma_pagamento" id="pagPix" value="pix">
                            <label class="form-check-label" for="pagPix">Pix</label>
                        </div>
                    </div>

                    <!-- Parcelas só para cartão -->
                    <div class="col-6" id="parcelasDiv">
                        <label for="parcelas" class="form-label">Parcelas</label>
                        <input type="number" id="parcelas" name="parcelas" class="form-control" value="1" min="1" max="12" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 mt-4">
                    <i class="bi bi-cart-plus"></i> Adicionar ao Carrinho
                </button>
            </form>

            <hr>
<?php if (! empty($vendedor)): ?>
  <p class="mt-4">
    <a href="<?= site_url('publico/lojaVendedor/' . $vendedor['id']) ?>"
       class="btn btn-outline-primary">
      <i class="bi bi-shop me-1"></i>
      Ver todos os produtos de <?= esc($vendedor['username']) ?>
    </a>
  </p>
<?php endif; ?>

    </div>
    </div>
</div>

<!-- Script para alternar visibilidade de parcelas -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('input[name="forma_pagamento"]');
        const parcelasDiv = document.getElementById('parcelasDiv');
        function toggleParcelas() {
            parcelasDiv.style.display = this.value === 'cartao' ? 'block' : 'none';
            // Quando Pix, reseta valor para 1
            if (this.value === 'pix') document.getElementById('parcelas').value = 1;
        }
        radios.forEach(r => r.addEventListener('change', function() { toggleParcelas.call(this); }));
        // Inicializa
        const checked = document.querySelector('input[name="forma_pagamento"]:checked');
        toggleParcelas.call(checked);
    });
</script>
    </div>
</div>

<!-- Produtos Relacionados -->
<hr>
<div class="container py-4">
    <h2 class="text-center mb-4">Produtos Relacionados</h2>
    <div class="row justify-content-center">
        <?php foreach ($produtos as $rel): ?>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card h-100 text-center shadow-sm">
                    <?php if (!empty($rel['foto_capa'])): ?>
                        <img src="<?= base_url('uploads/fotos/' . $rel['foto_capa']) ?>"
                             class="card-img-top"
                             style="height:180px; object-fit:cover;">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height:180px;">
                            <i class="bi bi-image fs-1 text-muted"></i>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h6 class="mb-1"><?= esc($rel['nome']) ?></h6>
                        <p class="text-primary fw-bold">
                            R$ <?= number_format($rel['preco'], 2, ',', '.') ?>
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between px-2">
                        <a href="<?= site_url('produtos/detalhes/' . $rel['id']) ?>"
                           class="btn btn-outline-primary btn-sm w-50 me-1">Detalhes</a>
                        <a href="<?= site_url('carrinho/adicionar/' . $rel['id']) ?>"
                           class="btn btn-primary btn-sm w-50 ms-1">
                            <i class="bi bi-cart-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?= $this->endSection() ?>
