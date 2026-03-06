<div class="p-4" id="pasien-create-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-gradient-primary small-caps"><i class="fas fa-user-plus me-2"></i><?= $title ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-1 mb-0 small">
                    <li class="breadcrumb-item"><a href="javascript:void(0)" onclick="openTab('master/pasien', 'Data Master Pasien')" class="text-decoration-none text-muted">Data Pasien</a></li>
                    <li class="breadcrumb-item active" aria-current="page text-primary">Registrasi Baru</li>
                </ol>
            </nav>
        </div>
        <button onclick="openTab('master/pasien', 'Data Master Pasien')" class="btn btn-light border rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </button>
    </div>

    <form id="form-tambah-pasien" class="needs-validation" novalidate>
        <div class="row g-4">
            <!-- Data Identitas -->
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm p-4" style="border-radius: 16px;">
                    <h6 class="fw-bold mb-4 text-primary border-bottom pb-2"><i class="fas fa-id-card me-2"></i>Identitas Utama</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">NIK (NOMOR INDUK KEPENDUDUKAN)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-fingerprint text-muted"></i></span>
                                <input type="text" name="nik" class="form-control border-0 bg-light p-2" placeholder="16 Digit NIK" required maxlength="16">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">NOMOR JKN / BPJS</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-credit-card text-muted"></i></span>
                                <input type="text" name="no_jkn" class="form-control border-0 bg-light p-2" placeholder="No. Kartu BPJS (Jika ada)">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted">NAMA LENGKAP PASIEN (SESUAI KTP)</label>
                            <input type="text" name="nama_pasien" class="form-control border-0 bg-light p-2 fw-bold text-uppercase" placeholder="Contoh: BUDI SANTOSO" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">TEMPAT LAHIR</label>
                            <input type="text" name="tempat_lahir" class="form-control border-0 bg-light p-2" placeholder="Kota/Kabupaten" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">TANGGAL LAHIR</label>
                            <input type="date" name="tgl_lahir" class="form-control border-0 bg-light p-2" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">JENIS KELAMIN</label>
                            <select name="jenis_kelamin" class="form-select border-0 bg-light p-2" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">AGAMA</label>
                            <select name="agama" class="form-select border-0 bg-light p-2">
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Budha">Budha</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">STATUS MARITAL</label>
                            <select name="status_perkawinan" class="form-select border-0 bg-light p-2">
                                <option value="Belum Kawin">Belum Kawin</option>
                                <option value="Kawin">Kawin</option>
                                <option value="Cerai Hidup">Cerai Hidup</option>
                                <option value="Cerai Mati">Cerai Mati</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Data Alamat -->
                <div class="card border-0 shadow-sm p-4 mt-4" style="border-radius: 16px;">
                    <h6 class="fw-bold mb-4 text-primary border-bottom pb-2"><i class="fas fa-map-marked-alt me-2"></i>Alamat & Domisili</h6>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted">ALAMAT JALAN / DUSUN / BLOK</label>
                            <textarea name="alamat" class="form-control border-0 bg-light p-2" rows="2" placeholder="Nama Jalan, No. Rumah..." required></textarea>
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-bold small text-muted">RT</label>
                            <input type="text" name="rt" class="form-control border-0 bg-light p-2" placeholder="000">
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-bold small text-muted">RW</label>
                            <input type="text" name="rw" class="form-control border-0 bg-light p-2" placeholder="000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">KELURAHAN/DESA</label>
                            <input type="text" name="kelurahan" class="form-control border-0 bg-light p-2" placeholder="Kelurahan">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">KECAMATAN</label>
                            <input type="text" name="kecamatan" class="form-control border-0 bg-light p-2" placeholder="Kecamatan">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">KOTA/KABUPATEN</label>
                            <input type="text" name="kota" class="form-control border-0 bg-light p-2" placeholder="Kota/Kab">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">PROVINSI</label>
                            <input type="text" name="provinsi" class="form-control border-0 bg-light p-2" placeholder="Provinsi">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Tambahan -->
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 16px;">
                    <h6 class="fw-bold mb-4 text-primary border-bottom pb-2"><i class="fas fa-info-circle me-2"></i>Informasi Kontak & Sosial</h6>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">NOMOR TELEPON / WA</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="fas fa-phone text-muted"></i></span>
                            <input type="text" name="no_telp" class="form-control border-0 bg-light p-2" placeholder="08xxxxxxxx" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">EMAIL (OPSIONAL)</label>
                        <input type="email" name="email" class="form-control border-0 bg-light p-2" placeholder="email@contoh.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">PEKERJAAN</label>
                        <select name="pekerjaan" class="form-select border-0 bg-light p-2">
                            <option value="Tidak Bekerja">Tidak Bekerja</option>
                            <option value="PNS">PNS / TNI / POLRI</option>
                            <option value="Swasta">Karyawan Swasta</option>
                            <option value="Wiraswasta">Wiraswasta</option>
                            <option value="Petani/Buruh">Petani / Buruh</option>
                            <option value="Pelajar">Pelajar / Mahasiswa</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">PENDIDIKAN TERAKHIR</label>
                        <select name="pendidikan" class="form-select border-0 bg-light p-2">
                            <option value="Tidak Sekolah">Tidak Sekolah</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA / SMK</option>
                            <option value="Diploma">Diploma (D1-D4)</option>
                            <option value="Sarjana">Sarjana (S1-S3)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">KEWARGANEGARAAN</label>
                        <select name="kewarganegaraan" class="form-select border-0 bg-light p-2">
                            <option value="WNI" selected>Warga Negara Indonesia (WNI)</option>
                            <option value="WNA">Warga Negara Asing (WNA)</option>
                        </select>
                    </div>

                    <div class="mt-auto pt-4">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 shadow-primary mb-2">
                            <i class="fas fa-save me-2"></i> Simpan Pasien Baru
                        </button>
                        <button type="reset" class="btn btn-light w-100 rounded-pill py-3 border">
                            <i class="fas fa-undo me-2"></i> Reset Form
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    const $container = $('#pasien-create-container');
    const $form = $container.find('#form-tambah-pasien');

    $form.on('submit', function(e) {
        e.preventDefault();
        
        if (this.checkValidity() === false) {
            e.stopPropagation();
            $(this).addClass('was-validated');
            return;
        }

        const formData = $(this).serialize();
        
        Swal.fire({
            title: 'Simpan Data Pasien?',
            text: "Pastikan semua data sudah benar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#00a651',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url('master/pasien/store') ?>', formData, function(res) {
                    if (res.status === 'success') {
                        Swal.fire('Berhasil!', res.message, 'success');
                        openTab('master/pasien', 'Data Master Pasien');
                    } else {
                        Swal.fire('Gagal!', res.message, 'error');
                    }
                });
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
.small-caps { font-variant: small-caps; }
.shadow-primary {
    box-shadow: 0 10px 15px -3px rgba(0, 166, 81, 0.3), 0 4px 6px -4px rgba(0, 166, 81, 0.3);
}
</style>
