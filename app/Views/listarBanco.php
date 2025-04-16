<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div>
    
    <?php if(!empty($mensagens)): ?>
        <?php foreach ($mensagens as $linha) : ?>
                <p>Nome:<?= esc($linha->nome) ?></p>
                <p>Email: <?= esc($linha->email) ?></p>
                <p>Mensagem: <?= esc($linha->mensagem) ?></p>
                <p>Data inserção: <?= esc($linha->created_at) ?></p>
                <hr>

        <?php endforeach ?>
    <?php else : ?>
        <p>Não há registros de contatos ainda.</p>
    <?php endif ?>
</div>


<?= $this->endSection() ?>