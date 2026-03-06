<?php

namespace App\Libraries\Bridging;

class BPJSService extends BaseBridgingService
{
    protected $serviceName = 'BPJS';
    protected $config;

    public function __construct()
    {
        parent::__construct();
        
        // Fetch config from DB
        // Dynamically pick the active BPJS configuration
        $this->config = $this->db->table('m_bpjs_config')->where('is_active', true)->get()->getRowArray();
        
        if (!$this->config) {
            $this->config = [];
        }
    }

    /**
     * Generate BPJS V-Claim Signature
     */
    protected function generateSignature()
    {
        $data = $this->config['consid'];
        $secretKey = $this->config['secret'];
        
        date_default_timezone_set('UTC');
        $timestamp = (string)time();
        
        $signature = hash_hmac('sha256', $data . "&" . $timestamp, $secretKey, true);
        $encodedSignature = base64_encode($signature);

        return [
            'X-cons-id'   => $data,
            'X-timestamp' => $timestamp,
            'X-signature' => $encodedSignature
        ];
    }

    /**
     * Decrypt BPJS Response (V-Claim 2.0)
     * 
     * Flow: AES-256-CBC decrypt → LZ-String decompressFromEncodedURIComponent
     * Key: consid + secret + timestamp (concatenated)
     */
    protected function decrypt(string $string, string $timestamp)
    {
        $key = $this->config['consid'] . $this->config['secret'] . $timestamp;
        $hash = hash('sha256', $key, true);
        $iv = substr($hash, 0, 16);
        
        $output = openssl_decrypt(base64_decode($string), 'AES-256-CBC', $hash, OPENSSL_RAW_DATA, $iv);
        
        if ($output === false) {
            log_message('error', '[BPJS] AES decrypt failed');
            return null;
        }

        // Step 1: Check if decrypted output is already valid JSON (some BPJS endpoints don't compress)
        $jsonCheck = json_decode($output, true);
        if (json_last_error() === JSON_ERROR_NONE && $jsonCheck !== null) {
            return $output;
        }

        // Step 2: Try LZ-String decompressFromEncodedURIComponent (per BPJS docs)
        require_once APPPATH . 'Libraries/Bridging/BpjsLzStringNative.php';
        
        $decompressed = \BpjsLzStringNative::decompressFromEncodedURIComponent($output);
        if ($decompressed !== null && $decompressed !== '' && $decompressed !== false) {
            return $decompressed;
        }

        // Step 3: Try plain LZ-String decompress as fallback
        $decompressed = \BpjsLzStringNative::decompress($output);
        if ($decompressed !== null && $decompressed !== '' && $decompressed !== false) {
            return $decompressed;
        }

        // Step 4: Return raw decrypted output as last resort
        log_message('warning', '[BPJS] LZ-String decompress failed, returning raw decrypted output');
        return $output;
    }


    /**
     * V-Claim Request Helper
     */
    public function vclaimRequest(string $method, string $endpoint, array $payload = [])
    {
        // Validate config before calling API
        if (empty($this->config['consid']) || empty($this->config['secret']) || empty($this->config['base_url_vclaim'])) {
            return [
                'metaData' => [
                    'code'    => '500',
                    'message' => 'Konfigurasi BPJS V-Claim belum diatur. Silakan isi Consumer ID, Secret Key, dan Base URL di menu Admin > BPJS Tools.'
                ]
            ];
        }

        $headers = $this->generateSignature();
        $headers['user_key'] = $this->config['user_key_vclaim'] ?? '';
        $headers['Content-Type'] = 'application/json';

        $url = $this->config['base_url_vclaim'] . '/' . $endpoint;
        
        $options = ['headers' => $headers];
        if (!empty($payload)) {
            $options['json'] = $payload;
        }

        $response = $this->sendRequest($method, $url, $options);

        // BPJS returns encrypted data in 'response' key if status is 200
        if ($response['status_code'] === 200 && isset($response['json']['response']) && is_string($response['json']['response'])) {
            $decrypted = $this->decrypt($response['json']['response'], $headers['X-timestamp']);
            $response['json']['response'] = json_decode($decrypted, true);
        }

        // BPJS sometimes returns plain text errors for auth/connection failures (e.g., "Authentication failed")
        if (empty($response['json']) && !empty($response['body'])) {
            return ['metaData' => ['code' => $response['status_code'], 'message' => trim($response['body'])]];
        }

        return $response['json'] ?? ['metaData' => ['code' => $response['status_code'], 'message' => 'Gagal memproses response API V-Claim']];
    }
}
