<?php helper('form'); ?>

<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm">
    <div class="card-body pt-4">

        <?php if (session()->getFlashData('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashData('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?= form_open('keranjang/update') ?>

        <div class="table-responsive">
            <table class="table datatable table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Nama</th>
                        <th scope="col" class="text-center">Foto</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Subtotal</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $grand_total = 0; ?>

                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>

                            <?php $grand_total += $item['subtotal']; ?>

                            <tr>
                                <td><?= $item['name'] ?></td>

                                <td class="text-center">
                                    <?php $foto = isset($item['options']['foto']) ? $item['options']['foto'] : ''; ?>
                                    <?php if ($foto != ''): ?>
                                        <img src="<?= base_url('img/' . $foto) ?>" alt="<?= $item['name'] ?>" width="80"
                                            class="rounded">
                                    <?php else: ?>
                                        <span class="text-muted">Tidak ada foto</span>
                                    <?php endif; ?>
                                </td>

                                <td>IDR <?= number_format($item['price'], 0, ',', ',') ?></td>

                                <td style="width: 100px;">
                                    <input type="number" name="qty[<?= $item['rowid'] ?>]" value="<?= $item['qty'] ?>"
                                        class="form-control text-center" min="1">
                                </td>

                                <td>IDR <?= number_format($item['subtotal'], 0, ',', ',') ?></td>

                                <td class="text-center">
                                    <a href="<?= base_url('keranjang/delete/' . $item['rowid']) ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus produk ini dari keranjang?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No entries found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="alert alert-info mt-3 mb-4">
            Total = IDR <?= number_format($grand_total, 0, ',', ',') ?>
        </div>

        <div>
            <button type="submit" class="btn btn-primary">Perbarui Keranjang</button>
            <a href="<?= base_url('keranjang/clear') ?>" class="btn btn-warning text-dark"
                onclick="return confirm('Yakin ingin mengosongkan semua isi keranjang?')">
                Kosongkan Keranjang
            </a>
        </div>

        <?= form_close() ?>

    </div>
</div>

<?= $this->endSection() ?>