<?php

namespace App\Libraries\Bridging;

use Config\Database;
use Config\Services;

abstract class BaseBridgingService
{
    protected $client;
    protected $db;
    protected $serviceName = 'Generic';

    public function __construct()
    {
        $this->client = Services::curlrequest([
            'headers' => [
                'Accept' => 'application/json',
            ],
            'timeout' => 30,
            'http_errors' => false, // We handle errors manually
            'verify' => false,      // Disable SSL verification for dev/local
        ]);
        $this->db = Database::connect();
    }

    /**
     * Send HTTP Request with automatic logging
     */
    protected function sendRequest(string $method, string $url, array $options = [])
    {
        $startTime = microtime(true);
        $requestPayload = isset($options['json']) ? json_encode($options['json']) : null;
        
        $statusCode = 500;
        $responseBody = '';
        $jsonResponse = null;

        try {
            $options['http_errors'] = false; // Force CI4 curl not to throw exception on 4xx/5xx
            $response = $this->client->request($method, $url, $options);
            
            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody();
            $jsonResponse = json_decode($responseBody, true);
            
        } catch (\Exception $e) {
            $responseBody = $e->getMessage();
            $statusCode = $e->getCode() ?: 500;
        }

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;

        $this->logApiCall($method, $url, $requestPayload, $responseBody, $statusCode, $executionTime);

        return [
            'status_code' => $statusCode,
            'body'        => $responseBody,
            'json'        => $jsonResponse,
        ];
    }

    /**
     * Private logger to t_api_logs
     */
    private function logApiCall($method, $url, $request, $response, $statusCode, $time)
    {
        try {
            $this->db->table('t_api_logs')->insert([
                'service_name'     => $this->serviceName,
                'endpoint'         => $url,
                'method'           => $method,
                'request_payload'  => $request,
                'response_payload' => $response,
                'status_code'      => $statusCode,
                'execution_time'   => $time,
                'user_id'          => (is_cli() || !session()->has('user_id')) ? 0 : session()->get('user_id'),
                'created_at'       => date('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            // Silently fail logging to not disrupt the main flow
            log_message('error', '[Bridging] Failed to log API call: ' . $e->getMessage());
        }
    }
}
