<?php

namespace App\Libraries\Bridging;

class AntreanService extends BPJSService
{
    protected $serviceName = 'BPJS-Antrean';

    /**
     * Antrean Request Helper
     */
    public function antreanRequest(string $method, string $endpoint, array $payload = [])
    {
        // Validate config
        if (empty($this->config['consid']) || empty($this->config['secret']) || empty($this->config['base_url_antrean'])) {
            return [
                'metadata' => [
                    'code'    => 500,
                    'message' => 'Konfigurasi BPJS Antrean belum diatur.'
                ]
            ];
        }

        $headers = $this->generateSignature();
        $headers['user_key'] = $this->config['user_key_antrean'] ?? '';
        $headers['Content-Type'] = 'application/x-www-form-urlencoded';

        $url = $this->config['base_url_antrean'] . '/' . $endpoint;
        
        $options = ['headers' => $headers];
        if (!empty($payload)) {
            // Antrean usually uses JSON for POST, but some endpoints might differ. 
            // Most modern Antrean RS endpoints use JSON.
            $options['json'] = $payload;
        }

        $response = $this->sendRequest($method, $url, $options);

        // Decrypt if necessary (Antrean RS responses are usually encrypted same as V-Claim)
        if ($response['status_code'] === 200 && isset($response['json']['response']) && is_string($response['json']['response'])) {
            $decrypted = $this->decrypt($response['json']['response'], $headers['X-timestamp']);
            $response['json']['response'] = json_decode($decrypted, true);
        }

        return $response['json'] ?? ['metadata' => ['code' => $response['status_code'], 'message' => 'Gagal memproses response API Antrean']];
    }

    /**
     * Get List Booking by Tanggal
     * Endpoint: antrean/pendaftaran/tanggal/{tanggal}
     */
    public function getBookingByTanggal(string $tanggal)
    {
        return $this->antreanRequest('GET', "antrean/pendaftaran/tanggal/{$tanggal}");
    }

    /**
     * Get Dashboard Per Tanggal (Diagnostic)
     * Endpoint: dashboard/waktutunggu/tanggal/{tanggal}/waktu/{waktu}
     */
    public function getDashboardTanggal(string $tanggal, string $waktu = 'rs')
    {
        return $this->antreanRequest('GET', "dashboard/waktutunggu/tanggal/{$tanggal}/waktu/{$waktu}");
    }

    /**
     * Get Dashboard Per Bulan (Diagnostic)
     * Endpoint: dashboard/waktutunggu/bulan/{bulan}/tahun/{tahun}/waktu/{waktu}
     */
    public function getDashboardBulan(string $bulan, string $tahun, string $waktu = 'rs')
    {
        return $this->antreanRequest('GET', "dashboard/waktutunggu/bulan/{$bulan}/tahun/{$tahun}/waktu/{$waktu}");
    }

    /**
     * Get Antrean Per Tanggal (Complete List)
     * Endpoint: antrean/pendaftaran/tanggal/{tanggal}
     */
    public function getAntreanTanggal(string $tanggal)
    {
        return $this->antreanRequest('GET', "antrean/pendaftaran/tanggal/{$tanggal}");
    }

    /**
     * Get Antrean Per Kode Booking
     * Endpoint: antrean/pendaftaran/kodebooking/{kodebooking}
     */
    public function getAntreanKodeBooking(string $kodebooking)
    {
        return $this->antreanRequest('GET', "antrean/pendaftaran/kodebooking/{$kodebooking}");
    }

    /**
     * Get Task Logs by Kode Booking
     * Endpoint: antrean/getlisttask
     */
    public function getTaskLogs(string $kodebooking)
    {
        return $this->antreanRequest('POST', "antrean/getlisttask", ['kodebooking' => $kodebooking]);
    }

    /**
     * Update Waktu Antrean (Task Id)
     * Used for check-in and other transitions
     */
    public function updateWaktuAntrean(array $data)
    {
        return $this->antreanRequest('POST', "antrean/updatewaktu", $data);
    }
}
