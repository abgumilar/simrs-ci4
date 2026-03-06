<div class="p-4" id="pasien-edit-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-gradient-primary small-caps"><i class="fas fa-user-edit me-2"></i><?= $title ?></h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mt-1 mb-0 small">
                    <li class="breadcrumb-item"><a href="javascript:void(0)" onclick="openTab('master/pasien', 'Data Master Pasien')" class="text-decoration-none text-muted">Data Pasien</a></li>
                    <li class="breadcrumb-item active text-primary" aria-current="page">Update Data: <?= $pasien['norm'] ?></li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button onclick="hapusPasien(<?= $pasien['id'] ?>, '<?= $pasien['nama_pasien'] ?>')" class="btn btn-soft-danger rounded-pill px-4 shadow-sm">
                <i class="fas fa-trash-alt me-2"></i> Hapus
            </button>
            <button onclick="openTab('master/pasien', 'Data Master Pasien')" class="btn btn-light border rounded-pill px-4 shadow-sm">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </button>
        </div>
    </div>

    <form id="form-edit-pasien" class="needs-validation" novalidate>
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
                                <input type="text" name="nik" class="form-control border-0 bg-light p-2" value="<?= $pasien['nik'] ?>" placeholder="16 Digit NIK" required maxlength="16">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">NOMOR JKN / BPJS</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-credit-card text-muted"></i></span>
                                <input type="text" name="no_jkn" class="form-control border-0 bg-light p-2" value="<?= $pasien['no_jkn'] ?>" placeholder="No. Kartu BPJS (Jika ada)">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted">ID SATUSEHAT (IHS NUMBER)</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-fingerprint text-muted"></i></span>
                                <input type="text" name="ihs_number" id="ihs_number" class="form-control border-0 bg-light p-2 fw-bold" value="<?= $pasien['ihs_number'] ?>" placeholder="Otomatis dari SatuSehat" readonly>
                                <button type="button" onclick="cekIHS(<?= $pasien['id'] ?>)" class="btn btn-primary px-3 shadow-none border-0">
                                    <i class="fas fa-sync-alt me-1"></i> Cek SatuSehat
                                </button>
                            </div>
                            <small class="text-muted" style="font-size: 11px;">* Klik "Cek SatuSehat" untuk mencari ID pasien berdasarkan NIK di server Kemenkes.</small>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted">NAMA LENGKAP PASIEN (SESUAI KTP)</label>
                            <input type="text" name="nama_pasien" class="form-control border-0 bg-light p-2 fw-bold text-uppercase" value="<?= $pasien['nama_pasien'] ?>" placeholder="Contoh: BUDI SANTOSO" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">TEMPAT LAHIR</label>
                            <input type="text" name="tempat_lahir" class="form-control border-0 bg-light p-2" value="<?= $pasien['tempat_lahir'] ?>" placeholder="Kota/Kabupaten" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">TANGGAL LAHIR</label>
                            <input type="date" name="tgl_lahir" class="form-control border-0 bg-light p-2" value="<?= $pasien['tgl_lahir'] ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">JENIS KELAMIN</label>
                            <select name="jenis_kelamin" class="form-select border-0 bg-light p-2" required>
                                <option value="L" <?= $pasien['jenis_kelamin'] == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= $pasien['jenis_kelamin'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">AGAMA</label>
                            <select name="agama" class="form-select border-0 bg-light p-2">
                                <?php foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Lainnya'] as $agm): ?>
                                    <option value="<?= $agm ?>" <?= $pasien['agama'] == $agm ? 'selected' : '' ?>><?= $agm ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">STATUS MARITAL</label>
                            <select name="status_perkawinan" class="form-select border-0 bg-light p-2">
                                <?php foreach(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'] as $stat): ?>
                                    <option value="<?= $stat ?>" <?= $pasien['status_perkawinan'] == $stat ? 'selected' : '' ?>><?= $stat ?></option>
                                <?php endforeach; ?>
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
                            <textarea name="alamat" class="form-control border-0 bg-light p-2" rows="2" placeholder="Nama Jalan, No. Rumah..." required><?= $pasien['alamat'] ?></textarea>
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-bold small text-muted">RT</label>
                            <input type="text" name="rt" class="form-control border-0 bg-light p-2" value="<?= $pasien['rt'] ?>" placeholder="000">
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label fw-bold small text-muted">RW</label>
                            <input type="text" name="rw" class="form-control border-0 bg-light p-2" value="<?= $pasien['rw'] ?>" placeholder="000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">KELURAHAN/DESA</label>
                            <input type="text" name="kelurahan" class="form-control border-0 bg-light p-2" value="<?= $pasien['kelurahan'] ?>" placeholder="Kelurahan">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">KECAMATAN</label>
                            <input type="text" name="kecamatan" class="form-control border-0 bg-light p-2" value="<?= $pasien['kecamatan'] ?>" placeholder="Kecamatan">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">KOTA/KABUPATEN</label>
                            <input type="text" name="kota" class="form-control border-0 bg-light p-2" value="<?= $pasien['kota'] ?>" placeholder="Kota/Kab">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">PROVINSI</label>
                            <input type="text" name="provinsi" class="form-control border-0 bg-light p-2" value="<?= $pasien['provinsi'] ?>" placeholder="Provinsi">
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
                            <input type="text" name="no_telp" class="form-control border-0 bg-light p-2" value="<?= $pasien['no_telp'] ?>" placeholder="08xxxxxxxx" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">EMAIL (OPSIONAL)</label>
                        <input type="email" name="email" class="form-control border-0 bg-light p-2" value="<?= $pasien['email'] ?>" placeholder="email@contoh.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">PEKERJAAN</label>
                        <select name="pekerjaan" class="form-select border-0 bg-light p-2">
                            <?php foreach(['Tidak Bekerja', 'PNS', 'Swasta', 'Wiraswasta', 'Petani/Buruh', 'Pelajar', 'Lainnya'] as $pek): ?>
                                <option value="<?= $pek ?>" <?= $pasien['pekerjaan'] == $pek ? 'selected' : '' ?>><?= $pek ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">PENDIDIKAN TERAKHIR</label>
                        <select name="pendidikan" class="form-select border-0 bg-light p-2">
                            <?php foreach(['Tidak Sekolah', 'SD', 'SMP', 'SMA', 'Diploma', 'Sarjana'] as $pend): ?>
                                <option value="<?= $pend ?>" <?= $pasien['pendidikan'] == $pend ? 'selected' : '' ?>><?= $pend ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">KEWARGANEGARAAN</label>
                        <select name="kewarganegaraan" class="form-select border-0 bg-light p-2">
                            <option value="WNI" <?= $pasien['kewarganegaraan'] == 'WNI' ? 'selected' : '' ?>>Warga Negara Indonesia (WNI)</option>
                            <option value="WNA" <?= $pasien['kewarganegaraan'] == 'WNA' ? 'selected' : '' ?>>Warga Negara Asing (WNA)</option>
                        </select>
                    </div>

                    <div class="mt-auto pt-4">
                        <button type="submit" class="btn btn-warning w-100 rounded-pill py-3 shadow-warning mb-2 text-white">
                            <i class="fas fa-save me-2"></i> Update Data Pasien
                        </button>
                        <button type="button" onclick="openTab('master/pasien', 'Data Master Pasien')" class="btn btn-light w-100 rounded-pill py-3 border">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    const $container = $('#pasien-edit-container');
    const $form = $container.find('#form-edit-pasien');

    $form.on('submit', function(e) {
        e.preventDefault();
        
        if (this.checkValidity() === false) {
            e.stopPropagation();
            $(this).addClass('was-validated');
            return;
        }

        const formData = $(this).serialize();
        
        Swal.fire({
            title: 'Simpan Perubahan?',
            text: "Data pasien akan diperbarui.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Update',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url('master/pasien/update/' . $pasien['id']) ?>', formData, function(res) {
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

    window.cekIHS = function(id) {
        Swal.fire({
            title: 'Mencari IHS...',
            text: 'Menghubungi server SatuSehat Kemenkes',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.get('<?= base_url('master/pasien/get_ihs/') ?>/' + id, function(res) {
            if (res.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'IHS Ditemukan!',
                    text: res.message
                });
                $container.find('#ihs_number').val(res.ihs);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: res.message
                });
            }
        });
    }

    window.hapusPasien = function(id, nama) {
        Swal.fire({
            title: 'Hapus Pasien?',
            html: `Anda yakin ingin menghapus data <b>${nama}</b>?<br><small class="text-danger">Tindakan ini tidak dapat dibatalkan!</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url('master/pasien/delete/') ?>/' + id, {}, function(res) {
                    if (res.status === 'success') {
                        Swal.fire('Terhapus!', res.message, 'success');
                        openTab('master/pasien', 'Data Master Pasien');
                    } else {
                        Swal.fire('Gagal!', res.message, 'error');
                    }
                });
            }
        });
    }
});
</script>

<style>
.text-gradient-primary {
    background: linear-gradient(45deg, #00a651, #8b5cf6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.small-caps { font-variant: small-caps; }
.shadow-warning {
    box-shadow: 0 10px 15px -3px rgba(245, 158, 11, 0.3), 0 4px 6px -4px rgba(245, 158, 11, 0.3);
}
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
