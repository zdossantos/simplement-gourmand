<?php

namespace App\Http\Controllers;

use App\Http\Requests\Fridge\AddFoodRequest;
use Inertia\Inertia;
use Inertia\Response;

class FridgeController extends Controller
{
    public function index(): Response
    {
        $food = auth()->user()->food;

        return Inertia::render('fridge/Index', [
            'food' => $food
        ]);
    }

    public function addFood(AddFoodRequest $request)
    {
        $food = auth()->user()->food()->create($request->validated());

        return back();
    }
} 