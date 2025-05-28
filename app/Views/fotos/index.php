<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container">
    <h2 class="mt-4">Fotos do produto <?= esc($produtoNome) ?></h2>

    <!-- formulário de upload -->
    <form action="<?= site_url("fotosproduto/upload/$produtoId") ?>" method="post" enctype="multipart/form-data" class="mb-4">
        <div class="mb-3">
            <label for="foto" class="form-label">Selecionar foto:</label>
            <input type="file" name="foto" id="foto" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
        <a href="<?= site_url('produtos') ?>" class="btn btn-secondary">Voltar</a>
    </form>

    <form action="<?= site_url("fotosproduto/uploadAjax/$produtoId") ?>" class="dropzone" id="fotoUploadForm">
    
    </form>

    <!-- lsita de fotos -->
    <?php if (!empty($fotos)): ?>
        <div class="row">
            <?php foreach ($fotos as $foto): ?>
                <div class="col-md-3 text-center mb-4">
                    <div class="card">
                        <img src="<?= base_url('uploads/fotos/' . $foto['caminho']) ?>" class="card-img-top" style="max-height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <?php if ($foto['capa']): ?>
                                <span class="badge bg-success mb-2">Capa</span><br>
                            <?php else: ?>
                                <a href="<?= site_url('fotosproduto/definircapa/' . $foto['id']) ?>" class="btn btn-sm btn-outline-success mb-2">Definir como capa</a><br>
                            <?php endif ?>
                            <a href="<?= site_url('fotosproduto/delete/' . $foto['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir esta foto?')">Excluir</a>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php else: ?>
        <p>Nenhuma foto encontrada</p>
    <?php endif ?>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

<script>
    Dropzone.options.fotoUploadForm = {
        paramName: "foto", 
        maxFilesize: 5, 
        acceptedFiles: 'image/*',
        dictDefaultMessage: "Arraste as imagens aqui ou clique para enviar",
        success: function(file, response) {
            console.log("Enviado com sucesso", response);
        },
        error: function(file, response) {
            console.error("Erro ao enviar", response);
        }
    };
</script>

<?= $this->endSection() ?>
