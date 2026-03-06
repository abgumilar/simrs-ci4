<div class="p-4" id="poli-create-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-gradient-primary"><i class="fas fa-plus-circle me-2"></i>Tambah Poliklinik</h4>
            <div class="text-muted small">Daftarkan unit layanan poliklinik baru ke sistem.</div>
        </div>
        <button onclick="openTab('master/poliklinik', 'Data Poliklinik')" class="btn btn-light border rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </button>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">
                <form id="form-tambah-poli" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted">NAMA POLIKLINIK / UNIT</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-hospital text-muted"></i></span>
                                <input type="text" name="nama_poli" class="form-control border-0 bg-light p-2" placeholder="Contoh: Poli Umum, Poli Gigi" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted">LOKASI / GEDUNG</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                <input type="text" name="lokasi" class="form-control border-0 bg-light p-2" placeholder="Contoh: Gedung A Lt. 1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">KODE BPJS (HFIS)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-id-card text-muted"></i></span>
                                <input type="text" name="kode_bpjs" class="form-control border-0 bg-light p-2" placeholder="Contoh: 001">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">IHS LOCATION ID</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-fingerprint text-muted"></i></span>
                                <input type="text" name="ihs_location" class="form-control border-0 bg-light p-2" placeholder="SatuSehat Location ID">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
                            <i class="fas fa-save me-2"></i> Simpan Poliklinik
                        </button>
                        <button type="reset" class="btn btn-light border rounded-pill px-4 py-2">
                            Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-5">
            <div class="alert alert-info border-0 shadow-sm rounded-4 p-4">
                <h6 class="fw-bold"><i class="fas fa-info-circle me-2"></i>Petunjuk Pengisian</h6>
                <ul class="small mb-0 ps-3">
                    <li class="mb-2"><b>Nama Poliklinik:</b> Gunakan nama resmi instalasi atau unit layanan.</li>
                    <li class="mb-2"><b>Lokasi:</b> Informasi detail untuk membantu pasien menemukan ruangan.</li>
                    <li class="mb-2"><b>Kode BPJS:</b> Wajib diisi jika poliklinik melayani pasien JKN untuk keperluan bridging V-Claim.</li>
                    <li><b>IHS Location ID:</b> Merupakan ID lokasi yang terdaftar di portal SatuSehat Kemenkes.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const $container = $('#poli-create-container');
    const $form = $container.find('#form-tambah-poli');

    $form.on('submit', function(e) {
        e.preventDefault();
        
        if (this.checkValidity() === false) {
            e.stopPropagation();
            $(this).addClass('was-validated');
            return;
        }

        const formData = $(this).serialize();
        
        $.post('<?= base_url('master/poliklinik/store') ?>', formData, function(res) {
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
</style>
