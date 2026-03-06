<div class="p-4">
    <div class="mb-5 text-center">
        <h2 class="fw-bold mb-0"><?= $title ?></h2>
        <p class="text-muted"><?= date('l, d F Y') ?> | <span id="clock" class="fw-bold">00:00:00</span></p>
    </div>

    <div class="row g-4">
        <?php foreach ($stats as $row) : ?>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-0 overflow-hidden" style="border-radius: 15px;">
                    <div class="bg-primary p-3 text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><?= $row['nama_poli'] ?></h5>
                        <span class="badge bg-white text-primary rounded-pill small"><?= $row['lokasi'] ?></span>
                    </div>
                    <div class="card-body p-4 text-center">
                        <div class="text-muted small mb-1">ANTRIAN SEKARANG</div>
                        <div class="display-1 fw-bold text-primary mb-3"><?= str_pad($row['current'], 3, '0', STR_PAD_LEFT) ?></div>
                        
                        <div class="d-flex justify-content-around mt-4 border-top pt-4">
                            <div>
                                <div class="text-muted small">TOTAL</div>
                                <div class="h4 fw-bold mb-0"><?= $row['total'] ?></div>
                            </div>
                            <div class="border-start"></div>
                            <div>
                                <div class="text-muted small">MENUNGGU</div>
                                <div class="h4 fw-bold mb-0 text-warning"><?= $row['waiting'] ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light border-0 py-3 text-center">
                        <button class="btn btn-sm btn-link text-decoration-none text-muted small" onclick="openTab('pendaftaran/index', 'Daftar Kunjungan')">Lihat Detail List <i class="fas fa-arrow-right ms-1"></i></button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    if (typeof clockInterval !== 'undefined') clearInterval(clockInterval);
    function updateClock() {
        const now = new Date();
        const time = now.toLocaleTimeString('id-ID', { hour12: false });
        const clockEl = document.getElementById('clock');
        if (clockEl) clockEl.textContent = time;
    }
    window.clockInterval = setInterval(updateClock, 1000);
    updateClock();
</script>

<style>
.display-1 { font-family: 'Inter', sans-serif; letter-spacing: -2px; }
</style>
