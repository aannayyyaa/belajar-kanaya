<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm">
    <div class="card-body pt-4">

        <?php if (session()->getFlashData('success')): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?= session()->getFlashData('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashData('failed')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashData('failed') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="mb-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                Tambah Data
            </button>
            <a class="btn btn-success" target="_blank" href="<?= base_url('produk/download') ?>">
                Download Data
            </a>
        </div>

        <div class="table-responsive">
            <table class="table datatable table-hover">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Foto</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $index => $produk): ?>
                        <tr>
                            <th scope="row"><?= $index + 1 ?></th>
                            <td><?= $produk['nama'] ?></td>
                            <td><?= $produk['harga'] ?></td>
                            <td><?= $produk['jumlah'] ?></td>
                            <td>
                                <?php if ($produk['foto'] != '' && file_exists("img/" . $produk['foto'])): ?>
                                    <img src="<?= base_url('img/' . $produk['foto']) ?>" width="100" class="rounded">
                                <?php else: ?>
                                    <span class="text-muted">Tidak ada foto</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editModal-<?= $produk['id'] ?>">
                                    Ubah
                                </button>
                                <a href="<?= base_url('produk/delete/' . $produk['id']) ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin hapus data ini?')">
                                    Hapus
                                    </button>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div> <?= $this->include('produk/modal_add') ?>
<?= $this->include('produk/modal_edit') ?>

<?= $this->endSection() ?>