<?php

namespace App\Http\Controllers;

use App\DTOs\FoodDTO;
use App\Facades\OpenFoodFacts;
use App\Http\Requests\Food\FoodListRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AuthController extends Controller
{
    public function index(): Response
    {
      return true;
    }
}
