<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    private $apiKey;
    private $url;

    public function __construct($apiKey, $statusApiKey = 'Starter')
    {
        $this->apiKey = $apiKey;
        $this->url = $statusApiKey === 'Pro' ? 'https://pro.rajaongkir.com/api' : 'https://api.rajaongkir.com/starter';
    }

    /**
     * General method to make API requests
     *
     * @param string $method The HTTP method (GET or POST)
     * @param string $endpoint The API endpoint
     * @param array $params Additional parameters for POST requests
     * @return array The decoded API response
     */
    private function makeRequest(string $method, string $endpoint, array $params = [])
    {
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey,
            ])->$method($this->url . $endpoint, $params);
            app('debugbar')->info($endpoint);
            app('debugbar')->info($response['rajaongkir']);
            return $response->successful() ? $response->json() : [
                'status' => 'error',
                'message' => $response->json()['message'] ?? 'An error occurred',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get the list of provinces
     *
     * @param int|null $id The province ID, or null for all
     * @return array
     */
    public function getProvinces($id = null)
    {
        $endpoint = $id ? "/province?id={$id}" : '/province';
        return $this->makeRequest('get', $endpoint);
        
    }

    /**
     * Get the list of cities
     *
     * @param int $cityId
     * @param int $provinceId
     * @return array
     */
    public function getCities($provinceId)
    {
        $endpoint = "/city";
        return $this->makeRequest('get', $endpoint,[
            'province' => $provinceId
        ]);

        
    }

    /**
     * Get the list of subdistricts
     *
     * @param int $cityId
     * @return array
     */
    public function getSubdistricts($cityId)
    {
        $endpoint = "/subdistrict";
        return $this->makeRequest('get', $endpoint, [
            'city' => $cityId
        ]);
    }

    /**
     * Get shipping cost details
     *
     * @param int $origin
     * @param int $destination
     * @param int $weight
     * @param string $courier
     * @return array
     */
    public function getCost($origin, $destination, $weight, $courier)
    {
        $params = [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
        ];
        
        $endpoint = '/cost';
        return $this->makeRequest('post', $endpoint, $params);
    }

    /**
     * Get international shipping origin
     *
     * @param int $id
     * @param int $province
     * @return array
     */
    public function getInternationalOrigin($id, $province)
    {
        $endpoint = "/v2/internationalOrigin?id={$id}&province={$province}";
        return $this->makeRequest('get', $endpoint);
    }

    /**
     * Get international shipping destination
     *
     * @param int $id
     * @return array
     */
    public function getInternationalDestination($id)
    {
        $endpoint = "/v2/internationalDestination?id={$id}";
        return $this->makeRequest('get', $endpoint);
    }

    /**
     * Get international shipping cost
     *
     * @param int $origin
     * @param int $destination
     * @param int $weight
     * @param string $courier
     * @return array
     */
    public function getCostInternational($origin, $destination, $weight, $courier)
    {
        $params = [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => $courier,
        ];
        $endpoint = '/v2/internationalCost';
        return $this->makeRequest('post', $endpoint, $params);
    }

    /**
     * Get the currency exchange rate
     *
     * @return array
     */
    public function getDollarCurrency()
    {
        $endpoint = '/currency';
        return $this->makeRequest('get', $endpoint);
    }

    /**
     * Track a shipment by waybill
     *
     * @param string $waybill
     * @param string $courier
     * @return array
     */
    public function trackWaybill($waybill, $courier)
    {
        $params = [
            'waybill' => $waybill,
            'courier' => $courier,
        ];
        $endpoint = '/waybill';
        return $this->makeRequest('post', $endpoint, $params);
    }
}
