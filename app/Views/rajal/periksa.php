<?php
/**
 * View: rajal/periksa.php
 * Form Pemeriksaan Dokter – SOAP + Vital Sign
 */
$p  = $detail;
$vs = $vitalSign ?: [];
$formatTd = fn($d) => (($d['tekanan_darah_sistole'] ?? '-') . '/' . ($d['tekanan_darah_diastole'] ?? '-'));
$imt = null;
if (!empty($vs['berat_badan']) && !empty($vs['tinggi_badan'])) {
    $tb  = $vs['tinggi_badan'] / 100;
    $imt = round($vs['berat_badan'] / ($tb * $tb), 1);
}
?>
<style>
.soap-panel        { border-left:4px solid; border-radius:4px; padding:4px 0; }
.soap-s            { border-color:#0dcaf0; }
.soap-o            { border-color:#ffc107; }
.soap-a            { border-color:#fd7e14; }
.soap-p            { border-color:#198754; }
.soap-label        { font-size:.65rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; }
.vital-badge       { background:#f8f9fa; border:1px solid #dee2e6; border-radius:8px; padding:8px 12px; text-align:center; }
.vital-val         { font-size:1.3rem; font-weight:700; color:#0d6efd; }
.diag-tag          { display:inline-flex; align-items:center; background:#e7f1ff; border:1px solid #b6d4fe; border-radius:20px; padding:3px 10px; font-size:.8em; gap:6px; margin:2px; }
.diag-tag.sekunder { background:#fff3e0; border-color:#ffcc80; }
#save_indicator    { font-size:.75em; opacity:.7; }
.bridging-dot      { width:8px; height:8px; border-radius:50%; display:inline-block; margin-right:4px; }
</style>

<div class="container-fluid px-2">

    <!-- ===== HEADER PASIEN ===== -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body py-2">
            <div class="row align-items-center g-2">
                <div class="col-auto">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:50px;height:50px;font-size:1.2rem;">
                        <?= strtoupper(substr($p['nama_pasien'] ?? 'P', 0, 1)) ?>
                    </div>
                </div>
                <div class="col">
                    <div class="fw-bold fs-6"><?= esc($p['nama_pasien']) ?></div>
                    <div class="text-muted small">
                        <b>NORM</b>: <?= esc($p['norm']) ?> &nbsp;|&nbsp;
                        <b>NIK</b>: <?= esc($p['nik'] ?? '-') ?> &nbsp;|&nbsp;
                        <?= $usia ?> thn, <?= $p['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="small">
                        <b>No. Reg</b>: <?= esc($p['no_registrasi']) ?> &nbsp;|&nbsp;
                        <span class="badge bg-<?= $p['penjamin'] === 'BPJS' ? 'primary' : 'secondary' ?>"><?= esc($p['penjamin']) ?></span>
                    </div>
                    <div class="small text-muted">
                        Poli: <b><?= esc($p['nama_poli']) ?></b> &nbsp;|&nbsp;
                        Dokter: <b><?= esc($p['nama_dokter'] ?? '-') ?></b>
                    </div>
                    <div class="small mt-1">
                        <!-- Bridging Status -->
                        <span title="BPJS SEP">
                            <span class="bridging-dot" style="background:<?= $p['no_sep'] ? '#198754' : '#6c757d' ?>"></span>
                            SEP: <?= $p['no_sep'] ? '<b class="text-success">' . esc($p['no_sep']) . '</b>' : '<span class="text-muted">-</span>' ?>
                        </span>
                        &nbsp;|&nbsp;
                        <span title="SatuSehat Encounter">
                            <span class="bridging-dot" style="background:<?= $p['ihs_encounter_id'] ? '#198754' : '#6c757d' ?>"></span>
                            SatuSehat: <?= $p['ihs_encounter_id'] ? '<span class="text-success">✓</span>' : '<span class="text-muted">-</span>' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- ===== KOLOM KIRI: Vital Sign ===== -->
        <div class="col-md-4">

            <!-- Vital Sign Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-info bg-opacity-10 py-2 d-flex justify-content-between align-items-center">
                    <span class="fw-bold small"><i class="fas fa-heartbeat me-1 text-info"></i>Tanda-Tanda Vital</span>
                    <button class="btn btn-info btn-sm py-0" onclick="saveVital()" id="btn_save_vital">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
                <div class="card-body p-3">
                    <!-- Summary Badges -->
                    <?php if (!empty($vs)): ?>
                    <div class="row g-2 mb-3">
                        <div class="col-6"><div class="vital-badge"><div class="vital-val"><?= $formatTd($vs) ?></div><div class="text-muted" style="font-size:.7em">TD (mmHg)</div></div></div>
                        <div class="col-3"><div class="vital-badge"><div class="vital-val"><?= $vs['nadi'] ?? '-' ?></div><div class="text-muted" style="font-size:.7em">Nadi</div></div></div>
                        <div class="col-3"><div class="vital-badge"><div class="vital-val"><?= $vs['suhu'] ?? '-' ?></div><div class="text-muted" style="font-size:.7em">Suhu°C</div></div></div>
                        <?php if ($imt): ?><div class="col-6"><div class="vital-badge"><div class="vital-val text-<?= $imt < 18.5 ? 'warning' : ($imt <= 25 ? 'success' : 'danger') ?>"><?= $imt ?></div><div class="text-muted" style="font-size:.7em">IMT</div></div></div><?php endif ?>
                    </div>
                    <?php endif ?>

                    <!-- Input Form -->
                    <form id="form_vital" onsubmit="event.preventDefault();">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small mb-0">Sistol <span class="text-muted">(mmHg)</span></label>
                            <input type="number" name="sistole" class="form-control form-control-sm" value="<?= $vs['tekanan_darah_sistole'] ?? '' ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-0">Diastol <span class="text-muted">(mmHg)</span></label>
                            <input type="number" name="diastole" class="form-control form-control-sm" value="<?= $vs['tekanan_darah_diastole'] ?? '' ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-0">Nadi <span class="text-muted">(/mnt)</span></label>
                            <input type="number" name="nadi" class="form-control form-control-sm" value="<?= $vs['nadi'] ?? '' ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-0">Suhu <span class="text-muted">(°C)</span></label>
                            <input type="number" step="0.1" name="suhu" class="form-control form-control-sm" value="<?= $vs['suhu'] ?? '' ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-0">Respirasi <span class="text-muted">(/mnt)</span></label>
                            <input type="number" name="respirasi" class="form-control form-control-sm" value="<?= $vs['respirasi'] ?? '' ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-0">SpO₂ <span class="text-muted">(%)</span></label>
                            <input type="number" name="spo2" class="form-control form-control-sm" value="<?= $vs['spo2'] ?? '' ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-0">BB <span class="text-muted">(kg)</span></label>
                            <input type="number" step="0.1" name="berat_badan" class="form-control form-control-sm" value="<?= $vs['berat_badan'] ?? '' ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-0">TB <span class="text-muted">(cm)</span></label>
                            <input type="number" name="tinggi_badan" class="form-control form-control-sm" value="<?= $vs['tinggi_badan'] ?? '' ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label small mb-0">GCS <span class="text-muted">(total)</span></label>
                            <input type="number" name="gcs" min="3" max="15" class="form-control form-control-sm" value="<?= $vs['gcs'] ?? '' ?>">
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            <!-- Riwayat kompak -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-2">
                    <span class="fw-bold small"><i class="fas fa-history me-1"></i>Riwayat Pasien</span>
                </div>
                <div class="card-body p-2 small text-muted">
                    <p class="mb-1"><b>Alergi:</b> <?= esc($p['riwayat_alergi'] ?? '-') ?></p>
                    <p class="mb-1"><b>Penyakit Dahulu:</b> <?= esc($p['riwayat_penyakit_dahulu'] ?? '-') ?></p>
                    <p class="mb-0"><b>Riwayat Keluarga:</b> <?= esc($p['riwayat_keluarga'] ?? '-') ?></p>
                </div>
            </div>
        </div>

        <!-- ===== KOLOM KANAN: Form SOAP ===== -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="fas fa-notes-medical me-1 text-primary"></i>Rekam Medis – SOAP</span>
                    <div class="d-flex align-items-center gap-2">
                        <span id="save_indicator" class="text-muted"></span>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="saveSOAP()">
                            <i class="fas fa-save me-1"></i>Simpan Draft
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="openSelesaiModal()">
                            <i class="fas fa-check-circle me-1"></i>Selesaikan
                        </button>
                    </div>
                </div>
                <div class="card-body p-3">
                <form id="form_soap" onsubmit="event.preventDefault();">

                    <!-- S – Subjective -->
                    <div class="soap-panel soap-s ps-3 mb-3">
                        <div class="soap-label text-info mb-2">S – Subjective (Anamnesis)</div>
                        <div class="mb-2">
                            <label class="form-label small mb-1 fw-semibold">Keluhan Utama <span class="text-danger">*</span></label>
                            <textarea name="keluhan_utama" class="form-control form-control-sm" rows="2" placeholder="Keluhan yang dirasakan pasien saat ini..."><?= esc($p['keluhan_utama'] ?? $p['keluhan'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small mb-1">Riwayat Penyakit Sekarang</label>
                            <textarea name="riwayat_penyakit" class="form-control form-control-sm" rows="2" placeholder="Anamnesis lebih detail…"><?= esc($p['riwayat_penyakit'] ?? '') ?></textarea>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small mb-1">Riwayat Penyakit Dahulu</label>
                                <textarea name="riwayat_penyakit_dahulu" class="form-control form-control-sm" rows="1"><?= esc($p['riwayat_penyakit_dahulu'] ?? '') ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small mb-1">Riwayat Alergi</label>
                                <textarea name="riwayat_alergi" class="form-control form-control-sm" rows="1"><?= esc($p['riwayat_alergi'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- O – Objective -->
                    <div class="soap-panel soap-o ps-3 mb-3">
                        <div class="soap-label text-warning mb-2">O – Objective (Pemeriksaan Fisik)</div>
                        <div class="row g-2 mb-2">
                            <div class="col-md-6">
                                <label class="form-label small mb-1">Keadaan Umum</label>
                                <select name="keadaan_umum" class="form-select form-select-sm">
                                    <option value="">-- Pilih --</option>
                                    <?php foreach(['Baik','Sedang','Sakit Berat','Tampak Sakit'] as $ku): ?>
                                    <option value="<?= $ku ?>" <?= ($p['keadaan_umum'] ?? '') === $ku ? 'selected' : '' ?>><?= $ku ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small mb-1">Kesadaran</label>
                                <select name="kesadaran" class="form-select form-select-sm">
                                    <option value="">-- Pilih --</option>
                                    <?php foreach(['Compos Mentis','Apatis','Somnolen','Sopor','Koma'] as $ks): ?>
                                    <option value="<?= $ks ?>" <?= ($p['kesadaran'] ?? '') === $ks ? 'selected' : '' ?>><?= $ks ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="form-label small mb-1">Pemeriksaan Fisik (per sistem)</label>
                            <textarea name="pemeriksaan_fisik" class="form-control form-control-sm" rows="3" placeholder="Kepala: NA&#10;Thorax: NA&#10;Abdomen: NT(-), Supel&#10;Ekstremitas: oedem(-)"><?= esc($p['pemeriksaan_fisik'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <!-- A – Assessment -->
                    <div class="soap-panel soap-a ps-3 mb-3">
                        <div class="soap-label text-warning mb-2" style="color:#fd7e14!important">A – Assessment (Diagnosa ICD-10)</div>

                        <!-- Search ICD -->
                        <div class="input-group input-group-sm mb-2">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" id="icd_search" class="form-control" placeholder="Ketik kode atau nama penyakit…">
                            <select id="icd_jenis" class="form-select" style="max-width:120px">
                                <option value="primer">Primer</option>
                                <option value="sekunder">Sekunder</option>
                            </select>
                            <button type="button" class="btn btn-warning btn-sm" onclick="tambahDiagnosa()"><i class="fas fa-plus"></i></button>
                        </div>
                        <div id="icd_suggestions" class="list-group mb-2" style="position:relative;z-index:10"></div>

                        <!-- List Diagnosa -->
                        <div id="diag_list" class="mb-1">
                            <?php foreach ($diagList as $d): ?>
                            <span class="diag-tag <?= $d['jenis'] === 'sekunder' ? 'sekunder' : '' ?>" data-kode="<?= esc($d['kode_icd']) ?>" data-nama="<?= esc($d['nama']) ?>" data-jenis="<?= $d['jenis'] ?>">
                                <b><?= esc($d['kode_icd']) ?></b> <?= esc(substr($d['nama'] ?? '', 0, 40)) ?>
                                <span class="badge bg-<?= $d['jenis'] === 'primer' ? 'warning text-dark' : 'secondary' ?>"><?= $d['jenis'] ?></span>
                                <i class="fas fa-times text-muted" style="cursor:pointer" onclick="hapusDiagnosa(this)"></i>
                            </span>
                            <?php endforeach ?>
                        </div>
                        <small class="text-muted">Min. 1 diagnosa primer wajib untuk kelengkapan BPJS.</small>
                    </div>

                    <!-- P – Plan -->
                    <div class="soap-panel soap-p ps-3 mb-0">
                        <div class="soap-label text-success mb-2">P – Plan (Rencana Terapi)</div>
                        <div class="mb-2">
                            <label class="form-label small mb-1">Terapi / Tindakan</label>
                            <textarea name="terapi" class="form-control form-control-sm" rows="2" placeholder="R/ ... "><?= esc($p['terapi'] ?? '') ?></textarea>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label small mb-1">Edukasi Pasien</label>
                                <textarea name="edukasi" class="form-control form-control-sm" rows="1"><?= esc($p['edukasi'] ?? '') ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small mb-1">Anjuran Kontrol</label>
                                <textarea name="anjuran_kontrol" class="form-control form-control-sm" rows="1"><?= esc($p['anjuran_kontrol'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                </form>

                <!-- Panel Tindakan / Tarif -->
                <div class="soap-panel ps-3 mt-3 border-secondary border-start">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="soap-label text-secondary">Tindakan / Tarif Pemeriksaan</div>
                        <button type="button" class="btn btn-outline-secondary btn-sm py-0" onclick="alert('Form Tindakan Dalam Pengembangan')"><i class="fas fa-plus me-1"></i>Tambah Tarif</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered text-muted small mb-0">
                            <thead class="bg-light">
                                <tr><th>Nama Tindakan</th><th>Pelaksana</th><th>Tarif</th><th></th></tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="4" class="text-center">Belum ada tindakan diinput</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Panel Order Resep (E-Prescribing) -->
                <div class="soap-panel ps-3 mt-3 border-primary border-start">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="soap-label text-primary">E-Prescribing / Order Resep Obat</div>
                        <button type="button" class="btn btn-outline-primary btn-sm py-0" onclick="alert('Modul E-Resep Dalam Pengembangan')"><i class="fas fa-pills me-1"></i>Buat E-Resep</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered text-muted small mb-0">
                            <thead class="bg-light">
                                <tr><th>Nama Obat</th><th>Jumlah</th><th>Signa/Dosis</th><th>Status</th><th></th></tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="5" class="text-center">Belum ada resep obat</td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-end">
                         <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Resep akan diteruskan ke antrean Farmasi setelah di-submit.</small>
                    </div>
                </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Selesaikan Kunjungan -->
<div class="modal fade" id="selesaiModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success text-white py-2">
                <h6 class="modal-title"><i class="fas fa-check-circle me-1"></i>Selesaikan Kunjungan</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label small fw-semibold">Status Pulang Pasien</label>
                <select id="status_pulang" class="form-select form-select-sm">
                    <option value="Pulang">Pulang (Sehat)</option>
                    <option value="Pulang Paksa">Pulang Paksa / APS</option>
                    <option value="Rujuk">Rujuk ke Faskes Lebih Tinggi</option>
                    <option value="Rawat Inap">Dirawat Inap</option>
                    <option value="Meninggal">Meninggal</option>
                </select>
            </div>
            <div class="modal-footer py-2">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-success btn-sm" onclick="konfirmasiSelesai()"><i class="fas fa-check me-1"></i>Selesaikan</button>
            </div>
        </div>
    </div>
</div>

<script>
const REG_ID   = <?= (int)$p['reg_id'] ?>;
let   icdTimer = null;
let   selectedIcd = null;

// ── Vital Sign ───────────────────────────────────────────────
function saveVital() {
    const data = $('#form_vital').serialize();
    $('#btn_save_vital').prop('disabled', true).html('<div class="spinner-border spinner-border-sm me-1"></div>');
    $.post('<?= base_url('rajal/save_vital') ?>/' + REG_ID, data, function(res) {
        if (res.status === 'success') {
            toastr.success('Vital sign tersimpan');
            if (res.imt) $('#wl_content').find('[data-reg="' + REG_ID + '"]');
        } else {
            toastr.error(res.message);
        }
    }).always(() => $('#btn_save_vital').prop('disabled', false).html('<i class="fas fa-save me-1"></i>Simpan'));
}

// ── ICD-10 Search ────────────────────────────────────────────
$('#icd_search').on('input', function() {
    clearTimeout(icdTimer);
    const q = $(this).val();
    if (q.length < 2) { $('#icd_suggestions').empty(); return; }
    icdTimer = setTimeout(() => {
        $.get('<?= base_url('rajal/get_icd') ?>', { q }, function(res) {
            let html = '';
            res.forEach(r => {
                html += `<a href="#" class="list-group-item list-group-item-action small py-1 px-2 icd-opt"
                    data-kode="${r.kode}" data-nama="${r.nama}">${r.kode} – ${r.nama}</a>`;
            });
            $('#icd_suggestions').html(html);
        });
    }, 300);
});

$(document).on('click', '.icd-opt', function(e) {
    e.preventDefault();
    selectedIcd = { kode: $(this).data('kode'), nama: $(this).data('nama') };
    $('#icd_search').val($(this).data('kode') + ' – ' + $(this).data('nama'));
    $('#icd_suggestions').empty();
});

function tambahDiagnosa() {
    if (!selectedIcd) { toastr.warning('Pilih diagnosa dari daftar dahulu.'); return; }
    const jenis = $('#icd_jenis').val();
    const cls   = jenis === 'sekunder' ? 'diag-tag sekunder' : 'diag-tag';
    const badge = jenis === 'primer' ? 'bg-warning text-dark' : 'bg-secondary';
    const tag   = `<span class="${cls}" data-kode="${selectedIcd.kode}" data-nama="${selectedIcd.nama}" data-jenis="${jenis}">
        <b>${selectedIcd.kode}</b> ${selectedIcd.nama.substring(0,40)}
        <span class="badge ${badge}">${jenis}</span>
        <i class="fas fa-times text-muted" style="cursor:pointer" onclick="hapusDiagnosa(this)"></i>
    </span>`;
    $('#diag_list').append(tag);
    $('#icd_search').val(''); selectedIcd = null;
}

function hapusDiagnosa(el) { $(el).closest('.diag-tag').remove(); }

function getDiagnosaList() {
    return $('#diag_list .diag-tag').map(function() {
        return { kode_icd: $(this).data('kode'), nama: $(this).data('nama'), jenis: $(this).data('jenis') };
    }).get();
}

// ── SOAP Save ────────────────────────────────────────────────
function saveSOAP() {
    const diagList = getDiagnosaList();
    const formData = {
        keluhan_utama:           $('[name=keluhan_utama]').val(),
        riwayat_penyakit:        $('[name=riwayat_penyakit]').val(),
        riwayat_penyakit_dahulu: $('[name=riwayat_penyakit_dahulu]').val(),
        riwayat_alergi:          $('[name=riwayat_alergi]').val(),
        keadaan_umum:            $('[name=keadaan_umum]').val(),
        kesadaran:               $('[name=kesadaran]').val(),
        pemeriksaan_fisik:       $('[name=pemeriksaan_fisik]').val(),
        terapi:                  $('[name=terapi]').val(),
        edukasi:                 $('[name=edukasi]').val(),
        anjuran_kontrol:         $('[name=anjuran_kontrol]').val(),
        diagnosa:                JSON.stringify(diagList),
    };

    $('#save_indicator').text('Menyimpan…');
    $.post('<?= base_url('rajal/save_soap') ?>/' + REG_ID, formData, function(res) {
        if (res.status === 'success') {
            $('#save_indicator').text('✓ Tersimpan ' + new Date().toLocaleTimeString('id-ID'));
            toastr.success('SOAP berhasil disimpan.');
        } else {
            toastr.error(res.message || 'Gagal menyimpan.');
            $('#save_indicator').text('');
        }
    });
}

// Auto-save setiap 3 menit
setInterval(saveSOAP, 180000);

// ── Selesaikan Kunjungan ─────────────────────────────────────
function openSelesaiModal() {
    const diag = getDiagnosaList();
    if (!diag.length || !diag.find(x => x.jenis === 'primer')) {
        return Swal.fire('Perhatian', 'Harap isi minimal 1 diagnosa primer (ICD-10) sebelum menyelesaikan kunjungan.', 'warning');
    }
    saveSOAP(); // auto-save dulu
    new bootstrap.Modal(document.getElementById('selesaiModal')).show();
}

function konfirmasiSelesai() {
    const sp = $('#status_pulang').val();
    bootstrap.Modal.getInstance(document.getElementById('selesaiModal')).hide();

    $.post('<?= base_url('rajal/selesai') ?>/' + REG_ID, { status_pulang: sp }, function(res) {
        if (res.status === 'success') {
            Swal.fire({ icon:'success', title:'Kunjungan Selesai', text: res.message, confirmButtonColor:'#198754' })
                .then(() => {
                    // Kembali ke worklist
                    if (typeof openTab === 'function') openTab('rajal/pemeriksaan', 'Pemeriksaan Poli', 'fas fa-user-md', 'Rawat Jalan');
                });
        } else {
            Swal.fire('Gagal', res.message, 'error');
        }
    });
}

// ── Toastr (jika belum ada) ───────────────────────────────────
if (typeof toastr === 'undefined') {
    window.toastr = {
        success: (m) => Swal.fire({ icon:'success', title:m, toast:true, position:'bottom-end', showConfirmButton:false, timer:2000, timerProgressBar:true }),
        warning: (m) => Swal.fire({ icon:'warning',  title:m, toast:true, position:'bottom-end', showConfirmButton:false, timer:2500, timerProgressBar:true }),
        error:   (m) => Swal.fire({ icon:'error',    title:m, toast:true, position:'bottom-end', showConfirmButton:false, timer:3000, timerProgressBar:true }),
    };
}
</script>
