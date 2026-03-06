<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('master/tindakan') ?>" class="btn btn-link link-secondary p-0 text-decoration-none small">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
    </a>
    <h4 class="mt-2">Tambah Tindakan / Layanan</h4>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="<?= base_url('master/tindakan/store') ?>" method="post">
            <div class="mb-3">
                <label class="form-label">Nama Tindakan / Layanan</label>
                <input type="text" name="nama_tindakan" class="form-control" placeholder="Contoh: Konsultasi Spesialis" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tarif (Rp)</label>
                <input type="number" name="tarif" class="form-control" required>
            </div>
            
            <div class="mt-4 text-end">
                <button type="submit" class="btn btn-primary px-4">Simpan Tindakan</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
