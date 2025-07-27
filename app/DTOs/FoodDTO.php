<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\MapName;

class FoodDTO extends Data
{
    public function __construct(
        public ?string $id,
        public string $name,
        public ?string $code,
        public ?string $brands,
        public ?string $nutriscore_grade,
        public ?string $image,
        public bool $hasImage,
        public ?float $quantity = null,
        public ?string $unit = null,
    ) {}

    public static function fromArray(array $product): ?self
    {
        $name = trim($product['product_name'] ?? '');
        if (empty($name)) {
            return null;
        }

        $image = $product['image_front_url'] ?? $product['image_front_small_url'] ?? $product['image_url'] ?? null;
        $hasImage = !empty($image);

        return new self(
            id: !empty($product['id']) ? (string)$product['id'] : null,
            name: $name,
            code: !empty($product['code']) ? (string)$product['code'] : null,
            brands: !empty($product['brands']) ? trim($product['brands']) : null,
            nutriscore_grade: !empty($product['nutriscore_grade']) ? strtoupper($product['nutriscore_grade']) : null,
            image: $image,
            hasImage: $hasImage,
            quantity: isset($product['product_quantity']) ? (float)$product['product_quantity'] : null,
            unit: !empty($product['product_quantity_unit']) ? trim($product['product_quantity_unit']) : null,
        );
    }

    public static function fromOpenFoodFactsCollection(array $products): array
    {
        $validProducts = [];
        
        foreach ($products as $product) {
            $dto = self::fromArray($product);
            if ($dto !== null) {
                $validProducts[] = $dto;
            }
        }

        return $validProducts;
    }

    /**
     * Prépare les données pour l'ajout au frigo
     */
    public function toFridgeArray(): array
    {
        return [
            'openfoodfacts_id' => $this->code ?? $this->id,
            'name' => $this->name,
            'image_url' => $this->image,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
        ];
    }
} 