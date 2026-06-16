<?php

namespace App\Services;

use GuzzleHttp\Client;
use Exception;

class FlutterwaveService
{
    protected $client;
    protected $secretKey;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://api.flutterwave.com/v3/']);
        $this->secretKey = env('FLUTTERWAVE_SECRET_KEY');
    }

    /**
     * Verify BVN.
     *
     * @param string $bvn
     * @return array
     * @throws Exception
     */
    public function verifyBVN($bvn)
    {
        try {
            $response = $this->client->request('GET', "kyc/bvns/{$bvn}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->secretKey,
                    'Accept'        => 'application/json',
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (Exception $e) {
            throw new Exception("BVN verification failed: " . $e->getMessage());
        }
    }
}
