<?php

namespace Database\Factories;

use App\Models\FridgeFood;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FridgeFoodFactory extends Factory
{
    protected $model = FridgeFood::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'openfoodfacts_id' => $this->faker->numerify('##########'),
            'name' => $this->faker->randomElement([
                'Pizza Margherita',
                'Coca-Cola',
                'Pain de mie',
                'Yaourt nature',
                'Pommes Golden',
                'Lait demi-écrémé',
                'Fromage râpé',
                'Pâtes',
                'Huile d\'olive',
                'Tomates cerises'
            ]),
            'image' => $this->faker->optional(0.7)->imageUrl(400, 400, 'food'),
            'quantity' => $this->faker->randomFloat(2, 0.1, 10),
            'unit' => $this->faker->randomElement(['pièce', 'g', 'kg', 'ml', 'l', 'cl']),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function withOpenFoodFactsId(string $id): static
    {
        return $this->state(fn (array $attributes) => [
            'openfoodfacts_id' => $id,
        ]);
    }

    public function withQuantity(float $quantity, string $unit = 'pièce'): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => $quantity,
            'unit' => $unit,
        ]);
    }

    public function withoutImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'image' => null,
        ]);
    }

    public function withName(string $name): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name,
        ]);
    }

    public function groceryItem(): static
    {
        $items = [
            ['name' => 'Pain de mie', 'unit' => 'pièce', 'quantity' => 1],
            ['name' => 'Lait', 'unit' => 'l', 'quantity' => 1],
            ['name' => 'Yaourt', 'unit' => 'pièce', 'quantity' => 4],
            ['name' => 'Fromage', 'unit' => 'g', 'quantity' => 200],
            ['name' => 'Pommes', 'unit' => 'kg', 'quantity' => 1],
        ];

        $item = $this->faker->randomElement($items);

        return $this->state(fn (array $attributes) => [
            'name' => $item['name'],
            'unit' => $item['unit'],
            'quantity' => $item['quantity'],
        ]);
    }
} 