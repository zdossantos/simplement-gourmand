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
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource['id'] ?? null,
            'name' => $this->resource['product_name'],
            'image_url' => $this->resource['image_url'],
            'image_front_url' => $this->resource['image_front_url'],
            'image_front_small_url' => $this->resource['image_front_small_url']
        ];
    }
}
