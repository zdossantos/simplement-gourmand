<?php

namespace App\Http\Requests\Fridge;

use Illuminate\Foundation\Http\FormRequest;

class AddFoodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'openfoodfacts_id' => 'nullable|string',
            'name' => 'required|string|max:255',
            'image_url' => 'nullable|url|max:500',
            'quantity' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du produit est obligatoire.',
            'name.max' => 'Le nom du produit ne peut pas dépasser 255 caractères.',
            'image_url.url' => 'L\'URL de l\'image doit être valide.',
            'quantity.numeric' => 'La quantité doit être un nombre.',
            'quantity.min' => 'La quantité ne peut pas être négative.',
            'unit.max' => 'L\'unité ne peut pas dépasser 50 caractères.',
        ];
    }
} 