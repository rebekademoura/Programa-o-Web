<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="container">
        <h2 class="mt-5">Lista de Produtos</h2>
        <a href="<?=site_url("produtos/create") ?>" class="btn btn-success mb-3 container-fluid">Novo produto</a>

        <!--formulário de para filtrar os produtos-->
        <form method="get" action="<?=site_url("produtos") ?>" class="mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" name="filtroNome" value="<?=esc($filtroNome??'')?>" class="form-control" placeholder="Busque por um produto">
                    </div>

                    <div class="col-md-5">
                        <select class="form-select" name="filtroPreco">
                            <option>Todos</option>
                            <option value="baixo" <?=esc($preco=='baixo'?'selected':'')?>>Abaixo de R$99,00</option>
                            <option value="medio" <?=esc($preco=='medio'?'selected':'')?>>Entre R$100,00 e R$499,00</option>
                            <option value="alto" <?=esc($preco=='alto'?'selected':'')?>>Acima de R$500,00</option>
                        </select>
                    </div>
                    

                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary">Buscar</button>    
                    </div>
                </div>
            </form>

        <?php if(!empty($produtos) && is_array($produtos)):?>

            

            <!--tabela para listar os produtos-->
            <table class="table table-bordered ">
                <thead class="thead-dark">
                    <tr>
                        <td> Id </td>
                        <td> Nome </td>
                        <td> descrição </td>
                        <td> Preço </td>
                        <td> Categoria </td>
                        <td> Ações </td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($produtos as $produto): ?>
                        <tr>
                            <td> <?=$produto['id'];?> </td>
                            <td> <?=$produto['nome'];?> </td>
                            <td> <?=$produto['descricao'];?> </td>
                            <td> <?=$produto['preco'];?> </td>
                            <td><?= esc($produto['nome_categoria'] ?? 'Sem categoria') ?></td>
                            <td>
                                <a href="<?=site_url("produtos/edit/".$produto['id']) ?>" class="btn btn-warning">Editar</a>
                                <a href="<?=site_url("produtos/delete/".$produto['id']) ?>" class="btn btn-danger"
                                   onclick="return confirm('Deseja mesmo excluir este produto?')"
                                >Excluir</a>

                            </td>
                        </tr>    
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?=$pager->links('default', 'template_paginator'); ?>


        <?php else: ?>
            <p>Nenhum produto encontrado</p>
        <?php endif ?>
                
    

    </div>

<?= $this->endSection() ?>