<?php

namespace App\DTOs;

use App\Models\FridgeFood;
use Spatie\LaravelData\Data;
use Illuminate\Database\Eloquent\Collection;

class FoodDTO extends Data
{
    public function __construct(
        public string $openfoodfacts_id,
        public string $name,
        public ?string $code = null,
        public ?string $brands = null,
        public ?string $nutriscore_grade = null,
        public ?string $image = null,
        public bool $hasImage = false,
        public ?float $quantity = null,
        public ?string $unit = null,
        public ?int $fridge_id = null,
        public ?int $user_id = null,
    ) {
        $this->hasImage = !empty($this->image);
        $this->unit = $this->unit ?? 'pièce';
    }

    public static function fromFridgeFood(FridgeFood $fridgeFood): self
    {
        return new self(
            openfoodfacts_id: $fridgeFood->openfoodfacts_id,
            name: $fridgeFood->name,
            image: $fridgeFood->image,
            quantity: (float) $fridgeFood->quantity,
            unit: $fridgeFood->unit,
            fridge_id: $fridgeFood->id,
            user_id: $fridgeFood->user_id,
        );
    }

    public static function fromOpenFoodFactsCollection(array $products): array
    {
        $validProducts = [];
        
        foreach ($products as $product) {
            $name = trim($product['product_name'] ?? '');
            if (empty($name)) {
                continue;
            }

            $image = $product['image_front_url'] 
                ?? $product['image_front_small_url'] 
                ?? $product['image_url'] 
                ?? null;

            $openfoodfacts_id = (string)($product['code'] ?? $product['id'] ?? '');
            if (empty($openfoodfacts_id)) {
                continue;
            }

            $validProducts[] = new self(
                openfoodfacts_id: $openfoodfacts_id,
                name: $name,
                code: !empty($product['code']) ? (string)$product['code'] : null,
                brands: !empty($product['brands']) ? trim($product['brands']) : null,
                nutriscore_grade: !empty($product['nutriscore_grade']) 
                    ? strtoupper($product['nutriscore_grade']) 
                    : null,
                image: $image,
                quantity: isset($product['product_quantity']) 
                    ? (float)$product['product_quantity'] 
                    : null,
                unit: !empty($product['product_quantity_unit']) 
                    ? trim($product['product_quantity_unit']) 
                    : null,
            );
        }

        return $validProducts;
    }

    public static function fromFridgeFoodCollection(Collection $fridgeFood): array
    {
        return $fridgeFood->map(function (FridgeFood $food) {
            return self::fromFridgeFood($food);
        })->values()->all();
    }

    public static function enrichWithFridgeData(array $openFoodFactsProducts, Collection $fridgeItems): array
    {
        $fridgeItemsKeyed = $fridgeItems->keyBy('openfoodfacts_id');

        return array_map(function (self $product) use ($fridgeItemsKeyed) {
            $fridgeItem = $fridgeItemsKeyed->get($product->openfoodfacts_id);
            
            if ($fridgeItem) {
                return $product->enrichWithFridgeItem($fridgeItem);
            }
            
            return $product;
        }, $openFoodFactsProducts);
    }

    public function enrichWithFridgeItem(FridgeFood $fridgeItem): self
    {
        return new self(
            openfoodfacts_id: $this->openfoodfacts_id,
            name: $this->name,
            code: $this->code,
            brands: $this->brands,
            nutriscore_grade: $this->nutriscore_grade,
            image: $this->image,
            quantity: (float) $fridgeItem->quantity,
            unit: $fridgeItem->unit,
            fridge_id: $fridgeItem->id,
            user_id: $fridgeItem->user_id,
        );
    }

    public function toFridgeArray(): array
    {
        return [
            'openfoodfacts_id' => $this->openfoodfacts_id,
            'name' => $this->name,
            'image' => $this->image,
            'quantity' => $this->quantity ?? 1,
            'unit' => $this->unit,
        ];
    }
} 