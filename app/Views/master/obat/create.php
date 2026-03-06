<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <a href="<?= base_url('master/obat') ?>" class="btn btn-link link-secondary p-0 text-decoration-none small">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
    </a>
    <h4 class="mt-2">Tambah Obat & Alkes</h4>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-body">
        <form action="<?= base_url('master/obat/store') ?>" method="post">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Kode Obat</label>
                    <input type="text" name="kode_obat" class="form-control" placeholder="Contoh: OB001" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Nama Obat / Alkes</label>
                    <input type="text" name="nama_obat" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="satuan" class="form-control" placeholder="Strip/Tablet/Botol" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Harga Jual (Rp)</label>
                    <input type="number" name="harga_jual" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stok Awal</label>
                    <input type="number" name="stok" class="form-control" value="0" required>
                </div>
                
                <div class="col-12 mt-4 text-end">
                    <button type="submit" class="btn btn-primary px-4">Simpan Obat</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
