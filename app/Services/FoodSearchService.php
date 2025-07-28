<?php

namespace App\Services;

use App\DTOs\FoodDTO;
use App\Facades\OpenFoodFacts;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FoodSearchService
{
    public function __construct(
        private FridgeService $fridgeService
    ) {}

    public function searchWithFridgeStatus(User $user, string $query): array
    {
        try {
            $results = OpenFoodFacts::searchByText($query);
            $openFoodFactsProducts = FoodDTO::fromOpenFoodFactsCollection($results['products'] ?? []);
            $enrichedProducts = $this->enrichWithFridgeData($user, $openFoodFactsProducts);

            return [
                'foods' => $enrichedProducts,
                'count' => $results['count'] ?? 0
            ];

        } catch (\Exception $e) {
            Log::error('Food search failed', [
                'query' => $query,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e;
        }
    }

    private function enrichWithFridgeData(User $user, array $openFoodFactsProducts): array
    {
        if (empty($openFoodFactsProducts)) {
            return [];
        }

        $openfoodfactsIds = collect($openFoodFactsProducts)->map(function (FoodDTO $product) {
            return $product->openfoodfacts_id;
        })->filter()->toArray();

        $fridgeItems = $this->fridgeService->getUserFridgeItemsByIds($user, $openfoodfactsIds);

        return FoodDTO::enrichWithFridgeData($openFoodFactsProducts, $fridgeItems);
    }
} 