<div class="p-4" id="booking-jkn-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-primary"><i class="fas fa-calendar-check me-2"></i>Booking Mobile JKN</h4>
            <div class="text-muted small">Daftar reservasi pasien dari aplikasi Mobile JKN hari ini.</div>
        </div>
        <div class="d-flex gap-2">
            <input type="date" id="filter-date-booking" class="form-control rounded-pill border-0 shadow-sm px-3" value="<?= date('Y-m-d') ?>">
            <button onclick="refreshBooking()" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-sync-alt me-2"></i> Refresh Data
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="table-booking">
                <thead class="bg-light text-muted small text-uppercase fw-bold">
                    <tr>
                        <th class="ps-4 py-3">Kode Booking</th>
                        <th class="py-3">Pasien / No. Kartu</th>
                        <th class="py-3">Poliklinik / Dokter</th>
                        <th class="py-3">Estimasi Melayani</th>
                        <th class="py-3">Status JKN</th>
                        <th class="text-end pe-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody id="list-booking-jkn">
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="spinner-border text-primary spinner-border-sm me-2" role="status"></div>
                            <span class="text-muted">Memuat data booking dari BPJS...</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const $container = $('#booking-jkn-container');
    
    window.refreshBooking = function() {
        const tgl = $container.find('#filter-date-booking').val();
        const $tbody = $container.find('#list-booking-jkn');
        
        $tbody.html(`
            <tr>
                <td colspan="6" class="text-center py-5">
                    <div class="spinner-border text-primary spinner-border-sm me-2" role="status"></div>
                    <span class="text-muted">Mengambil data dari server BPJS...</span>
                </td>
            </tr>
        `);

        $.get('<?= base_url('pendaftaran/get_booking_jkn') ?>?tanggal=' + tgl, function(res) {
            if (res.metadata && res.metadata.code == 1) {
                let html = '';
                const data = res.response || [];
                
                if (data.length === 0) {
                    html = '<tr><td colspan="6" class="text-center py-5 text-muted">Tidak ada booking untuk tanggal ini.</td></tr>';
                } else {
                    data.forEach(item => {
                        html += `
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-bold text-primary">${item.kodebooking}</div>
                                    <div class="sx-small text-muted">${item.nomorantrean}</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">${item.nama}</div>
                                    <div class="small text-muted">${item.nomorkartu} • NIK: ${item.nik}</div>
                                </td>
                                <td>
                                    <div class="small fw-bold">${item.namapoli}</div>
                                    <div class="sx-small text-muted">${item.namadokter}</div>
                                </td>
                                <td>
                                    <div class="small"><i class="far fa-clock me-1"></i> ${item.estimasidilayani || '-'}</div>
                                </td>
                                <td>
                                    <span class="badge bg-soft-success text-success rounded-pill px-3">${item.status}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <button onclick="checkinBooking('${item.kodebooking}')" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="fas fa-sign-in-alt me-1"></i> Check-in
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                }
                $tbody.html(html);
            } else {
                $tbody.html(`<tr><td colspan="6" class="text-center py-5 text-danger small">${res.metadata ? res.metadata.message : 'Gagal memuat data'}</td></tr>`);
            }
        });
    }

    window.checkinBooking = function(kode) {
        Swal.fire({
            title: 'Check-in Pasien?',
            text: `Proses check-in untuk kode booking ${kode}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Check-in'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url('pendaftaran/checkin_jkn') ?>', { kodebooking: kode }, function(res) {
                    if (res.status === 'success') {
                        Swal.fire('Berhasil', res.message, 'success').then(() => {
                            // Open registration form with pre-filled data or redirected NORM
                            if (res.norm) {
                                openTab('pendaftaran/rajal?norm=' + res.norm, 'Registrasi ' + res.norm);
                            } else {
                                refreshBooking();
                            }
                        });
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                });
            }
        });
    }

    // Initial load
    refreshBooking();
});
</script>

<style>
    .bg-soft-success { background-color: rgba(25, 135, 84, 0.1); }
    .sx-small { font-size: 10px; font-weight: bold; }
</style>
