<?php

namespace Tests\Feature;

use App\Http\Controllers\FoodController;
use App\Http\Requests\Food\FoodListRequest;
use App\Services\FoodSearchService;
use Mockery;
use Tests\TestCase;
use App\Models\User;

class FoodControllerTest extends TestCase
{
    protected FoodController $controller;
    protected $foodSearchService;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $this->foodSearchService = Mockery::mock(FoodSearchService::class);
        $this->controller = new FoodController($this->foodSearchService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_with_empty_search()
    {
        $request = new FoodListRequest();
        $request->merge(['search' => '']);

        $response = $this->controller->index($request);

        $this->assertNotNull($response);
    }

    public function test_index_with_valid_search()
    {
        $request = new FoodListRequest();
        $request->merge(['search' => 'pizza']);

        $this->foodSearchService->shouldReceive('searchWithFridgeStatus')
            ->with(auth()->user(), 'pizza')
            ->andReturn([
                'foods' => [
                    (object)['openfoodfacts_id' => '123', 'name' => 'Pizza']
                ],
                'count' => 1
            ]);

        $response = $this->controller->index($request);

        $this->assertNotNull($response);
    }

    public function test_index_throws_exception()
    {
        $request = new FoodListRequest();
        $request->merge(['search' => 'pizza']);

        $this->foodSearchService->shouldReceive('searchWithFridgeStatus')
            ->with(auth()->user(), 'pizza')
            ->andThrow(new \Exception('Service Error'));

        $response = $this->controller->index($request);

        // The controller catches exceptions and sets an error message
        $this->assertNotNull($response);
        
        $reflection = new \ReflectionClass($response);
        $propsProperty = $reflection->getProperty('props');
        $propsProperty->setAccessible(true);
        $data = $propsProperty->getValue($response);
        
        $this->assertArrayHasKey('error', $data);
        $this->assertNotNull($data['error']);
    }

    public function test_index_returns_products_in_correct_format()
    {
        $request = new FoodListRequest();
        $request->merge(['search' => 'chocolate']);

        $mockProduct = (object)[
            'openfoodfacts_id' => '123',
            'name' => 'Dark Chocolate',
            'image_url' => 'http://example.com/chocolate.jpg',
        ];

        $this->foodSearchService->shouldReceive('searchWithFridgeStatus')
            ->with(auth()->user(), 'chocolate')
            ->andReturn([
                'foods' => [$mockProduct],
                'count' => 1
            ]);

        $response = $this->controller->index($request);

        $this->assertNotNull($response);
        
        $reflection = new \ReflectionClass($response);
        $propsProperty = $reflection->getProperty('props');
        $propsProperty->setAccessible(true);
        $data = $propsProperty->getValue($response);
        
        $this->assertArrayHasKey('foods', $data);
        $this->assertArrayHasKey('search', $data);
        $this->assertArrayHasKey('count', $data);
        $this->assertEquals('chocolate', $data['search']);
        $this->assertEquals(1, $data['count']);
        $this->assertCount(1, $data['foods']);
        $this->assertEquals('123', $data['foods'][0]->openfoodfacts_id);
        $this->assertEquals('Dark Chocolate', $data['foods'][0]->name);
    }
} 