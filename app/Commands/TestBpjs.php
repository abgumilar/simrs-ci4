<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\Bridging\BPJSService;

class TestBpjs extends BaseCommand
{
    protected $group       = 'Bridging';
    protected $name        = 'bridge:bpjs';
    protected $description = 'Uji coba teknis bridging BPJS (Signature, Headers, & Response Raw)';
    protected $usage       = 'bridge:bpjs [no_kartu]';
    protected $arguments   = [
        'no_kartu' => 'Nomor kartu BPJS yang akan diuji',
    ];

    public function run(array $params)
    {
        $no_kartu = $params[0] ?? '0001234567890';
        $tgl = date('Y-m-d');
        
        CLI::write("--- BPJS BRIDGING DIAGNOSTIC ---", "yellow");
        
        $bpjs = new BPJSService();
        
        // 1. Check Configuration
        $db = \Config\Database::connect();
        $config = $db->table('m_bpjs_config')->where('env', 'Trial')->get()->getRowArray();
        
        if (!$config) {
            CLI::error("Konfigurasi BPJS (Trial) tidak ditemukan di tabel m_bpjs_config!");
            return;
        }

        CLI::write("Environment : " . $config['env']);
        CLI::write("Cons ID     : " . $config['consid']);
        CLI::write("Base URL    : " . $config['base_url_vclaim']);
        CLI::write("---", "dark_gray");

        // 2. Generate Signature Manual (for display)
        date_default_timezone_set('UTC');
        $timestamp = (string)time();
        $signature = hash_hmac('sha256', $config['consid'] . "&" . $timestamp, $config['secret'], true);
        $encodedSignature = base64_encode($signature);

        CLI::write("TIMESTAMP   : " . $timestamp, "cyan");
        CLI::write("SIGNATURE   : " . $encodedSignature, "cyan");
        CLI::write("---", "dark_gray");

        // 3. Execution (Real Call)
        CLI::write("Memanggil API BPJS (Peserta/nokartu/{$no_kartu})...", "yellow");
        
        try {
            $result = $bpjs->vclaimRequest('GET', "Peserta/nokartu/{$no_kartu}/tglSEP/{$tgl}");

            CLI::write("RESULT METADATA:", "green");
            print_r($result['metaData'] ?? $result);
            
            if (isset($result['response'])) {
                CLI::write("\nRESPONSE DATA:", "green");
                print_r($result['response']);
            }
        } catch (\Throwable $e) {
            CLI::error("EXCEPTION DIALAMI:");
            CLI::error($e->getMessage());
            CLI::write($e->getTraceAsString(), "dark_gray");
        }

        CLI::write("---", "dark_gray");
        CLI::write("Cek tabel 't_api_logs' untuk melihat Raw Payload & Execution Time.", "white");
    }
}
