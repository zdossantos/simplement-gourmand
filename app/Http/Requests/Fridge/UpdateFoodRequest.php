<?php

namespace App\Http\Requests\Fridge;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFoodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantity' => 'required|numeric|min:0|max:9999',
            'unit' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'La quantité est obligatoire.',
            'quantity.numeric' => 'La quantité doit être un nombre.',
            'quantity.min' => 'La quantité ne peut pas être négative.',
            'quantity.max' => 'La quantité ne peut pas dépasser 9999.',
            'unit.max' => 'L\'unité ne peut pas dépasser 50 caractères.',
            'unit.in' => 'L\'unité doit être une valeur valide.',
        ];
    }
} 