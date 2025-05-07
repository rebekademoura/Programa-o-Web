<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="container">
        <h2 class="mt-5">Lista de Categorias</h2>
        <a href="<?=site_url("categorias/create") ?>" class="btn btn-success mb-3 container-fluid">Nova categoria</a>

            

            <!--tabela para listar as categorias-->
            <table class="table table-bordered ">
                <thead class="thead-dark">
                    <tr>
                        <td> Id </td>
                        <td> Nome </td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($categorias as $categoria): ?>
                        <tr>
                            <td> <?=$categoria['id'];?> </td>
                            <td> <?=$categoria['nome'];?> </td>
                            <td>
                                <a href="<?=site_url("categorias/edit/".$categoria['id']) ?>" class="btn btn-warning">Editar</a>
                                <a href="<?=site_url("categorias/delete/".$categoria['id']) ?>" class="btn btn-danger"
                                   onclick="return confirm('Deseja mesmo excluir esta categoria?')"
                                >Excluir</a>

                            </td>
                        </tr>    
                    <?php endforeach; ?>
                </tbody>
            </table>
    </div>

<?= $this->endSection() ?>