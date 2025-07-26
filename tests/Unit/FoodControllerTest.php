<?php

namespace Tests\Unit;

use App\Facades\OpenFoodFacts;
use App\Http\Controllers\FoodController;
use App\Http\Requests\Food\FoodListRequest;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class FoodControllerTest extends TestCase
{
    protected FoodController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new FoodController();
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

        OpenFoodFacts::shouldReceive('searchByText')
            ->with('pizza')
            ->andReturn([
                'products' => [
                    ['id' => '123', 'product_name' => 'Pizza']
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

        OpenFoodFacts::shouldReceive('searchByText')
            ->with('pizza')
            ->andThrow(new \Exception('API Error'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('API Error');

        $this->controller->index($request);
    }

    public function test_index_returns_products_in_correct_format()
    {
        $request = new FoodListRequest();
        $request->merge(['search' => 'chocolate']);

        OpenFoodFacts::shouldReceive('searchByText')
            ->with('chocolate')
            ->andReturn([
                'products' => [
                    [
                        'id' => '123',
                        'product_name' => 'Dark Chocolate',
                        'image_url' => 'http://example.com/chocolate.jpg',
                        'image_front_url' => 'http://example.com/chocolate-front.jpg',
                        'image_front_small_url' => 'http://example.com/chocolate-small.jpg',
                    ]
                ],
                'count' => 1
            ]);

        $response = $this->controller->index($request);

        $this->assertNotNull($response);
        
        $reflection = new \ReflectionClass($response);
        $propsProperty = $reflection->getProperty('props');
        $propsProperty->setAccessible(true);
        $data = $propsProperty->getValue($response);
        
        $this->assertArrayHasKey('products', $data);
        $this->assertArrayHasKey('search', $data);
        $this->assertArrayHasKey('count', $data);
        $this->assertEquals('chocolate', $data['search']);
        $this->assertEquals(1, $data['count']);
        $this->assertCount(1, $data['products']);
        $this->assertEquals('123', $data['products'][0]['id']);
        $this->assertEquals('Dark Chocolate', $data['products'][0]['name']);
    }
} 