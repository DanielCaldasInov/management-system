<?php

namespace App\Http\Controllers;

use App\Services\ViesApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ViesController extends Controller
{
    public function check(Request $request, ViesApiService $viesService): JsonResponse
    {
        $request->validate([
            'country_code' => 'required|string|size:2',
            'vat_number' => 'required|string',
        ]);

        $result = $viesService->checkVat(
            $request->input('country_code'),
            $request->input('vat_number')
        );

        if ($result['success']) {
            return response()->json($result);
        }

        // Retornamos 422 (Unprocessable Entity) para o Axios cair no bloco 'catch'
        return response()->json($result, 422);
    }
}
