<div class="p-4" id="master-pasien-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-gradient-primary small-caps"><i class="fas fa-users-cog me-2"></i>Master Data Pasien</h4>
            <div class="text-muted small">Kelola repositori data pasien rumah sakit secara terpusat.</div>
        </div>
        <button onclick="openTab('master/pasien/create', 'Review Pasien Baru')" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-plus me-2"></i> Pasien Baru
        </button>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100" id="table-pasien">
                    <thead class="bg-light text-muted small text-uppercase">
                        <tr>
                            <th class="py-3">No. Register (NRM)</th>
                            <th class="py-3">Nama Pasien</th>
                            <th class="py-3">NIK / Identitas</th>
                            <th class="py-3 border-start">Alamat Lengkap</th>
                            <th class="py-3 text-center">Aksi Pasien</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data loaded via AJAX SSP -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const $container = $('#master-pasien-container');
    const tableId = '#table-pasien';
    
    // Prevent multiple initializations in tab system
    if ($.fn.DataTable.isDataTable($container.find(tableId))) {
        $container.find(tableId).DataTable().destroy();
    }

    const table = $container.find(tableId).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= base_url('master/pasien') ?>',
            type: 'GET'
        },
        columns: [
            { 
                data: 'norm',
                render: function(data) {
                    return `<span class="fw-bold text-primary">${data}</span>`;
                }
            },
            { 
                data: 'nama_pasien',
                render: function(data, type, row) {
                    const jk = row.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
                    return `<div>
                                <div class="fw-bold text-dark text-uppercase">${data}</div>
                                <span class="text-muted small">${jk}</span>
                            </div>`;
                }
            },
            { 
                data: 'nik',
                render: function(data) {
                    return `<div>
                                <div class="small fw-bold text-dark">${data || '-'}</div>
                                <div class="text-muted sx-small" style="font-size: 10px;">NIK</div>
                            </div>`;
                }
            },
            { 
                data: 'alamat',
                render: function(data, type, row) {
                    const detail = [row.kelurahan, row.kecamatan].filter(n => n).join(', ');
                    return `<div class="text-muted small text-truncate border-start ps-3" style="max-width: 250px;">
                                ${data || '-'} <br> <small>${detail}</small>
                            </div>`;
                }
            },
            {
                data: null,
                orderable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    return `<div class="d-flex justify-content-center gap-1">
                                <button onclick="openTab('master/pasien/edit/${row.id}', 'Edit Pasien: ${row.norm}')" class="btn btn-sm btn-light text-warning border-0 shadow-none" title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="openTab('pendaftaran/create?norm=${row.norm}', 'Daftarkan: ${row.norm}')" class="btn btn-sm btn-light text-primary border-0 shadow-none" title="Daftarkan Berobat">
                                    <i class="fas fa-hospital-user"></i>
                                </button>
                            </div>`;
                }
            }
        ],
        pageLength: 10,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari Nama, RM, atau Identitas...",
            lengthMenu: "_MENU_ per hal",
            info: "Data _START_ - _END_ dari _TOTAL_",
            paginate: {
                previous: '<i class="fas fa-chevron-left"></i>',
                next: '<i class="fas fa-chevron-right"></i>'
            }
        },
        dom: "<'d-flex justify-content-between align-items-center mb-3'<'d-flex align-items-center'l><f>>rt<'d-flex justify-content-between align-items-center mt-3'ip>"
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
    .dataTables_filter input {
        border-radius: 20px;
        padding-left: 15px;
        border: 1px solid #e2e8f0;
        min-width: 250px;
        outline: none;
    }
    .sx-small { font-size: 10px; font-weight: bold; text-transform: uppercase; }
    #table-pasien thead th { border-bottom: none; }
    #table-pasien tbody tr:hover { background-color: rgba(0, 166, 81, 0.02); }
</style>
