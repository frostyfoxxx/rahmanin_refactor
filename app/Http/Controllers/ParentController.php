<?php

namespace App\Http\Controllers;

use App\Http\Resources\ParentsResource;
use App\Models\Parents;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class ParentController extends Controller
{
    /**
     * Получение данных о родителях
     * @return JsonResponse
     */
    public function getParentInformation(): JsonResponse
    {
        $parents = Auth::user()->parents ? Auth::user()->parents : [];
        return response()->json([
            'code' => 200,
            'message' => 'Parents found',
            'data' => ParentsResource::collection($parents)
        ])->setStatusCode(200);
    }

    /**
     * @return void
     */
    public function postParents()
    {

    }
}
