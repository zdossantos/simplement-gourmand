<?php

namespace App\Http\Controllers;

use App\Facades\OpenFoodFacts;
use App\Http\Requests\Food\FoodListRequest;
use App\Http\Resources\FoodResource;
use Illuminate\Http\JsonResponse;

class FoodController extends Controller
{
    public function list(FoodListRequest $request): JsonResponse
    {
        $results = OpenFoodFacts::searchByText($request->search);
        $products = FoodResource::collection($results['products'] ?? []);

        return response()->json([
            'products' => $products,
            'search' => $request->search,
            'count' => $results['count'] ?? 0,
            'page' => $results['page'] ?? 1
        ]);
    }
} 