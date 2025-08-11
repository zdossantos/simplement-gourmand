<?php

namespace Tests\Feature;

use App\Models\FridgeFood;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FridgeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_add_food_to_fridge()
    {
        $data = [
            'openfoodfacts_id' => '123',
            'name' => 'Dark Chocolate',
            'image' => 'http://example.com/chocolate.jpg',
            'unit' => 'g',
            'quantity' => 100,
        ];

        $response = $this->post(route('fridge.addFood'), $data);

        $response->assertStatus(302);

        $this->assertDatabaseHas('fridge_food', [
            'user_id' => $this->user->id,
            'openfoodfacts_id' => 123,
            'name' => 'Dark Chocolate',
            'image' => 'http://example.com/chocolate.jpg',
            'unit' => 'g',
            'quantity' => 100,
        ]);
    }

    public function test_add_food_to_fridge_with_invalid_data()
    {
        $data = [
            'openfoodfacts_id' => 123,
            'name' => 12,
            'image' => 'http://example.com/chocolate.jpg',
            'unit' => 'g',
            'quantity' => 100,
        ];

        $response = $this->post(route('fridge.addFood'), $data);

        $response->assertStatus(302);

        $this->assertDatabaseMissing('fridge_food', [
            'user_id' => $this->user->id,
            'openfoodfacts_id' => '124',
        ]);
    }

    public function test_update_food_in_fridge()
    {
        $food = FridgeFood::create([
            'user_id' => $this->user->id,
            'openfoodfacts_id' => 123,
            'name' => 'Dark Chocolate',
            'image' => 'http://example.com/chocolate.jpg',
            'unit' => 'g',
            'quantity' => 100,
        ]);

        $data = [
            'quantity' => 100,
        ];

        $response = $this->put(route('fridge.updateFood', $food->id), $data);

        $response->assertStatus(302);

        $this->assertDatabaseHas('fridge_food', [
            'user_id' => $this->user->id,
            'openfoodfacts_id' => '123',
            'name' => 'Dark Chocolate',
            'image' => 'http://example.com/chocolate.jpg',
            'unit' => 'g',
            'quantity' => 100,
        ]);
    }

    public function test_delete_food_from_fridge()
    {
        $food = FridgeFood::create([
            'user_id' => $this->user->id,
            'openfoodfacts_id' => 123,
            'name' => 'Dark Chocolate',
            'image' => 'http://example.com/chocolate.jpg',
            'unit' => 'g',
            'quantity' => 100,
        ]);

        $response = $this->delete(route('fridge.deleteFood', $food->id));

        $response->assertStatus(302);

        $this->assertDatabaseMissing('fridge_food', [
            'id' => $food->id,
        ]);
    }
} 