<?php

namespace App\Libraries\Bridging;

class SatuSehatService extends BaseBridgingService
{
    protected $serviceName = 'SatuSehat';
    protected $baseUrl;
    protected $authUrl;
    protected $config;

    public function __construct()
    {
        parent::__construct();
        
        // Fetch active config from DB
        $this->config = $this->db->table('m_satusehat_config')->where('is_active', true)->get()->getRowArray();
        
        if (!$this->config) {
            // Fallback for safety if no active config (STG)
            $this->baseUrl = 'https://api-satusehat-stg.kemkes.go.id/fhir-r4/v1';
            $this->authUrl = 'https://api-satusehat-stg.kemkes.go.id/oauth2/v1';
            return;
        }

        $this->baseUrl = $this->config['base_url'] ?? 'https://api-satusehat-stg.kemkes.go.id/fhir-r4/v1';
        $this->authUrl = $this->config['auth_url'] ?? 'https://api-satusehat-stg.kemkes.go.id/oauth2/v1';
    }

    /**
     * Get or Refresh Access Token
     */
    public function getAccessToken()
    {
        // Check if current token in DB is still valid (buffer 5 mins)
        if (!empty($this->config['auth_token']) && strtotime($this->config['token_expires']) > (time() + 300)) {
            return $this->config['auth_token'];
        }

        // Request new token
        $response = $this->sendRequest('POST', $this->authUrl . '/accesstoken?grant_type=client_credentials', [
            'form_params' => [
                'client_id'     => $this->config['client_id'],
                'client_secret' => $this->config['client_secret'],
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]
        ]);

        if ($response['status_code'] === 200 && isset($response['json']['access_token'])) {
            $token = $response['json']['access_token'];
            $expires = date('Y-m-d H:i:s', time() + $response['json']['expires_in']);

            // Update DB
            $this->db->table('m_satusehat_config')->where('id', $this->config['id'])->update([
                'auth_token'    => $token,
                'token_expires' => $expires
            ]);

            // Update local config object
            $this->config['auth_token'] = $token;
            $this->config['token_expires'] = $expires;

            return $token;
        }

        log_message('error', '[SatuSehat] Auth Failed: ' . json_encode($response['json']));
        return null;
    }

    /**
     * Get Patient IHS Number by NIK
     */
    public function getPatientByNIK(string $nik)
    {
        $resource = 'Patient?identifier=https://fhir.kemkes.go.id/id/nik|' . $nik;
        $response = $this->fhirRequest('GET', $resource);

        if ($response['status'] === 'success' && !empty($response['data']['entry'])) {
            // Usually only one match for NIK
            $patient = $response['data']['entry'][0]['resource'];
            return [
                'status'     => 'success',
                'ihs_number' => $patient['id'],
                'data'       => $patient
            ];
        }

        return [
            'status'  => 'error',
            'message' => $response['message'] ?? 'Pasien tidak ditemukan di SatuSehat'
        ];
    }

    /**
     * Create Encounter (Visit)
     * @param array $data Expected keys: patient_ihs, patient_name, practitioner_ihs, practitioner_name, location_ihs, location_name, start_time
     */
    public function createEncounter(array $data)
    {
        $organizationId = $this->config['organization_id'] ?? '';
        
        $payload = [
            'resourceType' => 'Encounter',
            'status'       => 'arrived',
            'class'        => [
                'system'  => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                'code'    => 'AMB',
                'display' => 'ambulatory'
            ],
            'subject' => [
                'reference' => 'Patient/' . $data['patient_ihs'],
                'display'   => $data['patient_name']
            ],
            'participant' => [[
                'type' => [[
                    'coding' => [[
                        'system'  => 'http://terminology.hl7.org/CodeSystem/v3-ParticipationType',
                        'code'    => 'ATND',
                        'display' => 'attender'
                    ]]
                ]],
                'individual' => [
                    'reference' => 'Practitioner/' . $data['practitioner_ihs'],
                    'display'   => $data['practitioner_name']
                ]
            ]],
            'period' => [
                'start' => date('c', strtotime($data['start_time']))
            ],
            'location' => [[
                'location' => [
                    'reference' => 'Location/' . $data['location_ihs'],
                    'display'   => $data['location_name']
                ]
            ]],
            'statusHistory' => [[
                'status' => 'arrived',
                'period' => [
                    'start' => date('c', strtotime($data['start_time']))
                ]
            ]],
            'serviceProvider' => [
                'reference' => 'Organization/' . $organizationId
            ],
            'identifier' => [[
                'system' => 'http://sys-ids.kemkes.go.id/encounter/' . $organizationId,
                'value'  => $data['local_reg_id'] ?? uniqid()
            ]]
        ];

        return $this->fhirRequest('POST', 'Encounter', $payload);
    }

    /**
     * Specialized FHIR Request
     */
    public function fhirRequest(string $method, string $resource, array $payload = [])
    {
        $token = $this->getAccessToken();
        if (!$token) return ['status' => 'error', 'message' => 'Authentication Failed'];

        $url = $this->baseUrl . '/' . $resource;
        
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ]
        ];

        if (!empty($payload)) {
            $options['json'] = $payload;
        }

        $response = $this->sendRequest($method, $url, $options);

        // Basic success check
        if ($response['status_code'] >= 200 && $response['status_code'] < 300) {
            return [
                'status' => 'success',
                'data'   => $response['json']
            ];
        }

        return [
            'status'  => 'error',
            'code'    => $response['status_code'],
            'message' => $response['json']['issue'][0]['details']['text'] ?? 
                        ($response['json']['issue'][0]['diagnostics'] ?? 'Unknown API Error')
        ];
    }
}
