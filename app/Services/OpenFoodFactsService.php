<?php

namespace App\Services;

use OpenFoodFacts\Api;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class OpenFoodFactsService
{
    protected array $apis = [];
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function api(string $database = null, string $country = null): Api
    {
        $database = $database ?? Config::get('openfoodfacts.default_database');
        $country = $country ?? Config::get('openfoodfacts.default_country');
        
        $key = "{$database}_{$country}";

        if (!isset($this->apis[$key])) {
            $this->apis[$key] = $this->createApi($database, $country);
        }

        return $this->apis[$key];
    }

    public function default(): Api
    {
        return $this->api();
    }

    public function food(string $country = null): Api
    {
        return $this->api('food', $country);
    }

    public function getProduct(string $barcode, string $database = null, string $country = null)
    {
        $cacheKey = "openfoodfacts.product.{$database}.{$country}.{$barcode}";
        $cacheTtl = Config::get('openfoodfacts.cache.ttl', 3600);

        if (Config::get('openfoodfacts.cache.enabled', true)) {
            return Cache::remember($cacheKey, $cacheTtl, function () use ($barcode, $database, $country) {
                return $this->api($database, $country)->getProduct($barcode);
            });
        }

        return $this->api($database, $country)->getProduct($barcode);
    }

    public function search(array $criteria = [], int $page = 1, int $pageSize = 20, string $sortBy = 'unique_scans')
    {
        return $this->api()->search($criteria, $page, $pageSize, $sortBy);
    }

    public function searchByText(string $searchText, int $page = 1, int $pageSize = 10)
    {
        $criteria = [
            'search_terms' => $searchText,
            'search_simple' => 1,
            'action' => 'process',
            'json' => 1,
            'fields' => 'id,code,product_name,image_url,image_front_url,image_front_small_url,brands,nutriscore_grade,product_quantity,product_quantity_unit'
        ];

        return $this->searchWithUrl($criteria, $page, $pageSize);
    }

    protected function searchWithUrl(array $params, int $page = 1, int $pageSize = 20)
    {
        $client = new Client();
        $url = 'https://world.openfoodfacts.org/cgi/search.pl';
        
        $params = array_merge($params, [
            'page' => $page,
            'page_size' => $pageSize
        ]);

        $response = $client->get($url, [
            'query' => $params,
            'headers' => [
                'User-Agent' => Config::get('openfoodfacts.http.user_agent', 'Laravel-OpenFoodFacts/1.0')
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    protected function createApi(string $database, string $country): Api
    {
        $httpConfig = Config::get('openfoodfacts.http', []);
        
        $client = new Client([
            'timeout' => $httpConfig['timeout'] ?? 30,
            'connect_timeout' => $httpConfig['connect_timeout'] ?? 10,
            'headers' => [
                'User-Agent' => $httpConfig['user_agent'] ?? 'Laravel-OpenFoodFacts/1.0',
            ],
        ]);

        return new Api(
            $database,
            $country,
            $this->logger,
            $client
        );
    }

    public function clearCache(): void
    {
        Cache::flush();
    }

    public function getStats(): array
    {
        return [
            'active_apis' => count($this->apis),
            'databases' => array_keys(Config::get('openfoodfacts.databases', [])),
            'countries' => array_keys(Config::get('openfoodfacts.countries', [])),
        ];
    }
} 