<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Data Tindakan & Layanan</h4>
    <a href="<?= base_url('master/tindakan/create') ?>" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Tambah Tindakan
    </a>
</div>

<?php if(session()->getFlashdata('success')) : ?>
    <div class="alert alert-success mt-2">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="card" style="max-width: 800px;">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama Tindakan / Layanan</th>
                    <th>Tarif (Rp)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($tindakan)): ?>
                    <tr><td colspan="4" class="text-center">Belum ada data tindakan.</td></tr>
                <?php else: ?>
                    <?php foreach($tindakan as $t): ?>
                        <tr>
                            <td><?= $t['id'] ?></td>
                            <td><strong><?= $t['nama_tindakan'] ?></strong></td>
                            <td>Rp <?= number_format($t['tarif'], 0, ',', '.') ?></td>
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
