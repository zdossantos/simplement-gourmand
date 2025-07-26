<?php

namespace App\Http\Requests\Food;

use Illuminate\Foundation\Http\FormRequest;

class FoodListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['required', 'string', 'min:1'],
            'page' => ['integer', 'min:1'],
            'page_size' => ['integer', 'min:1', 'max:100']
        ];
    }
}
