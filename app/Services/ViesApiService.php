<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;

class ViesApiService
{
    protected string $baseUrl = 'https://ec.europa.eu/taxation_customs/vies/rest-api';

    public function checkVat(string $countryCode, string $vatNumber): array
    {
        $countryCode = strtoupper(trim($countryCode));
        $vatNumber = trim($vatNumber);

        try {
            $response = Http::timeout(10)
                ->post("{$this->baseUrl}/check-vat-number", [
                    'countryCode' => $countryCode,
                    'vatNumber' => $vatNumber,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['valid']) && $data['valid'] === true) {
                    $rawAddress = $data['address'] ?? '';
                    $parsedAddress = $this->parseAddress($rawAddress, $countryCode);

                    return [
                        'success' => true,
                        'data' => [
                            'name' => $data['name'] ?? '',
                            'address' => $parsedAddress['street'],
                            'zip_code' => $parsedAddress['zip_code'],
                            'city' => $parsedAddress['city'],
                            'vat_number' => $vatNumber,
                            'country_code' => $countryCode,
                        ],
                    ];
                }

                return [
                    'success' => false,
                    'message' => 'The provided VAT number is invalid or not registered in VIES.',
                ];
            }

            return ['success' => false, 'message' => 'Error communicating with VIES.'];

        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Connection failure.'];
        }
    }

    private function parseAddress(string $address, string $countryCode): array
    {
        $lines = explode("\n", trim($address));
        $lines = array_map('trim', array_filter($lines));

        $street = '';
        $zip = '';
        $city = '';

        if (count($lines) > 0) {
            $lastLine = end($lines);

            if (preg_match('/^(\d{2,5}(?:[-\s]\d{2,4})?)\s+(.+)$/', $lastLine, $matches)) {
                $zip = trim($matches[1]);
                $city = trim($matches[2]);

                $streetLines = array_slice($lines, 0, -1);
                $street = implode(', ', $streetLines);
            } else {
                if (count($lines) > 1) {
                    $city = $lastLine;
                    $street = implode(', ', array_slice($lines, 0, -1));
                } else {
                    $street = $lastLine;
                }
            }
        }

        if (empty($street) && !empty($city) && count($lines) === 1) {
            $street = $city;
            $city = '';
        }

        return [
            'street' => $street,
            'zip_code' => $zip,
            'city' => $city
        ];
    }
}
