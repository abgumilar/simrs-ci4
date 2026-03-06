<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="javascript:void(0)" onclick="openTab('pendaftaran', 'Daftar Kunjungan')">Registrasi</a></li>
                    <li class="breadcrumb-item active">Rawat Jalan Baru</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0">Registrasi Pasien Rawat Jalan</h4>
        </div>
        <div class="text-end">
            <span class="badge bg-soft-primary text-primary rounded-pill px-3 py-2">
                <i class="fas fa-calendar-alt me-1"></i> <?= date('d M Y') ?>
            </span>
        </div>
    </div>

    <form id="form-registrasi-rajal" class="registration-container">
        <div class="row g-4">
            <!-- Left Column: Patient Info -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-shape bg-soft-primary text-primary rounded-3 me-3">
                                <i class="fas fa-user-injured fs-4"></i>
                            </div>
                            <h5 class="mb-0 fw-bold">Data Pasien</h5>
                        </div>

                        <!-- Hybrid Patient Search Control -->
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted d-flex justify-content-between">
                                <span>Cari Pasien (Nama / No. RM)</span>
                                <a href="javascript:void(0)" onclick="openTab('master/pasien', 'Master Pasien')" class="text-primary text-decoration-none fw-bold small">
                                    <i class="fas fa-external-link-alt me-1"></i> Buka Master Pasien
                                </a>
                            </label>
                            <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden border">
                                <span class="input-group-text bg-white border-0 px-3">
                                    <i class="fas fa-search text-primary"></i>
                                </span>
                                <input type="text" id="search_pasien" class="form-control border-0 py-3 px-2 fs-6" placeholder="Ketik & Tekan Enter untuk cari..." style="outline: none; box-shadow: none;">
                                <button class="btn btn-primary px-4 border-0" type="button" id="btn-search-pasien">Cari</button>
                            </div>
                            <div id="patient-search-results" class="mt-2" style="display:none; max-height: 350px; overflow-y: auto; z-index: 1050; position: absolute; width: 90%;">
                                <div class="list-group list-group-flush border rounded-4 shadow-lg overflow-hidden bg-white" id="list-search-pasien">
                                    <!-- Results here -->
                                </div>
                            </div>
                        </div>

                        <?php if($pasien): ?>
                            <div class="patient-card p-3 rounded-4 bg-light mb-4 border border-dashed">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="avatar avatar-lg rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                            <i class="fas fa-user fs-4"></i>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="fw-bold text-dark fs-5"><?= $pasien['nama_pasien'] ?></div>
                                        <div class="text-muted small">No. RM: <span class="badge bg-primary"><?= $pasien['norm'] ?></span></div>
                                    </div>
                                </div>
                                <hr class="my-3 opacity-10">
                                <div class="row g-2 mb-2">
                                    <div class="col-6">
                                        <div class="text-muted small">NIK / Identitas</div>
                                        <div class="fw-semibold small text-dark"><?= $pasien['nik'] ?? '-' ?></div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted small">No. HP</div>
                                        <div class="fw-semibold small text-dark"><?= $pasien['no_hp'] ?? '-' ?></div>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <div class="text-muted small">Alamat</div>
                                        <div class="fw-semibold small text-dark lh-sm"><?= $pasien['alamat'] ?? '-' ?></div>
                                    </div>
                                </div>
                                <div class="row text-center mt-3 pt-2 border-top">
                                    <div class="col-6 border-end">
                                        <div class="text-muted small">Tgl Lahir</div>
                                        <div class="fw-bold small"><?= date('d/m/Y', strtotime($pasien['tgl_lahir'])) ?></div>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted small">L/P</div>
                                        <div class="fw-bold small"><?= $pasien['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></div>
                                    </div>
                                </div>
                                <input type="hidden" name="id_pasien" value="<?= $pasien['id'] ?>">
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning rounded-4 border-0">
                                <div class="d-flex">
                                    <i class="fas fa-exclamation-triangle mt-1 me-3"></i>
                                    <div>
                                        <div class="fw-bold">Pasien Belum Dipilih</div>
                                        <div class="small">Silakan cari pasien di Master Data atau masukkan No RM.</div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Column: Registration Details -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-shape bg-soft-success text-success rounded-3 me-3">
                                <i class="fas fa-hospital-alt fs-4"></i>
                            </div>
                            <h5 class="mb-0 fw-bold">Tujuan & Penjamin</h5>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-muted mb-2 small">Poliklinik Tujuan</label>
                                <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                                    <span class="input-group-text bg-white border-0 px-3">
                                        <i class="fas fa-hospital text-primary"></i>
                                    </span>
                                    <select class="form-select border-0 py-2 fs-6 poli-select" name="id_poli" id="id_poli" required style="outline: none; box-shadow: none;">
                                        <option value="">Pilih Poliklinik</option>
                                        <?php foreach($poliklinik as $p): ?>
                                            <option value="<?= $p['id'] ?>"><?= $p['nama_poli'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-muted mb-2 small">Dokter Pemeriksa</label>
                                <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                                    <span class="input-group-text bg-white border-0 px-3">
                                        <i class="fas fa-user-md text-primary"></i>
                                    </span>
                                    <select class="form-select border-0 py-2 fs-6 dokter-select" name="id_dokter" id="id_dokter" required style="outline: none; box-shadow: none;">
                                        <option value="">Pilih Dokter</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-muted mb-2 small">Metode Penjamin</label>
                                <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                                    <span class="input-group-text bg-white border-0 px-3">
                                        <i class="fas fa-wallet text-primary"></i>
                                    </span>
                                    <select class="form-select border-0 py-2 fs-6 penjamin-select" name="penjamin" id="penjamin" required style="outline: none; box-shadow: none;">
                                        <option value="Umum">Umum / Tunai</option>
                                        <option value="BPJS">BPJS Kesehatan</option>
                                        <option value="Asuransi Lain">Asuransi Swasta</option>
                                        <option value="Perusahan">Rekanan Perusahaan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-muted mb-2 small">Sumber Pendaftaran</label>
                                <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                                    <span class="input-group-text bg-white border-0 px-3">
                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                    </span>
                                    <select class="form-select border-0 py-2 fs-6" name="sumber_daftar" required style="outline: none; box-shadow: none;">
                                        <option value="Loket">Loket On-site</option>
                                        <option value="MJKN">Aplikasi Mobile JKN</option>
                                        <option value="APM">Anjungan Pasien Mandiri</option>
                                        <option value="Online">WhatsApp / Web Booking</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- BPJS ADVANCED FIELDS -->
                        <div id="bpjs-extra-fields" style="display: none;">
                            <div class="p-4 rounded-4 bg-soft-primary border border-primary border-opacity-10 mb-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-primary">No. Kartu BPJS / NIK</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control rounded-start-pill border-0 px-3" id="no_kartu_bpjs" name="no_kartu" placeholder="0001XXXXX" value="<?= $pasien['no_jkn'] ?? '' ?>">
                                            <button class="btn btn-primary rounded-end-pill px-3" type="button" id="btn-cek-bpjs"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold text-primary">Jenis Kunjungan</label>
                                        <select class="form-select form-select-sm rounded-pill border-0" name="tujuan_kunjungan" id="tujuan_kunjungan">
                                            <option value="0">Normal (Rujukan Baru)</option>
                                            <option value="1">Prosedur (Radiologi/Lab)</option>
                                            <option value="2">Konsul/Evaluasi</option>
                                        </select>
                                    </div>

                                    <div id="bpjs-info-box" class="col-md-12 small" style="display: none;">
                                        <div class="p-3 rounded-3 bg-white border border-primary border-opacity-25 shadow-sm">
                                            <div class="row g-2">
                                                <div class="col-6"><strong>Nama:</strong> <span id="bpjs-nama" class="text-primary">-</span></div>
                                                <div class="col-6"><strong>Status:</strong> <span id="bpjs-status" class="badge bg-success">-</span></div>
                                                <div class="col-6"><strong>Kelas:</strong> <span id="bpjs-kelas">-</span></div>
                                                <div class="col-6"><strong>Provider:</strong> <span id="bpjs-faskes" class="small">-</span></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label small fw-bold text-primary">Cari Rujukan / SKDP</label>
                                        <div class="input-group input-group-sm">
                                            <select class="form-select rounded-start-pill border-0" id="asal_rujukan" name="asal_rujukan" style="max-width: 120px;">
                                                <option value="1">Puskesmas</option>
                                                <option value="2">RS / Spk</option>
                                            </select>
                                            <input type="text" class="form-control border-0" name="no_rujukan" id="no_rujukan" placeholder="No. Rujukan Terakhir">
                                            <button class="btn btn-outline-primary rounded-end-pill px-3" type="button" id="btn-list-rujukan" title="Tampilkan Semua Rujukan"><i class="fas fa-list"></i></button>
                                        </div>
                                        <div id="referral-list-container" class="mt-2" style="display:none;">
                                            <div class="list-group list-group-flush rounded-3 border small" id="list-rujukan-data">
                                                <!-- Dynamic list -->
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label small fw-bold text-primary">Diagnosa Awal (ICD-10)</label>
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control rounded-pill border-0 px-3" id="cari_diagnosa" placeholder="Ketik kode atau nama diagnosa...">
                                            <input type="hidden" name="diag_awal" id="diag_awal">
                                        </div>
                                        <div id="diag-results" class="position-absolute mt-1 shadow rounded-3 bg-white w-100 border small" style="z-index: 1000; display:none; max-height: 200px; overflow-y:auto;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SATUSEHAT INDICATOR -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center p-3 rounded-4 <?= !empty($pasien['ihs_number']) ? 'bg-soft-success border-success text-success' : 'bg-soft-warning border-warning text-warning' ?> border border-opacity-25 small">
                                <i class="<?= !empty($pasien['ihs_number']) ? 'fas fa-check-circle' : 'fas fa-exclamation-circle' ?> fs-5 me-3"></i>
                                <div>
                                    <div class="fw-bold">Status SatuSehat (Kemenkes)</div>
                                    <div><?= !empty($pasien['ihs_number']) ? 'IHS Number Terdaftar: ' . $pasien['ihs_number'] : 'IHS Number Belum Terdaftar (Akan di-bridging otomatis)' ?></div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 opacity-10">
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                                <i class="fas fa-check-circle me-2"></i> Simpan Pendaftaran
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        const dokterData = <?= json_encode($dokter) ?>;
        
        // Use delegated events with container context for better multi-tab support
        const $container = $('.registration-container').last(); // Target the most recently opened tab

        // Helper to find within current tab container
        const scope = (selector) => $container.find(selector);

        scope('.poli-select').on('change', function() {
            const poliId = $(this).val();
            const $dokterSelect = scope('.dokter-select');
            $dokterSelect.html('<option value="">Pilih Dokter</option>');
            
            if (poliId) {
                // Now dokterData should contain unit assignments (handled by controller/model)
                const filtered = dokterData.filter(d => d.unit_ids && d.unit_ids.includes(parseInt(poliId)));
                filtered.forEach(d => {
                    $dokterSelect.append(`<option value="${d.id}">${d.fullname}</option>`);
                });

                // Auto-select if only one doctor
                if (filtered.length === 1) {
                    $dokterSelect.val(filtered[0].id).trigger('change');
                    $dokterSelect.parent().addClass('border-primary');
                    setTimeout(() => $dokterSelect.parent().removeClass('border-primary'), 1000);
                }
            }
        });

        // Initialize state on load for this specific tab
        if (scope('.penjamin-select').val() === 'BPJS') {
            scope('#bpjs-extra-fields').show();
        }

        scope('.penjamin-select').on('change', function() {
            if ($(this).val() === 'BPJS') {
                scope('#bpjs-extra-fields').slideDown();
            } else {
                scope('#bpjs-extra-fields').slideUp();
                scope('#bpjs-info-box').hide();
            }
        });

        // --- HYBRID PATIENT SEARCH ---
        let patientSearchTimeout = null;

        const performPatientSearch = (q) => {
            if (q.length < 3) {
                scope('#patient-search-results').hide();
                return;
            }

            $.get(`<?= base_url('pendaftaran/cari_pasien') ?>?q=${q}`, (res) => {
                let html = '';
                if (res.length > 0) {
                    res.forEach(p => {
                        html += `
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action p-3 border-bottom select-patient-result" data-norm="${p.norm}">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div class="fw-bold text-primary">${p.nama_pasien}</div>
                                    <span class="badge bg-soft-primary text-primary">${p.norm}</span>
                                </div>
                                <div class="small text-muted mb-1"><i class="fas fa-id-card me-1"></i> ${p.nik || 'NIK -'}</div>
                                <div class="small text-muted text-truncate"><i class="fas fa-map-marker-alt me-1"></i> ${p.alamat || 'Alamat -'}</div>
                            </a>`;
                    });
                    scope('#list-search-pasien').html(html);
                    scope('#patient-search-results').show();
                } else {
                    scope('#list-search-pasien').html('<div class="p-4 text-center text-muted small">Pasien tidak ditemukan.</div>');
                    scope('#patient-search-results').show();
                }
            });
        };

        scope('#search_pasien').on('input', function() {
            const q = $(this).val();
            clearTimeout(patientSearchTimeout);
            patientSearchTimeout = setTimeout(() => performPatientSearch(q), 500);
        });

        scope('#search_pasien').on('keypress', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                const q = $(this).val();
                performPatientSearch(q);
                setTimeout(() => {
                    const results = scope('.select-patient-result');
                    if (results.length === 1) results.first().click();
                }, 800);
            }
        });

        scope('#btn-search-pasien').on('click', function() {
            performPatientSearch(scope('#search_pasien').val());
        });

        $container.on('click', '.select-patient-result', function() {
            const norm = $(this).data('norm');
            const currentTabId = $container.closest('.tab-pane').attr('id').replace('pane-', '');
            
            // Consolidate workflow: Reload CURRENT tab instead of opening a new one
            openTab(`pendaftaran/create?norm=${norm}`, 'Registrasi ' + norm, currentTabId);
        });

        $container.on('click', function(e) {
            if (!$(e.target).closest('#search_pasien, #patient-search-results, #btn-search-pasien').length) {
                scope('#patient-search-results').hide();
            }
        });

        // --- BPJS CEK PESERTA ---
        scope('#btn-cek-bpjs').on('click', function() {
            const nomor = scope('#no_kartu_bpjs').val();
            if(!nomor) return Swal.fire('Error', 'Masukkan No. Kartu / NIK', 'warning');

            const $btn = $(this);
            $btn.html('<i class="fas fa-spinner fa-spin"></i>');
            
            $.get(`<?= base_url('pendaftaran/cek_peserta_bpjs') ?>?nomor=${nomor}`, (res) => {
                $btn.html('<i class="fas fa-search"></i>');
                if (res.metaData && res.metaData.code == '200') {
                    const p = res.response.peserta;
                    scope('#bpjs-nama').text(p.nama);
                    scope('#bpjs-status').text(p.statusPeserta.keterangan);
                    scope('#bpjs-kelas').text(p.hakKelas.keterangan);
                    scope('#bpjs-faskes').text(p.provUmum.nmProvider);
                    scope('#bpjs-info-box').slideDown();
                } else {
                    Swal.fire('Error', res.metaData ? res.metaData.message : 'Gagal cek BPJS', 'error');
                }
            });
        });

        // --- FETCH RUJUKAN LIST ---
        scope('#btn-list-rujukan').on('click', function() {
            const nomor = scope('#no_kartu_bpjs').val();
            const asal = scope('#asal_rujukan').val();
            if(!nomor) return Swal.fire('Error', 'Cek Peserta Terlebih Dahulu', 'warning');

            const $btn = $(this);
            $btn.html('<i class="fas fa-spinner fa-spin"></i>');
            scope('#referral-list-container').slideUp();
            
            $.get(`<?= base_url('pendaftaran/cari_rujukan') ?>?nomor=${nomor}&asal=${asal}`, (res) => {
                $btn.html('<i class="fas fa-list"></i>');
                if (res.metaData && res.metaData.code == '200') {
                    const list = res.response.rujukan;
                    let html = '';
                    list.forEach(r => {
                        html += `
                            <a href="javascript:void(0)" class="list-group-item list-group-item-action p-2 select-rujukan" 
                                data-no="${r.noKunjungan}" data-diag="${r.diagnosa.kode}" data-diag-nama="${r.diagnosa.nama}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="fw-bold text-primary">${r.noKunjungan}</div>
                                    <span class="badge bg-soft-info text-info">${r.tglKunjungan}</span>
                                </div>
                                <div class="small">Poli: ${r.poliRujukan.nama}</div>
                            </a>`;
                    });
                    scope('#list-rujukan-data').html(html);
                    scope('#referral-list-container').slideDown();
                } else {
                    Swal.fire('Info', res.metaData ? res.metaData.message : 'Rujukan tidak ditemukan', 'info');
                }
            });
        });

        $container.on('click', '.select-rujukan', function() {
            const no = $(this).data('no');
            const diag = $(this).data('diag');
            const diagNama = $(this).data('diag-nama');
            
            scope('#no_rujukan').val(no);
            scope('#diag_awal').val(diag);
            scope('#cari_diagnosa').val(`${diag} - ${diagNama}`);
            scope('#referral-list-container').slideUp();
        });

        // --- DIAGNOSA SEARCH ---
        let diagTimeout = null;
        scope('#cari_diagnosa').on('input', function() {
            const q = $(this).val();
            clearTimeout(diagTimeout);
            if (q.length < 3) { scope('#diag-results').hide(); return; }

            diagTimeout = setTimeout(() => {
                $.get(`<?= base_url('pendaftaran/cari_diagnosa') ?>?q=${q}`, (res) => {
                    if (res.metaData && res.metaData.code == '200') {
                        let html = '';
                        res.response.diagnosa.forEach(d => {
                            html += `<div class="p-2 border-bottom diag-item" style="cursor:pointer" data-kode="${d.kode}" data-nama="${d.nama}">
                                        <strong>${d.kode}</strong> - ${d.nama}
                                     </div>`;
                        });
                        scope('#diag-results').html(html).show();
                    }
                });
            }, 500);
        });

        $container.on('click', '.diag-item', function() {
            const kode = $(this).data('kode');
            const nama = $(this).data('nama');
            scope('#diag_awal').val(kode);
            scope('#cari_diagnosa').val(`${kode} - ${nama}`);
            scope('#diag-results').hide();
        });

        scope('#form-registrasi-rajal').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            Swal.fire({
                title: 'Simpan Pendaftaran?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('pendaftaran/store') ?>',
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire('Berhasil', response.message, 'success').then(() => {
                                    closeTab($container.closest('.tab-pane').attr('id').replace('pane-', ''));
                                    openTab('pendaftaran', 'Daftar Kunjungan');
                                });
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        }
                    });
                }
            });
        });
    });
</script>

<style>
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); }
    .bg-soft-success { background-color: rgba(25, 135, 84, 0.1); }
    .icon-shape { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; }
</style>
