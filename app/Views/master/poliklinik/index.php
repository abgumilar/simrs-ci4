<div class="p-4" id="master-poli-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-gradient-primary">Master Poliklinik</h4>
            <div class="text-muted small">Kelola unit layanan dan poliklinik rumah sakit.</div>
        </div>
        <button onclick="openTab('master/poliklinik/create', 'Tambah Poli Baru')" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="fas fa-plus me-2"></i> Tambah Poli
        </button>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
        <div class="card-body p-0">
            <div class="table-responsive p-4">
                <table class="table table-hover align-middle mb-0 w-100" id="table-poliklinik">
                    <thead class="bg-light text-muted small text-uppercase fw-bold">
                        <tr>
                            <th class="py-3">Nama Poliklinik</th>
                            <th class="py-3">Lokasi / Gedung</th>
                            <th class="py-3">Kode BPJS</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($poliklinik as $p) : ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-soft-primary text-primary rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-hospital"></i>
                                        </div>
                                        <div class="fw-bold text-dark"><?= $p['nama_poli'] ?></div>
                                    </div>
                                </td>
                                <td><span class="text-muted small"><i class="fas fa-map-marker-alt me-1"></i> <?= $p['lokasi'] ?></span></td>
                                <td>
                                    <div class="badge bg-soft-info text-info px-3 py-2" style="font-size: 11px;">
                                        <?= $p['kode_bpjs'] ?: 'None' ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <button onclick="openTab('master/poliklinik/edit/<?= $p['id'] ?>', 'Edit Poli: <?= $p['nama_poli'] ?>')" class="btn btn-sm btn-light text-warning border-0 shadow-none">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="hapusPoli(<?= $p['id'] ?>, '<?= $p['nama_poli'] ?>')" class="btn btn-sm btn-light text-danger border-0 shadow-none">
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
    const $container = $('#master-poli-container');
    const table = $container.find('#table-poliklinik').DataTable({
        pageLength: 10,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari Poliklinik...",
            lengthMenu: "_MENU_ data per hal",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            paginate: {
                previous: '<i class="fas fa-chevron-left"></i>',
                next: '<i class="fas fa-chevron-right"></i>'
            }
        },
        dom: "<'d-flex justify-content-between align-items-center mb-3'<'d-flex align-items-center'l><f>>rt<'d-flex justify-content-between align-items-center mt-3'ip>"
    });

    window.hapusPoli = function(id, nama) {
        Swal.fire({
            title: 'Hapus Poliklinik?',
            text: `Anda yakin ingin menghapus ${nama}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('<?= base_url('master/poliklinik/delete') ?>/' + id, function(res) {
                    if (res.status === 'success') {
                        Swal.fire('Berhasil!', res.message, 'success');
                        openTab('master/poliklinik', 'Data Poliklinik');
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
    .bg-soft-primary { background-color: rgba(0, 166, 81, 0.1); }
    .bg-soft-info { background-color: rgba(59, 130, 246, 0.1); }
    .text-gradient-primary {
        background: linear-gradient(45deg, #00a651, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .dataTables_filter input {
        border-radius: 20px;
        padding-left: 15px;
        border: 1px solid #e2e8f0;
        outline: none;
    }
</style>
