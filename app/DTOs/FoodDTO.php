<?php

namespace App\DTOs;

use Spatie\LaravelData\Data;

class FoodDTO extends Data
{
    public function __construct(
        public ?string $id,
        public string $name,
        public ?string $code,
        public ?string $brands,
        public ?string $nutriscore_grade,
        public array $images,
        public bool $hasImage,
    ) {}

    public static function fromArray(array $product): ?self
    {
        $name = trim($product['product_name'] ?? '');
        if (empty($name)) {
            return null;
        }

        $images = [
            'url' => !empty($product['image_url']) ? $product['image_url'] : null,
            'front_url' => !empty($product['image_front_url']) ? $product['image_front_url'] : null,
            'front_small_url' => !empty($product['image_front_small_url']) ? $product['image_front_small_url'] : null,
        ];

        $hasImage = !empty($images['url']) || !empty($images['front_url']) || !empty($images['front_small_url']);

        return new self(
            id: !empty($product['id']) ? (string)$product['id'] : null,
            name: $name,
            code: !empty($product['code']) ? (string)$product['code'] : null,
            brands: !empty($product['brands']) ? trim($product['brands']) : null,
            nutriscore_grade: !empty($product['nutriscore_grade']) ? strtoupper($product['nutriscore_grade']) : null,
            images: $images,
            hasImage: $hasImage,
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

    public function getBestImage(): ?string
    {
        return $this->images['front_url'] ?? $this->images['front_small_url'] ?? $this->images['url'];
    }

    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'hasImage' => $this->hasImage,
        ];

        if ($this->hasImage) {
            $data['image'] = $this->getBestImage();
        }

        return $data;
    }

    public function toArrayWithDetails(): array
    {
        $data = $this->toArray();
        
        $data['code'] = $this->code;
        $data['brands'] = $this->brands;
        $data['nutriscore_grade'] = $this->nutriscore_grade;
        $data['images'] = $this->images;

        return $data;
    }
} 