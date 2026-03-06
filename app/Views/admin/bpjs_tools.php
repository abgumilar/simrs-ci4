<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0"><?= $title ?></h4>
            <div class="text-muted small">Diagnostic Tools for V-Claim 2.0 Integration</div>
        </div>
    </div>

    <div class="row">
        <!-- SIGNATURE GENERATOR -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-key text-warning me-2"></i> Signature Generator</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-4">
                        Hitung HMAC-SHA256 berdasarkan konfigurasi saat ini (ID: 1). Kosongkan timestamp untuk menggunakan waktu aktif server (UTC).
                    </p>
                    
                    <form id="form-signature">
                        <div class="mb-3">
                            <label class="form-label small text-muted">Custom Timestamp (Opsional)</label>
                            <input type="text" class="form-control" name="timestamp" id="custom-timestamp" placeholder="Contoh: 1772293917">
                        </div>
                        <button type="button" class="btn btn-primary btn-sm w-100 mb-3" id="btn-generate">Generate Signature</button>
                    </form>

                    <div id="signature-result" class="bg-light p-3 rounded-3 d-none" style="font-family: monospace; font-size: 12px; word-break: break-all;">
                        <div class="mb-2"><span class="text-muted fw-bold">Cons ID: </span><span id="res-consid" class="text-dark"></span></div>
                        <div class="mb-2"><span class="text-muted fw-bold">Secret: </span><span id="res-secret" class="text-dark"></span></div>
                        <div class="mb-2"><span class="text-muted fw-bold">X-timestamp: </span><span id="res-timestamp" class="text-info font-weight-bold"></span></div>
                        <div class="mb-2"><strong class="text-success">X-signature: </strong><br><span id="res-signature" class="text-dark"></span></div>
                        <div class="mb-0"><strong class="text-primary">URL Encoded: </strong><br><span id="res-url" class="text-dark"></span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- API CHECKER -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-satellite-dish text-info me-2"></i> Live API Checker</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-4">
                        Uji coba koneksi langsung ke endpoint <code>Peserta/nokartu</code> atau <code>Peserta/nik</code>.
                    </p>

                    <form id="form-peserta">
                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <select class="form-select" id="cek-jenis">
                                    <option value="nokartu">No. Kartu</option>
                                    <option value="nik">NIK</option>
                                </select>
                            </div>
                            <div class="col-8">
                                <input type="text" class="form-control" id="cek-nomor" placeholder="Masukkan Nomor..." required>
                            </div>
                        </div>
                        <button type="button" class="btn btn-info text-white btn-sm w-100 mb-3" id="btn-cek">Cek Peserta (Live)</button>
                    </form>

                    <div class="position-relative">
                        <div id="loading-peserta" class="text-center p-4 d-none">
                            <div class="spinner-border text-info spinner-border-sm mb-2"></div>
                            <div class="small text-muted">Menghubungi server BPJS...</div>
                        </div>
                        <div id="peserta-result" class="bg-dark text-light p-3 rounded-3 d-none overflow-auto" style="font-family: monospace; font-size: 11px; max-height: 250px;">
                            <pre id="json-output" class="mb-0"></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#btn-generate').click(function() {
        const btn = $(this);
        const originalText = btn.text();
        btn.html('<i class="fas fa-spinner fa-spin"></i> Processing...').prop('disabled', true);
        
        $.post(baseUrl + '/admin/bpjs/signature', {
            timestamp: $('#custom-timestamp').val()
        }, function(res) {
            if(res.status === 'success') {
                $('#res-consid').text(res.consid);
                $('#res-secret').text(res.secret);
                $('#res-timestamp').text(res.timestamp);
                $('#res-signature').text(res.signature);
                $('#res-url').text(res.url_encoded);
                $('#signature-result').removeClass('d-none');
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }).fail(function() {
            Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
        }).always(function() {
            btn.html(originalText).prop('disabled', false);
        });
    });

    $('#btn-cek').click(function() {
        const nomor = $('#cek-nomor').val();
        if(!nomor) return Swal.fire('Peringatan', 'Nomor tidak boleh kosong', 'warning');

        $('#peserta-result').addClass('d-none');
        $('#loading-peserta').removeClass('d-none');
        $('#btn-cek').prop('disabled', true);

        $.post(baseUrl + '/admin/bpjs/cek', {
            nomor: nomor,
            jenis: $('#cek-jenis').val()
        }, function(res) {
            $('#loading-peserta').addClass('d-none');
            $('#peserta-result').removeClass('d-none');
            $('#btn-cek').prop('disabled', false);
            
            // Pretty print JSON
            $('#json-output').html(JSON.stringify(res, undefined, 4));
            
        }).fail(function(xhr) {
            $('#loading-peserta').addClass('d-none');
            $('#btn-cek').prop('disabled', false);
            Swal.fire('Error Request', 'Gagal memanggil controller API Cek', 'error');
        });
    });
</script>
