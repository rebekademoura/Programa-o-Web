<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Viva Leve' ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand ms-5" href="<?= site_url('/') ?>">
            <span class="material-symbols-outlined">psychiatry</span> Viva Leve
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (session()->get('logado')):
                    $user   = session()->get('usuario');
                    $role   = $user['role'];
                    $avatar = !empty($user['foto_perfil']) ? base_url('uploads/' . $user['foto_perfil']) : null;
                ?>
                <?php if ($role === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('categorias') ?>">
                            <i class="bi bi-tags me-1"></i>Categorias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('produtos') ?>">
                            <i class="bi bi-box-seam me-1"></i>Produtos
                        </a>
                    </li>
                <?php elseif ($role === 'admin_geral'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('dashboard') ?>">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('categorias') ?>">
                            <i class="bi bi-tags me-1"></i>Categorias
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('produtos') ?>">
                            <i class="bi bi-box-seam me-1"></i>Produtos
                        </a>
                    </li>
                <?php elseif ($role === 'user'): ?>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="<?= site_url('loja/carrinho') ?>">
                            <span class="material-symbols-outlined">shopping_cart</span>
                            <?php if (session()->get('cart_count') > 0): ?>
                                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                    <?= session()->get('cart_count'); ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Usuário Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php if ($avatar): ?>
                            <img src="<?= esc($avatar) ?>" class="rounded-circle me-2" style="width:32px; height:32px; object-fit:cover;">
                        <?php else: ?>
                            <i class="bi bi-person-circle fs-4 text-light me-2"></i>
                        <?php endif ?>
                        <span><?= esc($user['username']) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="<?= site_url('perfil') ?>">
                                <i class="bi bi-pencil-fill me-1"></i>Editar Perfil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="<?= site_url('logout') ?>">
                                <i class="bi bi-box-arrow-right me-1"></i>Sair
                            </a>
                        </li>
                    </ul>
                </li>
                <?php else: ?>
                    <li class="nav-item me-2">
                        <a class="btn btn-primary" href="<?= site_url('login') ?>">Login</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="btn btn-outline-light" href="<?= site_url('cadastrar') ?>">Cadastrar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
  <div class="alert alert-success text-center mb-0"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
  <div class="alert alert-danger text-center mb-0"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<!-- Banner -->
<div class="bg-success text-white text-center py-4 mb-4">
  <h1 class="mb-1">Bem-vindo à Viva Leve</h1>
  <p class="mb-0">Encontre os melhores produtos aqui!</p>
</div>

<main class="container-fluid px-5">
  <?= $this->renderSection('content') ?>
</main>

<footer class="bg-dark text-white text-center py-3 mt-5">
  <p>Trabalho final - Laisa e Rebeka &copy; <?= date('Y') ?></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
