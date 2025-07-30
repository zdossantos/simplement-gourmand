<?php

namespace App\Http\Controllers;

use App\Http\Requests\Fridge\AddFoodRequest;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Requests\Fridge\UpdateFoodRequest;
use App\Services\FridgeService;
use Illuminate\Http\RedirectResponse;
use App\DTOs\FoodDTO;

class FridgeController extends Controller
{
    public function __construct(
        private FridgeService $fridgeService
    ) {}

    public function index(): Response
    {
        $food = $this->fridgeService->getUserFridgeItems(auth()->user());

        return Inertia::render('fridge/Index', [
            'food' => $food
        ]);
    }

    public function addFood(AddFoodRequest $request): RedirectResponse
    {
        try {
            $food = new FoodDTO(
                openfoodfacts_id: $request->validated('openfoodfacts_id'),
                name: $request->validated('name'),
                image: $request->validated('image'),
                quantity: $request->validated('quantity'),
                unit: $request->validated('unit'),
            );

            $this->fridgeService->addUserFood(auth()->user(), $food);

            return back()->with('success', 'Aliment ajouté au frigo avec succès !');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de l\'ajout de l\'aliment au frigo.']);
        }
    }

    public function updateFood(UpdateFoodRequest $request, int $id): RedirectResponse
    {
        try {
            $this->fridgeService->updateUserFood(auth()->user(), $id, $request->validated('quantity'));

            return back()->with('success', 'Aliment mis à jour avec succès !');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour.']);
        }
    }

    public function deleteFood(int $id): RedirectResponse
    {
        try {
            $this->fridgeService->deleteUserFood(auth()->user(), $id);

            return back()->with('success', 'Aliment retiré du frigo avec succès !');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }
} 