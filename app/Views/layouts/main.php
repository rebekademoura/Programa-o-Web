<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Controle seus produtos' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=psychiatry" />
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand ms-5" href="produtos/index"><span class="material-symbols-outlined">
psychiatry
</span>  Viva Leve</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <?php if(session()->get('logado')):?>
                    <li class="nav-item text-light">Bem vindo, <?=esc(session()->get('usuario')['username']); ?>!</li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo site_url("./perfil"); ?>">Editar Perfil</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo site_url("./categorias"); ?>">Ver categorias</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo site_url("./produtos"); ?>">Ver produtos</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo site_url("./logout"); ?>">Sair</a></li>

                <?php else: ?>
                <li class="nav-item btn btn-primary "><a class="nav-link" href="<?php echo site_url("./cadastrar"); ?>">Cadastrar</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo site_url("./sobre"); ?>">Sobre</a></li>
                <li class="nav-item"><a class="nav-link" href="/contact">Contato</a></li>
                <?php endif?>

            </ul>
        </div>
    </nav>

                    <!-- Mensagens -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                    <?php endif; ?>

    <div class="container mt-4">
        
        <?= $this->renderSection('content') ?>
    
    </div>
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p>Minha Aplicação &copy; <?= date('Y') ?></p>
    </footer>

                    


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>