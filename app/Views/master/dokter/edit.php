<div class="p-4" id="dokter-edit-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-gradient-primary small-caps"><i class="fas fa-edit me-2"></i>Edit Data Praktisi</h4>
            <div class="text-muted small">Perbarui informasi klinis dan kredensial untuk <b><?= $dokter['nama_dokter'] ?></b>.</div>
        </div>
        <button onclick="openTab('master/dokter', 'Data Dokter')" class="btn btn-light border rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </button>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">
                <form id="form-edit-dokter" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">DATA PEGAWAI TERHUBUNG</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-user-tie text-muted"></i></span>
                                <select name="pegawai_id" class="form-select border-0 bg-light p-2" required>
                                    <?php foreach ($pegawai as $p) : ?>
                                        <option value="<?= $p['id'] ?>" <?= $dokter['pegawai_id'] == $p['id'] ? 'selected' : '' ?>>
                                            <?= $p['nama_pegawai'] ?> (NIK: <?= $p['nik'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">UNIT POLIKLINIK (Bisa pilih banyak)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-clinic-medical text-muted"></i></span>
                                <select name="unit_ids[]" class="form-select border-0 bg-light p-2" multiple required>
                                    <?php foreach ($poliklinik as $p) : ?>
                                        <option value="<?= $p['id'] ?>" <?= in_array($p['id'], $dokter['unit_ids'] ?? []) ? 'selected' : '' ?>>
                                            <?= $p['nama_poli'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">SPESIALISASI</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-certificate text-muted"></i></span>
                                <input type="text" name="specialis" class="form-control border-0 bg-light p-2" value="<?= $dokter['specialis'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">NOMOR SIP</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-id-card text-muted"></i></span>
                                <input type="text" name="sip" class="form-control border-0 bg-light p-2" value="<?= $dokter['sip'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">KODE BPJS (V-CLAIM)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-code text-muted"></i></span>
                                <input type="text" name="kode_bpjs" class="form-control border-0 bg-light p-2" value="<?= $dokter['kode_bpjs'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">IHS PRACTITIONER ID</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-fingerprint text-muted"></i></span>
                                <input type="text" name="ihs_practitioner" class="form-control border-0 bg-light p-2" value="<?= $dokter['ihs_practitioner'] ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
                            <i class="fas fa-save me-2"></i> Update Data Praktisi
                        </button>
                        <button type="button" onclick="hapusDokter(<?= $dokter['id'] ?>, '<?= $dokter['nama_dokter'] ?>')" class="btn btn-soft-danger rounded-pill px-4 py-2">
                            <i class="fas fa-trash-alt me-2"></i> Hapus data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const $container = $('#dokter-edit-container');
    const $form = $container.find('#form-edit-dokter');

    $form.on('submit', function(e) {
        e.preventDefault();
        
        if (this.checkValidity() === false) {
            e.stopPropagation();
            $(this).addClass('was-validated');
            return;
        }

        const formData = $(this).serialize();
        const $btn = $(this).find('button[type="submit"]');
        const originalHtml = $btn.html();
        
        $btn.html('<i class="fas fa-spinner fa-spin me-2"></i> Memperbarui...').attr('disabled', true);
        
        $.post('<?= base_url('master/dokter/update/'.$dokter['id']) ?>', formData, function(res) {
            $btn.html(originalHtml).attr('disabled', false);
            if (res.status === 'success') {
                Swal.fire({
                    title: 'Berhasil!',
                    text: res.message,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    openTab('master/dokter', 'Data Dokter');
                });
            } else {
                Swal.fire('Gagal!', res.message, 'error');
            }
        }).fail(function() {
            $btn.html(originalHtml).attr('disabled', false);
            Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
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
.small-caps { font-variant: small-caps; }
.btn-soft-danger {
    background-color: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: none;
}
.btn-soft-danger:hover {
    background-color: #ef4444;
    color: white;
}
</style>
