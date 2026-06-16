<?php

namespace App\Services;

class PaystackService
{
    /**
     * Verify a bank account.
     *
     * @param string $accountNumber
     * @param string $bankCode
     * @return array
     */
    public function verifyBankAccount(string $accountNumber, string $bankCode): array
    {
        //$url = 'https://api.paystack.co/bank/resolve';

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.paystack.co/bank/resolve?account_number=".$accountNumber."&bank_code=".$bankCode,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ".env('PAYSTACK_SECRET_KEY'),
            "Cache-Control: no-cache",
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return [
                'status' => false,
                'message' => "cURL Error: " . $err,
                'data' => [],
            ];
        } else {
            $responseData = json_decode($response, true); // Convert to associative array

            if (isset($responseData['status']) && $responseData['status'] === true) {
                return [
                    'status' => true,
                    'message' => $responseData['message'],
                    'data' => $responseData['data'],
                ];
            } else {
                return [
                    'status' => false,
                    'message' => $responseData['message'] ?? 'Failed to verify bank account',
                    'data' => [],
                ];
            }
        }
    }

    /**
     * Get a list of Nigerian banks.
     *
     * @return array
     */
    public function getNigerianBanks(): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/bank",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . env('PAYSTACK_SECRET_KEY'),
                "Cache-Control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return [
                'status' => false,
                'message' => "cURL Error: " . $err,
                'data' => [],
            ];
        } else {
            $responseData = json_decode($response, true); // Convert to associative array

            if (isset($responseData['status']) && $responseData['status'] === true) {
                return [
                    'status' => true,
                    'message' => $responseData['message'],
                    'data' => $responseData['data'],
                ];
            } else {
                return [
                    'status' => false,
                    'message' => $responseData['message'] ?? 'Failed to retrieve banks',
                    'data' => [],
                ];
            }
        }
    }
}
