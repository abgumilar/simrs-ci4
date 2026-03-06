<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Data Obat & Alkes</h4>
    <a href="<?= base_url('master/obat/create') ?>" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Tambah Obat
    </a>
</div>

<?php if(session()->getFlashdata('success')) : ?>
    <div class="alert alert-success mt-2">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Kode</th>
                    <th>Nama Obat</th>
                    <th>Satuan</th>
                    <th>Harga Jual</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($obat)): ?>
                    <tr><td colspan="6" class="text-center">Belum ada data obat.</td></tr>
                <?php else: ?>
                    <?php foreach($obat as $o): ?>
                        <tr>
                            <td><code><?= $o['kode_obat'] ?></code></td>
                            <td><strong><?= $o['nama_obat'] ?></strong></td>
                            <td><?= $o['satuan'] ?></td>
                            <td>Rp <?= number_format($o['harga_jual'], 0, ',', '.') ?></td>
                            <td><?= $o['stok'] ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
