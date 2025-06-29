<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container">
    <h2 class="mt-5">Lista de Produtos</h2>
    <div class="d-flex justify-content-between mb-3">
        <a href="<?= site_url('produtos/create') ?>" class="btn btn-success">Novo produto</a>
        <a href="<?= site_url('produtos/relatorio') ?>" class="btn btn-info">Relatório de Vendas</a>
    </div>

    <!-- formulário para filtrar os produtos -->
    <form method="get" action="<?= site_url('produtos') ?>" class="mb-3">
        <div class="row">
            <div class="col-md-5">
                <input type="text" name="filtroNome" value="<?= esc($filtroNome ?? '') ?>" class="form-control" placeholder="Busque por um produto">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="filtroPreco">
                    <option value="">Todos</option>
                    <option value="baixo" <?= esc($preco == 'baixo' ? 'selected' : '') ?>>Abaixo de R$99,00</option>
                    <option value="medio" <?= esc($preco == 'medio' ? 'selected' : '') ?>>Entre R$100,00 e R$499,00</option>
                    <option value="alto" <?= esc($preco == 'alto' ? 'selected' : '') ?>>Acima de R$500,00</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="id_categoria" id="id_categoria" class="form-select">
                    <option value="">Selecione...</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['id'] ?>" <?= old('id_categoria', $filtroCategoria ?? '') == $categoria['id'] ? 'selected' : '' ?>>
                            <?= esc($categoria['nome']) ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </div>
    </form>

    <?php if (! empty($produtos) && is_array($produtos)): ?>
  <div class="table-responsive">
    <table class="table table-hover align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Nome</th>
          <th>Descrição</th>
          <th>Preço</th>
          <th>Categoria</th>
          <th>Estoque</th>
          <th>Status</th>
          <th>Foto</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($produtos as $produto): ?>
          <tr>
            <td><?= esc($produto['id']) ?></td>
            <td><?= esc($produto['nome']) ?></td>
            <td style="max-width:200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
              <?= esc($produto['descricao']) ?>
            </td>
            <td class="text-success fw-bold">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
            <td><?= esc($produto['nome_categoria'] ?? '—') ?></td>
            <td><?= esc($produto['estoque']) ?></td>
            <td>
              <?php if (($produto['status'] ?? '') === 'aprovado'): ?>
                <span class="badge bg-success">Aprovado</span>
              <?php elseif (($produto['status'] ?? '') === 'reprovado'): ?>
                <span class="badge bg-danger">Reprovado</span>
              <?php else: ?>
                <span class="badge bg-secondary">—</span>
              <?php endif ?>
            </td>
            <td>
              <?php if (! empty($produto['foto_capa'])): ?>
                <img src="<?= base_url('uploads/fotos/' . $produto['foto_capa']) ?>"
                     class="rounded shadow-sm"
                     style="width:80px; height:80px; object-fit:cover;"
                     data-bs-toggle="modal"
                     data-bs-target="#modalFoto<?= $produto['id'] ?>">
                <!-- Modal -->
                <div class="modal fade" id="modalFoto<?= $produto['id'] ?>" tabindex="-1">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-body text-center p-0">
                        <img src="<?= base_url('uploads/fotos/' . $produto['foto_capa']) ?>"
                             class="img-fluid rounded">
                      </div>
                    </div>
                  </div>
                </div>
              <?php else: ?>
                <i class="bi bi-image text-muted fs-3"></i>
              <?php endif ?>
            </td>
            <td class="text-nowrap">
              <a href="<?= site_url('produtos/edit/' . $produto['id']) ?>" class="btn btn-sm btn-outline-warning">
                <i class="bi bi-pencil"></i>
              </a>
              <a href="<?= site_url('produtos/delete/' . $produto['id']) ?>"
                 class="btn btn-sm btn-outline-danger"
                 onclick="return confirm('Deseja mesmo excluir este produto?')">
                <i class="bi bi-trash"></i>
              </a>
              <a href="<?= site_url('fotosproduto/' . $produto['id']) ?>" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-camera"></i>
              </a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>

  <div class="d-flex justify-content-center">
    <?= $pager->links('default', 'template_paginator') ?>
  </div>

<?php else: ?>
  <div class="alert alert-info text-center">
    Nenhum produto encontrado
  </div>
<?php endif ?>
ca
</div>

<?= $this->endSection() ?>
