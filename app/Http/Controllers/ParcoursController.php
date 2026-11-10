<?php

namespace App\Http\Controllers;

use App\Services\ParcoursGenerator;
use Illuminate\Http\JsonResponse;

class ParcoursController extends Controller
{
    /**
     * Génère et renvoie le parcours d'une nouvelle partie.
     */
    public function __invoke(ParcoursGenerator $generator): JsonResponse
    {
        return response()->json($generator->generer());
    }
}
