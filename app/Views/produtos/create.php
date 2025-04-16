<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

    <div class="container">
        <h2 class="mt-5">Cadastrar Produto</h2>
        <form action="<?=site_url('produtos/store')?>" method="POST">
            <label for="nome" id="nome" class="form-label">Nome: </label>
            <input type="text" id="nome" name="nome" require class="form-control">

            <label for="descricao" id="descricao" class="form-label">Descrição: </label>
            <input type="text" id="descricao" name="descricao" require class="form-control">

            <label for="preco" id="preco" class="form-label">Preço: </label>
            <input type="number" step="0.1" id="preco" name="preco" require class="form-control">

            <button type="submit" class="btn btn-success mt-3">Cadastrar produto</button>
            <a href="" class="btn btn-warning mt-3">Cancelar</a>
        </form>
    

    </div>
<?= $this->endSection() ?>