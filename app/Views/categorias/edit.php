<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

    <div class="container">
        <h2 class="mt-5">Editar Categoria</h2>
        <form action="<?=site_url('categorias/update/'.$categorias['id'])?>" method="POST">
            <label for="nome" id="nome" class="form-label">Nome: </label>
            <input type="text" id="nome" name="nome" require class="form-control" value="<?=$categorias['nome']?>">

            <button type="submit" class="btn btn-success mt-3">Salvar alteração</button>
            <a href="" class="btn btn-warning mt-3">Cancelar</a>
        </form>
    

    </div>
<?= $this->endSection() ?>