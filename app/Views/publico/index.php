<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minha Loja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=psychiatry" />

</head>
<body>

<!-- Topo -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
     <a class="navbar-brand ms-5" href="produtos/index"><span class="material-symbols-outlined">
psychiatry
</span>  Viva Leve</a>
        <div class="ms-auto">
            <a href="<?= site_url('login') ?>" class="btn btn-outline-primary me-2">Login</a>
            <a href="<?= site_url('cadastro') ?>" class="btn btn-primary">Cadastro</a>
        </div>
    </div>
</nav>

<div class="text-white text-center py-3" style="background: #2E8B57	;">
    <div class="container">
        <h1 class="mb-0">Bem-vindo à Viva Leve</h1>
        <p class="mb-0">Encontre os melhores produtos aqui!</p>
    </div>

</div>

<!-- Conteúdo principal com largura total -->
<div class="container-fluid">
    <div class="row">

        <!-- Filtros -->
        <div class="col-md-3 p-4 bg-light border-end">
            <form method="get">
                <div class="mb-3">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="filtroNome" class="form-control" placeholder="Buscar por nome" value="<?= esc($filtroNome ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Faixa de preço</label>
                    <select name="filtroPreco" class="form-select">
                        <option value="">Todas</option>
                        <option value="baixo" <?= $filtroPreco === 'baixo' ? 'selected' : '' ?>>Abaixo de R$99</option>
                        <option value="medio" <?= $filtroPreco === 'medio' ? 'selected' : '' ?>>R$100–R$499</option>
                        <option value="alto" <?= $filtroPreco === 'alto' ? 'selected' : '' ?>>Acima de R$500</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Categoria</label>
                    <select name="id_categoria" class="form-select">
                        <option value="">Todas</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $idCategoria == $cat['id'] ? 'selected' : '' ?>>
                                <?= esc($cat['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </form>
        </div>

        <!-- Produtos -->
        <div class="col-md-9 p-4">
            <div class="row">
                <?php foreach ($produtos as $produto): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 text-center shadow-sm">
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

            <!-- Paginação -->
            <div class="mt-4">
                <?= $pager->links() ?>
            </div>
        </div>

    </div>
</div>

</body>
</html>
