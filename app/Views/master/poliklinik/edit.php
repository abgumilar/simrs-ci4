<div class="p-4" id="poli-edit-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-gradient-primary"><i class="fas fa-edit me-2"></i>Edit Poliklinik</h4>
            <div class="text-muted small">Perbarui data unit layanan poliklinik <b><?= $poli['nama_poli'] ?></b>.</div>
        </div>
        <button onclick="openTab('master/poliklinik', 'Data Poliklinik')" class="btn btn-light border rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </button>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">
                <form id="form-edit-poli" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted">NAMA POLIKLINIK / UNIT</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-hospital text-muted"></i></span>
                                <input type="text" name="nama_poli" class="form-control border-0 bg-light p-2" value="<?= $poli['nama_poli'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted">LOKASI / GEDUNG</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                <input type="text" name="lokasi" class="form-control border-0 bg-light p-2" value="<?= $poli['lokasi'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">KODE BPJS (HFIS)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-id-card text-muted"></i></span>
                                <input type="text" name="kode_bpjs" class="form-control border-0 bg-light p-2" value="<?= $poli['kode_bpjs'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">IHS LOCATION ID</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-fingerprint text-muted"></i></span>
                                <input type="text" name="ihs_location" class="form-control border-0 bg-light p-2" value="<?= $poli['ihs_location'] ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
                        </button>
                        <button type="button" onclick="hapusPoli(<?= $poli['id'] ?>, '<?= $poli['nama_poli'] ?>')" class="btn btn-soft-danger rounded-pill px-4 py-2">
                            <i class="fas fa-trash-alt me-2"></i> Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const $container = $('#poli-edit-container');
    const $form = $container.find('#form-edit-poli');

    $form.on('submit', function(e) {
        e.preventDefault();
        
        if (this.checkValidity() === false) {
            e.stopPropagation();
            $(this).addClass('was-validated');
            return;
        }

        const formData = $(this).serialize();
        
        $.post('<?= base_url('master/poliklinik/update/'.$poli['id']) ?>', formData, function(res) {
            if (res.status === 'success') {
                Swal.fire('Berhasil!', res.message, 'success');
                openTab('master/poliklinik', 'Data Poliklinik');
            } else {
                Swal.fire('Gagal!', res.message, 'error');
            }
        });
    });
});
</script>

<style>
.text-gradient-primary {
    background: linear-gradient(45deg, #00a651, #8b5cf6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.btn-soft-danger {
    background-color: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: none;
}
.btn-soft-danger:hover {
    background-color: #ef4444;
    color: #fff;
}
</style>
