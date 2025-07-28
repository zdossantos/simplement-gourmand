<?php

namespace App\Http\Controllers;

use App\Http\Requests\Food\FoodListRequest;
use App\Services\FoodSearchService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class FoodController extends Controller
{
    public function __construct(
        private FoodSearchService $foodSearchService
    ) {}

    public function index(FoodListRequest $request): Response
    {
        $search = trim($request->get('search', ''));
        $foods = [];
        $count = 0;
        $error = null;

        if (strlen($search) >= 2) {
            try {
                $searchResult = $this->foodSearchService->searchWithFridgeStatus(
                    auth()->user(), 
                    $search
                );
                
                $foods = $searchResult['foods'];
                $count = $searchResult['count'];
                
            } catch (\Exception $e) {
                Log::error('Food search failed in controller', [
                    'query' => $search,
                    'user_id' => auth()->id(),
                    'error' => $e->getMessage()
                ]);
                
                $error = 'Une erreur est survenue lors de la recherche. Veuillez réessayer.';
            }
        }

        return Inertia::render('food/Index', [
            'foods' => $foods,
            'search' => $search,
            'count' => $count,
            'error' => $error,
        ]);
    }
} 