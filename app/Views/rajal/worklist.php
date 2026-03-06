<?php
/**
 * View: rajal/worklist.php
 * Worklist harian Rawat Jalan per poliklinik
 */
?>
<style>
.status-badge       { font-size:.72em; padding:3px 9px; border-radius:20px; font-weight:600; }
.s-draft            { background:#f8f9fa; color:#6c757d; border:1px solid #dee2e6; }
.s-in-progress      { background:#fff3cd; color:#856404; }
.s-finished         { background:#d1e7dd; color:#0f5132; }
.s-waiting          { background:#cfe2ff; color:#084298; }
.antrian-no         { font-size:1.6rem; font-weight:700; color:#0d6efd; line-height:1; }
.antrian-card       { border-left:4px solid #0d6efd; transition:.2s; }
.antrian-card:hover { box-shadow:0 4px 16px rgba(13,110,253,.15); }
.antrian-card.in-progress { border-left-color:#ffc107; }
.antrian-card.finished    { border-left-color:#198754; opacity:.8; }
</style>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="mb-0 text-primary"><i class="fas fa-stethoscope me-2"></i>Worklist Rawat Jalan</h5>
            <small class="text-muted">Daftar pasien per poliklinik hari ini</small>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <span class="badge bg-light text-dark border" id="wl_info">-</span>
            <button class="btn btn-sm btn-outline-secondary" onclick="muat()"><i class="fas fa-sync"></i></button>
        </div>
    </div>

    <div class="card-body p-3">
        <!-- Filter Bar -->
        <div class="row g-2 mb-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Poliklinik</label>
                <select id="wl_unit" class="form-select form-select-sm" onchange="muat()">
                    <option value="">-- Pilih Poli --</option>
                    <?php foreach ($units as $u): ?>
                    <option value="<?= $u['id'] ?>" <?= $selected_unit == $u['id'] ? 'selected' : '' ?>>
                        <?= esc($u['nama_poli']) ?>
                    </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Tanggal</label>
                <input type="date" id="wl_tgl" class="form-control form-control-sm" value="<?= $selected_tgl ?>" onchange="muat()">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold mb-1">Filter Status</label>
                <select id="wl_status" class="form-select form-select-sm" onchange="filterLokal()">
                    <option value="">Semua Status</option>
                    <option value="draft">Menunggu</option>
                    <option value="in-progress">Sedang Diperiksa</option>
                    <option value="finished">Selesai</option>
                </select>
            </div>
        </div>

        <!-- Summary Pill -->
        <div id="wl_summary" class="d-flex gap-2 mb-3 flex-wrap"></div>

        <!-- Worklist content -->
        <div id="wl_content">
            <p class="text-center text-muted py-5"><i class="fas fa-hand-pointer me-2"></i>Pilih poliklinik untuk menampilkan daftar pasien.</p>
        </div>
    </div>
</div>

<script>
let wlData = [];

function muat() {
    const unitId = $('#wl_unit').val();
    const tgl    = $('#wl_tgl').val();

    if (!unitId) {
        $('#wl_content').html('<p class="text-center text-muted py-5"><i class="fas fa-hand-pointer me-2"></i>Pilih poliklinik untuk menampilkan daftar pasien.</p>');
        return;
    }

    $('#wl_content').html('<div class="text-center py-5"><div class="spinner-border text-primary spinner-border-sm"></div><p class="mt-2 text-muted">Memuat...</p></div>');

    $.post('<?= base_url('rajal/get_worklist') ?>', { unit_id: unitId, tgl }, function(res) {
        wlData = res.data || [];
        renderWorklist(wlData);
        renderSummary(wlData);
    });
}

function getStatusLabel(s) {
    if (s === 'draft')       return '<span class="status-badge s-draft"><i class="fas fa-clock me-1"></i>Menunggu</span>';
    if (s === 'in-progress') return '<span class="status-badge s-in-progress"><i class="fas fa-user-md me-1"></i>Diperiksa</span>';
    if (s === 'finished')    return '<span class="status-badge s-finished"><i class="fas fa-check-circle me-1"></i>Selesai</span>';
    return '<span class="status-badge s-draft">-</span>';
}

function getCardClass(s) {
    if (s === 'in-progress') return 'antrian-card in-progress';
    if (s === 'finished')    return 'antrian-card finished opacity-75 bg-light';
    return 'antrian-card bg-white';
}

function getAgeStr(tglLahir) {
    if (!tglLahir) return '-';
    const birth = new Date(tglLahir);
    const today = new Date();
    const age   = Math.floor((today - birth) / (365.25 * 24 * 3600 * 1000));
    return age + ' thn';
}

function renderWorklist(list) {
    if (!list.length) {
        $('#wl_content').html('<div class="text-center py-5 text-muted"><i class="fas fa-calendar-times fa-2x mb-2"></i><p>Tidak ada pendaftaran untuk tanggal ini.</p></div>');
        return;
    }

    const filterStatus = $('#wl_status').val();
    const filtered     = filterStatus ? list.filter(x => x.status_emr === filterStatus) : list;

    let html = '<div class="row g-3">';
    filtered.forEach(p => {
        const penjamin  = p.penjamin === 'BPJS' ? '<span class="badge bg-primary">BPJS</span>' : `<span class="badge bg-secondary">${p.penjamin || 'Umum'}</span>`;
        const btns      = p.status_emr !== 'finished'
            ? `<button class="btn btn-success btn-sm" onclick="bukaPeriksa(${p.reg_id}, '${p.nama_pasien.replace(/'/g, "\\'")}')"><i class="fas fa-notes-medical me-1"></i>Periksa</button>`
            : `<button class="btn btn-outline-secondary btn-sm" onclick="bukaPeriksa(${p.reg_id}, '${p.nama_pasien.replace(/'/g, "\\'")}')"><i class="fas fa-eye me-1"></i>Lihat</button>`;

        html += `
        <div class="col-12 col-md-6" data-status="${p.status_emr}">
            <div class="card ${getCardClass(p.status_emr)} border p-0">
                <div class="card-body p-3 d-flex gap-3">
                    <div class="text-center" style="min-width:52px">
                        <div class="antrian-no">${p.no_antrian}</div>
                        <small class="text-muted">Antrian</small>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold">${p.nama_pasien}</div>
                        <div class="text-muted small">${p.norm}  •  ${getAgeStr(p.tgl_lahir)}  •  ${p.jenis_kelamin === 'L' ? 'L' : 'P'}</div>
                        <div class="mt-1 d-flex gap-1 flex-wrap">
                            ${penjamin}
                            ${getStatusLabel(p.status_emr)}
                            ${p.diagnosa_utama ? `<span class="badge bg-light text-dark border">${p.diagnosa_utama}</span>` : ''}
                        </div>
                        ${p.dokter ? `<small class="text-muted"><i class="fas fa-user-md me-1"></i>${p.dokter}</small>` : ''}
                    </div>
                    <div class="text-end">
                        ${btns}
                    </div>
                </div>
            </div>
        </div>`;
    });
    html += '</div>';

    $('#wl_content').html(html);
    $('#wl_info').text(filtered.length + ' pasien');
}

function renderSummary(list) {
    const total = list.length;
    const menunggu  = list.filter(x => x.status_emr === 'draft').length;
    const diperiksa = list.filter(x => x.status_emr === 'in-progress').length;
    const selesai   = list.filter(x => x.status_emr === 'finished').length;
    $('#wl_summary').html(`
        <span class="badge bg-primary">Total: ${total}</span>
        <span class="badge bg-info text-dark">Menunggu: ${menunggu}</span>
        <span class="badge bg-warning text-dark">Diperiksa: ${diperiksa}</span>
        <span class="badge bg-success">Selesai: ${selesai}</span>
    `);
}

function filterLokal() { renderWorklist(wlData); }

function bukaPeriksa(regId, namaPasien) {
    const title = namaPasien ? 'EMR - ' + namaPasien.substring(0, 10) : 'EMR Pasien';
    if (typeof openTab === 'function') {
        openTab('rajal/periksa/' + regId, title, 'emr-' + regId);
    } else {
        window.location = '<?= base_url('rajal/periksa') ?>/' + regId;
    }
}

// Auto-load jika poli sudah dipilih
$(function() {
    if ($('#wl_unit').val()) muat();

    // Auto-refresh setiap 60 detik
    setInterval(function() {
        if ($('#wl_unit').val()) muat();
    }, 60000);
});
</script>
