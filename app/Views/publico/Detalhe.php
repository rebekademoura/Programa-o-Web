<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= esc($produto['nome']) ?> - Viva Leve</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=psychiatry" />
</head>
<body>

<!-- Topo -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid">
  <a class="navbar-brand ms-5" href="<?=site_url('produtos/index')?>"><span class="material-symbols-outlined">
psychiatry
</span>  Viva Leve</a>     <div class="ms-auto">
            <a href="<?= site_url('login') ?>" class="btn btn-outline-primary me-2">Login</a>
            <a href="<?= site_url('cadastro') ?>" class="btn btn-primary">Cadastro</a>
        </div>
    </div>
</nav>

<!-- Detalhes do Produto -->
<div class="container py-5">
    <div class="row">
        <!-- Coluna do Carrossel -->
        <div class="col-md-6">
            <div id="meuCarrossel" class="carousel slide mb-4" data-bs-ride="carousel">
                <?php if (!empty($fotos)): ?>
                    <!-- Indicadores -->
                    <div class="carousel-indicators">
                        <?php foreach ($fotos as $index => $foto): ?>
                            <button type="button" data-bs-target="#meuCarrossel" data-bs-slide-to="<?= $index ?>"
                                class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>"></button>
                        <?php endforeach ?>
                    </div>

                    <!-- Slides -->
                    <div class="carousel-inner">
                        <?php foreach ($fotos as $index => $foto): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <img src="<?= base_url('uploads/fotos/' . $foto['caminho']) ?>" class="d-block w-100" style="max-height: 400px; object-fit: contain;" alt="Imagem <?= $index + 1 ?>">
                            </div>
                        <?php endforeach ?>
                    </div>

                    <!-- Botões -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#meuCarrossel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#meuCarrossel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden">Próximo</span>
                    </button>
                <?php else: ?>
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
                $precoParcelado = $preco * pow(1.03, 12);
                $parcela = $precoParcelado / 12;
            ?>
            <p>
                <strong>Parcelado:</strong>
                12x de R$ <?= number_format($parcela, 2, ',', '.') ?> (com juros)
            </p>

            <p><strong>Descrição:</strong><br><?= esc($produto['descricao']) ?></p>

            <p><strong>Estoque:</strong> <?= esc($produto['estoque']) ?> unidades</p>

            <a href="<?= site_url('carrinho/adicionar/' . $produto['id']) ?>" class="btn btn-success w-100 mt-3">
                <i class="bi bi-cart-plus"></i> Adicionar ao Carrinho
            </a>

            <hr>

            <p class="text-muted mb-0">
                <strong>Loja do vendedor:</strong>
                <a href="<?= site_url('loja/' . $produto['usuario_id']) ?>">Ver outros produtos</a>
            </p>
        </div>
    </div>
</div>

<!-- Produtos Relacionados -->
<hr>
<div class="col-12 p-4">
    <h2 class="text-center mb-4">Produtos Relacionados</h2>

    <div class="row justify-content-center">
        <?php foreach ($produtos as $produto): ?>
            <div class="col-md-4 col-sm-6 col-10 mb-4">
                <div class="card h-100 text-center shadow-sm mx-auto" style="max-width: 100%;">
                    <?php if (!empty($produto['foto_capa'])): ?>
                        <img src="<?= base_url('uploads/fotos/' . $produto['foto_capa']) ?>" class="card-img-top" style="height: 180px; object-fit: cover;">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                            <i class="bi bi-image fs-1 text-muted"></i>
                        </div>
                    <?php endif; ?>

                    <div class="card-body">
                        <h6 class="mb-1"><?= esc($produto['nome']) ?></h6>
                        <p class="text-primary fw-bold">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                    </div>

                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between px-2">
                        <a href="<?= site_url('produtos/detalhes/' . $produto['id']) ?>" class="btn btn-outline-primary btn-sm w-50 me-1">Detalhes</a>
                        <a href="<?= site_url('carrinho/adicionar/' . $produto['id']) ?>" class="btn btn-primary btn-sm w-50 ms-1">
                            <i class="bi bi-cart-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Bootstrap Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
