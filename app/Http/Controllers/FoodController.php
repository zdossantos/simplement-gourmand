<?php

namespace App\Http\Controllers;

use App\Facades\OpenFoodFacts;
use App\Http\Requests\Food\FoodListRequest;
use App\Http\Resources\FoodResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FoodController extends Controller
{
    public function index(FoodListRequest $request): Response
    {
        $search = $request->get('search', '');
        $products = [];
        $count = 0;

        if (strlen($search) >= 2) {
            $results = OpenFoodFacts::searchByText($search);
            $products = FoodResource::collection($results['products'] ?? [])->resolve();
            $products = array_filter($products, function($product) {
                return $product;
            });
            $count = $results['count'] ?? 0;
        }

        return Inertia::render('food/Index', [
            'products' => $products,
            'search' => $search,
            'count' => $count,
        ]);
    }
} 