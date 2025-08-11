<?php

namespace App\Services;

use App\DTOs\FoodDTO;
use App\Models\FridgeFood;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class FridgeService
{
    public function updateUserFood(User $user, int $foodId, $quantity): FridgeFood
    {
        $food = $user->fridgeFood()->findOrFail($foodId);
        $food->update([
            'quantity' => $quantity
        ]);
        return $food->fresh();
    }

    public function deleteUserFood(User $user, int $foodId): bool
    {
        $food = $user->fridgeFood()->findOrFail($foodId);
        return $food->delete();
    }

    public function addUserFood(User $user, FoodDTO $foodDTO): FridgeFood
    {
        return $user->fridgeFood()->create($foodDTO->toFridgeArray());
    }

    public function getUserFridgeItems(User $user): array
    {
        $fridgeFood = $user->fridgeFood;
        return FoodDTO::fromFridgeFoodCollection($fridgeFood);
    }

    public function getUserFridgeItemsByIds(User $user, array $openfoodfactsIds): Collection
    {
        return $user->fridgeFood()
            ->whereIn('openfoodfacts_id', $openfoodfactsIds)
            ->get();
    }

    public function getFridgeItemsAsDTO(User $user, array $openfoodfactsIds): array
    {
        $fridgeFood = $this->getUserFridgeItemsByIds($user, $openfoodfactsIds);
        return FoodDTO::fromFridgeFoodCollection($fridgeFood);
    }
}