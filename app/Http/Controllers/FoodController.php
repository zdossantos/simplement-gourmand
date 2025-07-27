<?php

namespace App\Http\Controllers;

use App\DTOs\FoodDTO;
use App\Facades\OpenFoodFacts;
use App\Http\Requests\Food\FoodListRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FoodController extends Controller
{
    public function index(FoodListRequest $request): Response
    {
        $search = trim($request->get('search', ''));
        $products = [];
        $count = 0;

        if (strlen($search) >= 2) {
            $results = OpenFoodFacts::searchByText($search);
            $products = FoodDTO::fromOpenFoodFactsCollection($results['products'] ?? []);
            
            $userFood = auth()->user()->food;
            
            $count = $results['count'] ?? 0;
        }

        return Inertia::render('food/Index', [
            'products' => $products,
            'search' => $search,
            'count' => $count,
        ]);
    }
} 