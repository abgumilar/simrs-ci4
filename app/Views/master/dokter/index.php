<div class="p-4" id="master-dokter-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-gradient-primary small-caps"><i class="fas fa-user-md me-2"></i>Master Praktisi (Dokter)</h4>
            <div class="text-muted small">Kelola data dokter, tenaga medis, dan kredensial bridging.</div>
        </div>
        <button onclick="openTab('master/dokter/create', 'Tambah Dokter Baru')" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-plus me-2"></i> Tambah Dokter
        </button>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100" id="table-dokter">
                    <thead class="bg-light text-muted small text-uppercase fw-bold">
                        <tr>
                            <th class="py-3 px-4">Informasi Dokter</th>
                            <th class="py-3">Keahlian & Unit</th>
                            <th class="py-3">Kredensial Bridging</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dokter as $d) : ?>
                            <tr>
                                <td class="px-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle bg-soft-primary text-primary fw-bold me-3 shadow-sm">
                                            <?= substr($d['fullname'], 0, 1) ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark"><?= $d['fullname'] ?></div>
                                            <div class="text-muted sx-small">SIP: <span class="text-primary"><?= $d['sip'] ?: '-' ?></span></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark small text-uppercase"><?= $d['specialis'] ?></div>
                                    <div class="badge bg-soft-success text-success border-0 py-1 px-2 mt-1 fw-normal" style="font-size: 10px;">
                                        <i class="fas fa-clinic-medical me-1"></i> <?= $d['nama_poli'] ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <div class="px-2 py-1 rounded bg-light border border-dashed text-center" style="min-width: 80px;">
                                            <div class="text-muted sx-small" style="font-size: 8px;">KODE BPJS</div>
                                            <div class="small fw-bold text-dark"><?= $d['kode_bpjs'] ?: '-' ?></div>
                                        </div>
                                        <div class="px-2 py-1 rounded bg-light border border-dashed text-center" style="min-width: 80px;">
                                            <div class="text-muted sx-small" style="font-size: 8px;">IHS PRACTITIONER</div>
                                            <div class="small fw-bold text-success"><?= $d['ihs_practitioner'] ?: '-' ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button onclick="openTab('master/dokter/edit/<?= $d['id'] ?>', 'Edit Dokter: <?= $d['fullname'] ?>')" class="btn btn-sm btn-light text-warning border-0 shadow-none">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="hapusDokter(<?= $d['id'] ?>, '<?= $d['fullname'] ?>')" class="btn btn-sm btn-light text-danger border-0 shadow-none">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const $container = $('#master-dokter-container');
    
    if ($.fn.DataTable.isDataTable($container.find('#table-dokter'))) {
        $container.find('#table-dokter').DataTable().destroy();
    }

    $container.find('#table-dokter').DataTable({
        pageLength: 10,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari Nama Dokter, SIP, atau Spesialis...",
            lengthMenu: "_MENU_ per hal",
            info: "Data _START_ - _END_ dari _TOTAL_",
            paginate: {
                previous: '<i class="fas fa-chevron-left"></i>',
                next: '<i class="fas fa-chevron-right"></i>'
            }
        },
        dom: "<'d-flex justify-content-between align-items-center mb-3'<'d-flex align-items-center'l><f>>rt<'d-flex justify-content-between align-items-center mt-3'ip>"
    });

    window.hapusDokter = function(id, nama) {
        Swal.fire({
            title: 'Hapus Praktisi?',
            text: `Anda yakin ingin menghapus ${nama}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url('master/dokter/delete') ?>/' + id, function(res) {
                    if (res.status === 'success') {
                        Swal.fire('Berhasil!', res.message, 'success');
                        openTab('master/dokter', 'Data Dokter');
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
    .avatar-circle { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; }
    .bg-soft-primary { background-color: rgba(0, 166, 81, 0.1); }
    .bg-soft-success { background-color: rgba(16, 185, 129, 0.1); }
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
</style>
