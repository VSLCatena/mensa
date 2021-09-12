<?php
namespace App\Http\Controllers\Api\v1\User\Controllers;

use App\Http\Controllers\Api\v1\User\Mappers\UserMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class SelfController extends Controller
{
    use UserMapper;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get the user object currently logged in
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse {
        $user = Auth::user();
        if ($user == null) {
            return response()->json(null);
        }

        return response()->json(self::mapUser($user));
    }
}
