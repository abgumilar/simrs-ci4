<?php
/**
 * View: admin/bpjs_jkn.php
 * BPJS MJKN Tools – Dashboard Operasional
 */
?>
<style>
.status-badge { font-size: 0.72em; padding: 3px 8px; border-radius: 20px; font-weight: 600; }
.status-waiting  { background: #fff3cd; color: #856404; }
.status-done     { background: #d1e7dd; color: #0f5132; }
.status-absent   { background: #f8d7da; color: #842029; }
.booking-card    { border-left: 4px solid #0d6efd; transition: .2s; }
.booking-card:hover { box-shadow: 0 4px 16px rgba(13,110,253,.12); }
.checkin-btn     { font-size: .8em; }
</style>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="mb-0 text-primary"><i class="fas fa-mobile-alt me-2"></i>BPJS MJKN Tools</h5>
            <small class="text-muted">Monitoring &amp; Check-in Pasien Booking Mobile JKN</small>
        </div>
        <div class="badge bg-light text-primary border p-2 small">
            <i class="fas fa-plug me-1"></i><?= esc($config['base_url_antrean'] ?? 'Tidak Dikonfigurasi') ?>
        </div>
    </div>

    <div class="card-body p-3">

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-3" id="jknTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-booking-list" type="button">
                    <i class="fas fa-list-check me-1 text-primary"></i> Daftar Booking Pasien
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-cek-booking" type="button">
                    <i class="fas fa-search me-1"></i> Cek Kode Booking
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-daily" type="button">
                    <i class="fas fa-chart-bar me-1"></i> Dashboard Harian
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-monthly" type="button">
                    <i class="fas fa-calendar-alt me-1"></i> Dashboard Bulanan
                </button>
            </li>
        </ul>

        <div class="tab-content">

            <!-- ===================== TAB 1: DAFTAR BOOKING ===================== -->
            <div class="tab-pane fade show active" id="tab-booking-list">
                <!-- Filter Bar -->
                <div class="d-flex gap-2 align-items-end mb-3 flex-wrap">
                    <div>
                        <label class="form-label small fw-semibold mb-1">Tanggal Booking</label>
                        <input type="date" id="bl_date" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>">
                    </div>
                    <button class="btn btn-primary btn-sm" onclick="loadBookingList()">
                        <i class="fas fa-sync me-1"></i> Muat Daftar
                    </button>
                    <div class="ms-auto">
                        <input type="text" id="bl_filter" class="form-control form-control-sm" placeholder="Filter nama / poli / booking…" onkeyup="filterTable()">
                    </div>
                </div>

                <!-- Summary Pills -->
                <div id="bl_summary" class="d-flex gap-2 mb-3 flex-wrap" style="display:none!important"></div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle small" id="bl_table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Kode Booking</th>
                                <th>Pasien (NIK / No Kartu)</th>
                                <th>Poli</th>
                                <th>Jadwal</th>
                                <th>Estimasi</th>
                                <th>Sumber</th>
                                <th>Status BPJS</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="bl_tbody">
                            <tr><td colspan="9" class="text-center text-muted py-4">Klik <strong>Muat Daftar</strong> untuk mengambil data dari BPJS.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ===================== TAB 2: CEK BOOKING ===================== -->
            <div class="tab-pane fade" id="tab-cek-booking">
                <div class="row g-3 mb-4 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold">Kode Booking</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                            <input type="text" id="cb_code" class="form-control" placeholder="Contoh: ABC0000001" onkeydown="if(event.key==='Enter') cekBooking()">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100" onclick="cekBooking()"><i class="fas fa-search me-1"></i> Cek</button>
                    </div>
                </div>
                <div id="cb_result">
                    <p class="text-center text-muted">Masukkan kode booking untuk melihat detail &amp; log waktu.</p>
                </div>
            </div>

            <!-- ===================== TAB 3: DASHBOARD HARIAN ===================== -->
            <div class="tab-pane fade" id="tab-daily">
                <div class="row g-2 mb-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Tanggal</label>
                        <input type="date" id="dd_date" class="form-control form-control-sm" value="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Waktu</label>
                        <select id="dd_waktu" class="form-select form-select-sm">
                            <option value="rs">Waktu RS</option>
                            <option value="server">Waktu Server BPJS</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary btn-sm w-100" onclick="getDailyDashboard()"><i class="fas fa-sync me-1"></i> Muat</button>
                    </div>
                </div>
                <div id="dd_result" class="table-responsive">
                    <p class="text-center text-muted">Pilih tanggal dan klik Muat.</p>
                </div>
            </div>

            <!-- ===================== TAB 4: DASHBOARD BULANAN ===================== -->
            <div class="tab-pane fade" id="tab-monthly">
                <div class="row g-2 mb-3 align-items-end">
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Bulan</label>
                        <select id="dm_month" class="form-select form-select-sm">
                            <?php for ($i = 1; $i <= 12; $i++): ?>
                                <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= $i == date('n') ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $i, 10)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Tahun</label>
                        <input type="number" id="dm_year" class="form-control form-control-sm" value="<?= date('Y') ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Waktu</label>
                        <select id="dm_waktu" class="form-select form-select-sm">
                            <option value="rs">Waktu RS</option>
                            <option value="server">Waktu Server BPJS</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary btn-sm w-100" onclick="getMonthlyDashboard()"><i class="fas fa-sync me-1"></i> Muat</button>
                    </div>
                </div>
                <div id="dm_result" class="table-responsive">
                    <p class="text-center text-muted">Pilih periode dan klik Muat.</p>
                </div>
            </div>

        </div><!-- /tab-content -->
    </div><!-- /card-body -->
</div>

<!-- Modal Check-in -->
<div class="modal fade" id="checkinModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-check me-2"></i>Check-in Pasien mJKN</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="checkin_modal_body">
                <!-- Diisi JS -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btn_confirm_checkin" onclick="confirmCheckin()">
                    <i class="fas fa-check me-1"></i> Konfirmasi Check-in
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// ─── State ───────────────────────────────────────────────────────────────────
let jknBookingData = [];
let selectedBooking = null;

// ─── Tab 1: Daftar Booking ───────────────────────────────────────────────────
function loadBookingList() {
    const tgl = $('#bl_date').val();
    if (!tgl) return;

    $('#bl_tbody').html(`<tr><td colspan="9" class="text-center py-4">
        <div class="spinner-border spinner-border-sm text-primary me-2"></div>Mengambil data dari BPJS...
    </td></tr>`);
    $('#bl_summary').empty().hide();

    $.post('admin/bpjsjkn/get_antrean_tanggal', { tanggal: tgl }, function(res) {
        if ((res.metadata?.code == 200 || res.metadata?.code == 1) && res.response?.list?.length) {
            jknBookingData = res.response.list;
            renderBookingTable(jknBookingData);
            renderSummary(jknBookingData);
        } else {
            jknBookingData = [];
            const msg = res.metadata?.message || 'Gagal mengambil data';
            $('#bl_tbody').html(`<tr><td colspan="9" class="text-center text-warning py-4">
                <i class="fas fa-exclamation-triangle me-2"></i>${msg}
            </td></tr>`);
        }
    }).fail(function() {
        $('#bl_tbody').html(`<tr><td colspan="9" class="text-center text-danger py-4">
            <i class="fas fa-times-circle me-2"></i>Koneksi ke server gagal.
        </td></tr>`);
    });
}

function renderBookingTable(list) {
    if (!list.length) {
        $('#bl_tbody').html(`<tr><td colspan="9" class="text-center text-muted py-4">Tidak ada data booking untuk tanggal ini.</td></tr>`);
        return;
    }

    let html = '';
    list.forEach((item, idx) => {
        const statusClass = item.status?.toLowerCase().includes('selesai') ? 'status-done'
                          : item.status?.toLowerCase().includes('batal')  ? 'status-absent'
                          : 'status-waiting';
        const estimasi = item.estimasidilayani
            ? new Date(item.estimasidilayani).toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'})
            : '-';

        html += `<tr>
            <td class="text-muted">${idx + 1}</td>
            <td><code class="text-primary small">${item.kodebooking}</code></td>
            <td>
                <div class="fw-semibold">${item.nik || '-'}</div>
                <div class="text-muted small">${item.nokapst || '-'}</div>
                <div class="text-muted xsmall">${item.nohp || ''}</div>
            </td>
            <td><span class="badge bg-secondary">${item.kodepoli || '-'}</span></td>
            <td class="small">${item.tanggal || '-'}<br><span class="text-muted">${item.jampraktek || ''}</span></td>
            <td class="small">${estimasi}</td>
            <td><span class="badge bg-info text-dark">${item.sumberdata || 'mJKN'}</span></td>
            <td><span class="status-badge ${statusClass}">${item.status || 'Menunggu'}</span></td>
            <td>
                <button class="btn btn-success btn-sm checkin-btn" onclick='openCheckinModal(${JSON.stringify(item)})'>
                    <i class="fas fa-user-check me-1"></i>Check-in
                </button>
            </td>
        </tr>`;
    });
    $('#bl_tbody').html(html);
}

function renderSummary(list) {
    const total    = list.length;
    const done     = list.filter(x => x.status?.toLowerCase().includes('selesai')).length;
    const waiting  = list.filter(x => !x.status?.toLowerCase().includes('selesai') && !x.status?.toLowerCase().includes('batal')).length;
    const canceled = list.filter(x => x.status?.toLowerCase().includes('batal')).length;

    $('#bl_summary').html(`
        <span class="badge bg-primary">Total: ${total}</span>
        <span class="badge bg-warning text-dark">Menunggu: ${waiting}</span>
        <span class="badge bg-success">Selesai: ${done}</span>
        <span class="badge bg-danger">Batal: ${canceled}</span>
    `).show();
}

function filterTable() {
    const q = $('#bl_filter').val().toLowerCase();
    const filtered = jknBookingData.filter(x =>
        (x.kodebooking||'').toLowerCase().includes(q) ||
        (x.nik||'').toLowerCase().includes(q)         ||
        (x.nokapst||'').toLowerCase().includes(q)     ||
        (x.kodepoli||'').toLowerCase().includes(q)    ||
        (x.status||'').toLowerCase().includes(q)
    );
    renderBookingTable(filtered);
}

// ─── Check-in Modal ──────────────────────────────────────────────────────────
function openCheckinModal(booking) {
    selectedBooking = booking;

    const estimasi = booking.estimasidilayani
        ? new Date(booking.estimasidilayani).toLocaleString('id-ID')
        : '-';

    $('#checkin_modal_body').html(`
        <div class="row g-3">
            <div class="col-md-6">
                <h6 class="fw-bold border-bottom pb-2 text-primary"><i class="fas fa-ticket-alt me-1"></i> Data Booking BPJS</h6>
                <table class="table table-sm table-borderless small">
                    <tr><td class="text-muted" width="120">Kode Booking</td><td>: <strong class="text-primary">${booking.kodebooking}</strong></td></tr>
                    <tr><td class="text-muted">NIK</td><td>: ${booking.nik || '-'}</td></tr>
                    <tr><td class="text-muted">No Kartu JKN</td><td>: ${booking.nokapst || '-'}</td></tr>
                    <tr><td class="text-muted">No HP</td><td>: ${booking.nohp || '-'}</td></tr>
                    <tr><td class="text-muted">Poli</td><td>: <span class="badge bg-secondary">${booking.kodepoli}</span></td></tr>
                    <tr><td class="text-muted">Jam Praktek</td><td>: ${booking.jampraktek || '-'}</td></tr>
                    <tr><td class="text-muted">No Antrean</td><td>: <strong>${booking.noantrean || '-'}</strong></td></tr>
                    <tr><td class="text-muted">Estimasi Dilayani</td><td>: ${estimasi}</td></tr>
                    <tr><td class="text-muted">No Referensi</td><td>: ${booking.nomorreferensi || '-'}</td></tr>
                    <tr><td class="text-muted">Status</td><td>: <span class="badge bg-success">${booking.status || '-'}</span></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold border-bottom pb-2 text-success"><i class="fas fa-database me-1"></i> Cocokkan Pasien Lokal</h6>
                <div id="local_patient_info">
                    <div class="text-center p-3"><div class="spinner-border spinner-border-sm text-success"></div> Mencari data pasien...</div>
                </div>
            </div>
        </div>
    `);

    const modal = new bootstrap.Modal(document.getElementById('checkinModal'));
    modal.show();

    // Cari pasien lokal berdasarkan NIK / No Kartu JKN
    $.post('admin/bpjsjkn/cari_pasien_local', {
        nik: booking.nik,
        nokapst: booking.nokapst
    }, function(res) {
        if (res.found) {
            selectedBooking._local = res.pasien;
            $('#local_patient_info').html(`
                <div class="alert alert-success py-2">
                    <i class="fas fa-check-circle me-1"></i> <strong>Pasien Ditemukan!</strong>
                </div>
                <table class="table table-sm table-borderless small">
                    <tr><td class="text-muted" width="100">NORM</td><td>: <strong>${res.pasien.norm}</strong></td></tr>
                    <tr><td class="text-muted">Nama</td><td>: ${res.pasien.nama_pasien}</td></tr>
                    <tr><td class="text-muted">Tgl Lahir</td><td>: ${res.pasien.tgl_lahir || '-'}</td></tr>
                    <tr><td class="text-muted">Alamat</td><td>: ${res.pasien.alamat || '-'}</td></tr>
                </table>
                <div class="alert alert-info small py-2"><i class="fas fa-info-circle me-1"></i>Klik <strong>Konfirmasi Check-in</strong> untuk mendaftarkan pasien ke antrian RS.</div>
            `);
            $('#btn_confirm_checkin').prop('disabled', false);
        } else {
            selectedBooking._local = null;
            $('#local_patient_info').html(`
                <div class="alert alert-warning py-2">
                    <i class="fas fa-exclamation-triangle me-1"></i> Pasien <strong>belum terdaftar</strong> di database RS.
                </div>
                <p class="small text-muted">Anda perlu mendaftarkan pasien terlebih dahulu ke master pasien sebelum melakukan check-in.</p>
                <button class="btn btn-warning btn-sm" onclick="openRegisterNewPatient()">
                    <i class="fas fa-user-plus me-1"></i> Daftarkan Pasien Baru
                </button>
            `);
            $('#btn_confirm_checkin').prop('disabled', true);
        }
    });
}

function openRegisterNewPatient() {
    // Pre-fill and open registration via workspace tab
    if (typeof openTab === 'function') {
        openTab('master/pasien/create', 'Daftar Pasien Baru');
    }
    bootstrap.Modal.getInstance(document.getElementById('checkinModal')).hide();
}

function confirmCheckin() {
    if (!selectedBooking) return;
    $('#btn_confirm_checkin').prop('disabled', true).html('<div class="spinner-border spinner-border-sm me-1"></div> Memproses...');

    $.post('admin/bpjsjkn/do_checkin', {
        kodebooking: selectedBooking.kodebooking,
        nik: selectedBooking.nik,
        nokapst: selectedBooking.nokapst,
        kodepoli: selectedBooking.kodepoli,
        kodedokter: selectedBooking.kodedokter,
        jampraktek: selectedBooking.jampraktek,
        tanggal: selectedBooking.tanggal,
        nomorreferensi: selectedBooking.nomorreferensi,
        noantrean: selectedBooking.noantrean
    }, function(res) {
        bootstrap.Modal.getInstance(document.getElementById('checkinModal')).hide();

        if (res.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Check-in Berhasil!',
                html: `Pasien <strong>${res.norm}</strong> telah terdaftar ke antrian RS.<br>No Registrasi: <strong>${res.no_reg || '-'}</strong>`,
                confirmButtonColor: '#0d6efd'
            }).then(() => loadBookingList());
        } else {
            Swal.fire('Gagal', res.message || 'Terjadi kesalahan saat check-in.', 'error');
            $('#btn_confirm_checkin').prop('disabled', false).html('<i class="fas fa-check me-1"></i> Konfirmasi Check-in');
        }
    }).fail(function() {
        Swal.fire('Error', 'Gagal menghubungi server.', 'error');
        $('#btn_confirm_checkin').prop('disabled', false).html('<i class="fas fa-check me-1"></i> Konfirmasi Check-in');
    });
}

// ─── Tab 2: Cek Kode Booking ─────────────────────────────────────────────────
function cekBooking() {
    const kode = $('#cb_code').val().trim();
    if (!kode) return;
    $('#cb_result').html('<div class="text-center p-4"><div class="spinner-border text-primary spinner-border-sm"></div> Mencari...</div>');

    $.post('admin/bpjsjkn/get_antrean_booking', { kodebooking: kode }, function(res) {
        if ((res.metadata?.code == 200 || res.metadata?.code == 1) && res.response?.list?.length) {
            const item = res.response.list[0];
            const estimasi = item.estimasidilayani ? new Date(item.estimasidilayani).toLocaleString('id-ID') : '-';
            let html = `<div class="row g-3">
                <div class="col-md-5">
                    <div class="card border-0 bg-light">
                        <div class="card-body small">
                            <h6 class="fw-bold text-primary"><i class="fas fa-ticket-alt me-1"></i>Detail Booking</h6>
                            <table class="table table-borderless table-sm mb-0">
                                <tr><td class="text-muted">Kode</td><td>: <strong class="text-primary">${item.kodebooking}</strong></td></tr>
                                <tr><td class="text-muted">NIK</td><td>: ${item.nik}</td></tr>
                                <tr><td class="text-muted">No Kartu</td><td>: ${item.nokapst}</td></tr>
                                <tr><td class="text-muted">Poli</td><td>: ${item.kodepoli}</td></tr>
                                <tr><td class="text-muted">Antrean</td><td>: <strong>${item.noantrean}</strong></td></tr>
                                <tr><td class="text-muted">Status</td><td>: <span class="badge bg-success">${item.status}</span></td></tr>
                                <tr><td class="text-muted">Estimasi</td><td>: ${estimasi}</td></tr>
                            </table>
                            <button class="btn btn-success btn-sm mt-2 w-100" onclick='openCheckinModal(${JSON.stringify(item)})'>
                                <i class="fas fa-user-check me-1"></i> Check-in Pasien Ini
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-7" id="task_log_col">
                    <div class="text-center text-muted p-3 small"><div class="spinner-border spinner-border-sm"></div> Mengambil log task...</div>
                </div>
            </div>`;
            $('#cb_result').html(html);

            // Fetch task logs
            $.post('admin/bpjsjkn/get_task_logs', { kodebooking: kode }, function(resT) {
                if ((resT.metadata?.code == 200 || resT.metadata?.code == 1) && resT.response?.list?.length) {
                    let logHtml = `<h6 class="fw-bold text-dark"><i class="fas fa-history me-1"></i>Log Task ID BPJS</h6>
                        <table class="table table-sm table-striped small">
                            <thead class="table-light"><tr><th>Task</th><th>Waktu RS</th><th>Waktu BPJS</th></tr></thead>
                            <tbody>`;
                    resT.response.list.forEach(log => {
                        logHtml += `<tr>
                            <td><span class="badge bg-primary">${log.taskid}</span> ${log.taskname}</td>
                            <td>${log.wakturs}</td>
                            <td>${log.waktu}</td>
                        </tr>`;
                    });
                    logHtml += `</tbody></table>`;
                    $('#task_log_col').html(logHtml);
                } else {
                    $('#task_log_col').html(`<div class="alert alert-light border small text-muted"><i class="fas fa-info-circle me-1"></i>${resT.metadata?.message || 'Belum ada log task.'}</div>`);
                }
            });
        } else {
            $('#cb_result').html(`<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-2"></i>${res.metadata?.message || 'Data tidak ditemukan.'}</div>`);
        }
    });
}

// ─── Tab 3: Dashboard Harian ──────────────────────────────────────────────────
function getDailyDashboard() {
    const tgl = $('#dd_date').val(), waktu = $('#dd_waktu').val();
    $('#dd_result').html('<div class="text-center p-4"><div class="spinner-border text-primary spinner-border-sm"></div> Memuat...</div>');
    $.post('admin/bpjsjkn/get_dashboard_tanggal', { tanggal: tgl, waktu }, function(res) {
        if ((res.metadata?.code == 200 || res.metadata?.code == 1) && res.response?.list?.length) {
            let html = `<table class="table table-hover table-bordered align-middle small">
                <thead class="table-light"><tr>
                    <th>Poli</th><th>Jumlah</th>
                    <th title="Waktu tunggu admisi">T1 Tunggu Admisi</th>
                    <th title="Waktu layan admisi">T2 Layan Admisi</th>
                    <th title="Waktu tunggu poli">T3 Tunggu Poli</th>
                    <th title="Waktu layan poli">T4 Layan Poli</th>
                    <th title="Waktu tunggu farmasi">T5 Tunggu Farm</th>
                    <th title="Waktu layan farmasi">T6 Layan Farm</th>
                </tr></thead><tbody>`;
            res.response.list.forEach(item => {
                html += `<tr>
                    <td><strong>${item.namapoli}</strong><br><small class="text-muted">${item.kodepoli}</small></td>
                    <td class="text-center fw-bold">${item.jumlah_antrean}</td>
                    <td class="text-center">${Math.round(item.avg_waktu_task1/60)} mnt</td>
                    <td class="text-center">${Math.round(item.avg_waktu_task2/60)} mnt</td>
                    <td class="text-center">${Math.round(item.avg_waktu_task3/60)} mnt</td>
                    <td class="text-center">${Math.round(item.avg_waktu_task4/60)} mnt</td>
                    <td class="text-center">${Math.round(item.avg_waktu_task5/60)} mnt</td>
                    <td class="text-center">${Math.round(item.avg_waktu_task6/60)} mnt</td>
                </tr>`;
            });
            html += `</tbody></table><small class="text-muted">* Waktu dalam menit (rata-rata). T1-T6 = 6 task BPJS Antrean RS.</small>`;
            $('#dd_result').html(html);
        } else {
            $('#dd_result').html(`<div class="alert alert-warning">${res.metadata?.message || 'Tidak ada data.'}</div>`);
        }
    });
}

// ─── Tab 4: Dashboard Bulanan ─────────────────────────────────────────────────
function getMonthlyDashboard() {
    const bln = $('#dm_month').val(), thn = $('#dm_year').val(), waktu = $('#dm_waktu').val();
    $('#dm_result').html('<div class="text-center p-4"><div class="spinner-border text-primary spinner-border-sm"></div> Memuat...</div>');
    $.post('admin/bpjsjkn/get_dashboard_bulan', { bulan: bln, tahun: thn, waktu }, function(res) {
        if ((res.metadata?.code == 200 || res.metadata?.code == 1) && res.response?.list?.length) {
            let html = `<table class="table table-hover table-bordered align-middle small">
                <thead class="table-light"><tr><th>Tanggal</th><th>Poli</th><th>Jumlah</th>
                    <th>T1</th><th>T2</th><th>T3</th><th>T4</th><th>T5</th><th>T6</th>
                </tr></thead><tbody>`;
            res.response.list.forEach(item => {
                html += `<tr>
                    <td>${item.tanggal}</td>
                    <td><strong>${item.namapoli}</strong></td>
                    <td class="text-center">${item.jumlah_antrean}</td>
                    <td class="text-center">${Math.round(item.avg_waktu_task1/60)}m</td>
                    <td class="text-center">${Math.round(item.avg_waktu_task2/60)}m</td>
                    <td class="text-center">${Math.round(item.avg_waktu_task3/60)}m</td>
                    <td class="text-center">${Math.round(item.avg_waktu_task4/60)}m</td>
                    <td class="text-center">${Math.round(item.avg_waktu_task5/60)}m</td>
                    <td class="text-center">${Math.round(item.avg_waktu_task6/60)}m</td>
                </tr>`;
            });
            html += `</tbody></table>`;
            $('#dm_result').html(html);
        } else {
            $('#dm_result').html(`<div class="alert alert-warning">${res.metadata?.message || 'Tidak ada data.'}</div>`);
        }
    });
}

// Auto-load booking for today on page load
$(function() { loadBookingList(); });
</script>
