<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card p-4 border-0 shadow-sm" style="border-left: 4px solid #3b82f6 !important;">
            <div class="text-muted small fw-bold">TOTAL PASIEN HARI INI</div>
            <div class="h2 fw-bold mt-2">124</div>
            <div class="text-success small"><i class="fas fa-arrow-up"></i> 12% dari kemarin</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-4 border-0 shadow-sm" style="border-left: 4px solid #10b981 !important;">
            <div class="text-muted small fw-bold">PASIEN DILAYANI</div>
            <div class="h2 fw-bold mt-2">86</div>
            <div class="text-muted small">69% Selesai</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-4 border-0 shadow-sm" style="border-left: 4px solid #f59e0b !important;">
            <div class="text-muted small fw-bold">ANTRIAN BERJALAN</div>
            <div class="h2 fw-bold mt-2">38</div>
            <div class="text-warning small">Status: Sibuk</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-4 border-0 shadow-sm" style="border-left: 4px solid #6366f1 !important;">
            <div class="text-muted small fw-bold">TOTAL BILLING</div>
            <div class="h2 fw-bold mt-2">Rp 4.2M</div>
            <div class="text-muted small">Rekapitulasi Harian</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card p-4 border-0 shadow-sm">
            <h5 class="fw-bold mb-4">Grafik Kunjungan Pasien (7 Hari Terakhir)</h5>
            <div style="height: 350px; background: #f9fafb; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #9ca3af; border: 2px dashed #e5e7eb;">
                <div class="text-center">
                    <i class="fas fa-chart-line fa-3x mb-3"></i>
                    <p>Integrate Chart.js/ApexCharts here</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-4 border-0 shadow-sm mb-4">
            <h5 class="fw-bold mb-4">Pengumuman & Info</h5>
            <div class="d-flex gap-3 mb-4">
                <div class="bg-primary-subtle text-primary p-2 rounded d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                    <i class="fas fa-info-circle fa-lg"></i>
                </div>
                <div>
                    <div class="fw-bold mb-1">Maintenance Server</div>
                    <div class="text-muted small">Update keamanan basis data malam ini pukul 23:00 WIB.</div>
                </div>
            </div>
            <div class="d-flex gap-3">
                <div class="bg-success-subtle text-success p-2 rounded d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                    <i class="fas fa-check-circle fa-lg"></i>
                </div>
                <div>
                    <div class="fw-bold mb-1">Akreditasi Paripurna</div>
                    <div class="text-muted small">Selamat! Unit Laboratorium mendapatkan nilai A+.</div>
                </div>
            </div>
        </div>

        <div class="card p-4 border-0 shadow-sm">
            <h5 class="fw-bold mb-3">Pintasan Cepat</h5>
            <div class="d-grid gap-2">
                <a href="<?= base_url('registration') ?>" class="btn btn-light text-start border d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-user-plus me-2 text-primary"></i> Daftar Pasien Baru</span>
                    <i class="fas fa-chevron-right small text-muted"></i>
                </a>
                <a href="<?= base_url('clinical/rajal') ?>" class="btn btn-light text-start border d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-stethoscope me-2 text-success"></i> Antrian Rawat Jalan</span>
                    <i class="fas fa-chevron-right small text-muted"></i>
                </a>
                <a href="<?= base_url('billing/kasir') ?>" class="btn btn-light text-start border d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-wallet me-2 text-warning"></i> Kasir Pembayaran</span>
                    <i class="fas fa-chevron-right small text-muted"></i>
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
