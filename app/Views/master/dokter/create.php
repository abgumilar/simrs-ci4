<div class="p-4" id="dokter-create-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-gradient-primary small-caps"><i class="fas fa-plus-circle me-2"></i>Tambah Praktisi Baru</h4>
            <div class="text-muted small">Daftarkan dokter atau tenaga medis baru ke dalam database rumah sakit.</div>
        </div>
        <button onclick="openTab('master/dokter', 'Data Dokter')" class="btn btn-light border rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </button>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">
                <form id="form-tambah-dokter" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">PILIH PEGAWAI</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-user-tie text-muted"></i></span>
                                <select name="pegawai_id" id="pegawai_id" class="form-select border-0 bg-light p-2" required>
                                    <option value="">-- Pilih Pegawai --</option>
                                    <option value="new" class="fw-bold text-primary">+ Tambah Pegawai Baru</option>
                                    <?php foreach ($pegawai as $p) : ?>
                                        <option value="<?= $p['id'] ?>"><?= $p['nama_pegawai'] ?> (NIK: <?= $p['nik'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Fields for New Employee (Hidden by Default) -->
                        <div id="new-employee-fields" style="display: none;" class="col-12">
                            <div class="p-3 rounded-4 bg-soft-primary border border-primary border-opacity-10">
                                <h6 class="fw-bold small mb-3 text-primary"><i class="fas fa-id-card me-2"></i>Data Pegawai Baru</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">NAMA LENGKAP</label>
                                        <input type="text" name="nama_baru" id="nama_baru" class="form-control border-0 bg-white" placeholder="Nama tanpa gelar">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">NIK (16 Digit)</label>
                                        <input type="text" name="nik_baru" id="nik_baru" class="form-control border-0 bg-white" placeholder="Nomor Induk Kependudukan">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">NO. WHATSAPP</label>
                                        <input type="text" name="hp_baru" id="hp_baru" class="form-control border-0 bg-white" placeholder="0812xxxx">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">UNIT POLIKLINIK (Bisa pilih banyak)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-clinic-medical text-muted"></i></span>
                                <select name="unit_ids[]" class="form-select border-0 bg-light p-2" multiple required>
                                    <?php foreach ($poliklinik as $p) : ?>
                                        <option value="<?= $p['id'] ?>"><?= $p['nama_poli'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">SPESIALISASI</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-certificate text-muted"></i></span>
                                <input type="text" name="specialis" class="form-control border-0 bg-light p-2" placeholder="Contoh: Spesialis Jantung" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">NOMOR SIP</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-id-card text-muted"></i></span>
                                <input type="text" name="sip" class="form-control border-0 bg-light p-2" placeholder="Nomor Surat Izin Praktik" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">KODE BPJS (V-CLAIM)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-code text-muted"></i></span>
                                <input type="text" name="kode_bpjs" class="form-control border-0 bg-light p-2" placeholder="Kode Dokter BPJS">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">IHS PRACTITIONER ID</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-fingerprint text-muted"></i></span>
                                <input type="text" name="ihs_practitioner" class="form-control border-0 bg-light p-2" placeholder="SatuSehat Practitioner ID">
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
                            <i class="fas fa-save me-2"></i> Simpan Praktisi
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
                <h6 class="fw-bold"><i class="fas fa-info-circle me-2"></i>Informasi Praktisi</h6>
                <p class="small text-muted">Data praktisi digunakan untuk:</p>
                <ul class="small mb-0 ps-3">
                    <li class="mb-2"><b>Penempatan Poli:</b> Menentukan dokter muncul di poli mana saat pendaftaran.</li>
                    <li class="mb-2"><b>Bridging BPJS:</b> Menggunakan Kode BPJS untuk pembuatan SEP.</li>
                    <li><b>SatuSehat:</b> Practitioner ID digunakan untuk pelaporan Encounter/EMR ke Kemenkes.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const $container = $('#dokter-create-container');
    const $form = $container.find('#form-tambah-dokter');

    $container.find('#pegawai_id').on('change', function() {
        if ($(this).val() === 'new') {
            $container.find('#new-employee-fields').slideDown();
            $container.find('#nama_baru, #nik_baru').attr('required', true);
        } else {
            $container.find('#new-employee-fields').slideUp();
            $container.find('#nama_baru, #nik_baru').removeAttr('required');
        }
    });

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
        
        $btn.html('<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...').attr('disabled', true);
        
        $.post('<?= base_url('master/dokter/store') ?>', formData, function(res) {
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
.bg-soft-primary { background-color: rgba(0, 166, 81, 0.05); }
</style>
