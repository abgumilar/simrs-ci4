<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0"><?= $title ?></h4>
            <div class="text-muted small">Total Kunjungan Rawat Jalan Hari Ini: <strong><?= count($pendaftaran) ?></strong></div>
        </div>
        <div>
            <button onclick="refreshCurrentTab()" class="btn btn-light btn-sm rounded-pill me-2">
                <i class="fas fa-sync-alt"></i>
            </button>
            <a href="javascript:void(0)" onclick="openTab('pendaftaran/create', 'Pendaftaran Baru')" class="btn btn-primary shadow-sm rounded-pill px-4">
                <i class="fas fa-plus me-2"></i> Pendaftaran Baru
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase fw-bold">
                    <tr>
                        <th class="ps-4 py-3">No. Antrian</th>
                        <th class="py-3">No. Reg / Pasien</th>
                        <th class="py-3">Poliklinik</th>
                        <th class="py-3">Dokter DPJP</th>
                        <th class="py-3">Waktu / Sumber</th>
                        <th class="py-3">Status</th>
                        <th class="text-end pe-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($pendaftaran)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted"> Belum ada pendaftaran untuk hari ini. </td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($pendaftaran as $row): ?>
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="queue-number bg-soft-primary text-primary fw-bold rounded-3 text-center py-2" style="width: 60px;">
                                    <?= str_pad($row['no_antrian'], 3, '0', STR_PAD_LEFT) ?>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="fw-bold text-dark"><?= $row['nama_pasien'] ?></div>
                                <div class="text-muted small"><?= $row['no_reg'] ?> • <span class="badge bg-light text-dark fw-normal"><?= $row['norm'] ?></span></div>
                            </td>
                            <td class="py-3">
                                <div class="text-dark fw-semibold"><?= $row['nama_poli'] ?></div>
                            </td>
                            <td class="py-3">
                                <div class="text-muted small"><?= $row['nama_dokter'] ?></div>
                            </td>
                            <td class="py-3">
                                <div class="text-dark small"><i class="far fa-clock me-1"></i> <?= date('H:i', strtotime($row['tgl_registrasi'])) ?></div>
                                <div class="mt-1">
                                    <span class="badge bg-soft-info text-info small rounded-pill px-2">
                                        <?= $row['sumber_daftar'] ?? 'Loket' ?>
                                    </span>
                                </div>
                            </td>
                            <td class="py-3">
                                <?php if($row['status_reg'] == 'Active'): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Antri</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted rounded-pill px-3"><?= $row['status_reg'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4 py-3">
                                <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                    <button class="btn btn-white btn-sm px-3 border-end" title="Lihat Detail">
                                        <i class="fas fa-eye text-primary"></i>
                                    </button>
                                    <button class="btn btn-white btn-sm px-3" title="Cetak Kartu">
                                        <i class="fas fa-print text-muted"></i>
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

<style>
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); }
    .bg-soft-info { background-color: rgba(13, 202, 240, 0.1); }
    .btn-white { background-color: #fff; border: 1px solid #f1f3f5; }
    .btn-white:hover { background-color: #f8f9fa; }
</style>
