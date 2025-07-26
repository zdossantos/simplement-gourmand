<?php

namespace App\Http\Requests\Food;

use Illuminate\Foundation\Http\FormRequest;

class FoodListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string'],
            'page' => ['integer', 'min:1'],
            'page_size' => ['integer', 'min:1', 'max:100']
        ];
    }
}
