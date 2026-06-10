<?php helper('form'); ?> <?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<?php
if (session()->getFlashData('success')) {
    ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashData('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
}
?>

<div class="row">
    <?php foreach ($products as $key => $item): ?>
        <div class="col-lg-6 mb-4"> <?= form_open('keranjang') ?>
            <?= form_hidden([
                'id' => $item['id'],
                'nama' => $item['nama'],
                'harga' => $item['harga'],
                'foto' => $item['foto']
            ]) ?>

            <div class="card h-100 shadow-sm">
                <div class="card-body pt-4 text-center">

                    <img src="<?= base_url('img/' . $item['foto']) ?>" alt="<?= $item['nama'] ?>" width="50%" class="mb-3">

                    <h5 class="card-title text-start pb-0" style="font-size: 1.1rem; color: #012970;">
                        <?= $item['nama'] ?> <br>
                        <?= number_to_currency($item['harga'], 'IDR') ?>
                    </h5>

                    <div class="text-start mt-2">
                        <button type="submit" class="btn btn-info rounded-pill text-white px-4">Beli</button>
                    </div>

                </div>
            </div>

            <?= form_close() ?>

        </div>
    <?php endforeach ?>
</div>

<?= $this->endSection() ?>