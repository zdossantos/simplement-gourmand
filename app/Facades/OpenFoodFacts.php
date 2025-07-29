<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \OpenFoodFacts\Api api(string $database = null, string $country = null)
 * @method static \OpenFoodFacts\Api default()
 * @method static \OpenFoodFacts\Api food(string $country = null)
 * @method static mixed getProduct(string $barcode, string $database = null, string $country = null)
 * @method static mixed search(array $criteria = [], int $page = 1, int $pageSize = 20, string $sortBy = 'unique_scans')
 * @method static mixed searchByText(string $searchText, int $page = 1, int $pageSize = 20)
 * @method static void clearCache()
 * @method static array getStats()
 * 
 * @see \App\Services\OpenFoodFactsService
 */
class OpenFoodFacts extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'openfoodfacts';
    }
} 