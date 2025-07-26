<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): ?array
    {
        $required = [
            'id',
            'product_name',
        ];

        foreach ($required as $key) {
            if (empty($this->resource[$key])) {
                return null;
            }
        }

        return [
            'id' => $this->resource['id'],
            'name' => $this->resource['product_name'],
            'image_url' => $this->resource['image_url'] ?? null,
            'image_front_url' => $this->resource['image_front_url'] ?? null,
            'image_front_small_url' => $this->resource['image_front_small_url'] ?? null,
        ];
    }
}
