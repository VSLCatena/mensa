<?php

namespace App\Http\Controllers\Api\v1\User\Controllers;

use App\Http\Controllers\Api\v1\Common\Models\FoodOption;
use App\Http\Controllers\Api\v1\User\Mappers\UserMapper;
use App\Http\Controllers\Api\v1\Utils\ValidateOrFail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class SelfController extends Controller
{
    use UserMapper, ValidateOrFail;

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
     */
    public function getSelf(Request $request): JsonResponse
    {
        $user = Auth::user();
        if ($user == null) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        return response()->json(self::mapUser($user));
    }

    /**
     * Update the user currently logged in
     *
     * @return JsonResponse
     */
    public function updateSelf(Request $request)
    {
        $user = Auth::user();
        if ($user == null) {
            abort(Response::HTTP_UNAUTHORIZED);
        }

        $validator = Validator::make($request->all(), [
            'allergies' => ['string'],
            'extraInfo' => ['string'],
            'foodPreference' => ['string', 'nullable', Rule::in(FoodOption::allNames())],
        ]);
        $this->validateOrFail($validator);

        if ($request->has('allergies')) {
            $user->allergies = $request->get('allergies');
        }
        if ($request->has('extraInfo')) {
            $user->extra_info = $request->get('extraInfo');
        }
        if ($request->has('foodPreference')) {
            $user->food_preference = $this->mapFoodOptionFromNameToInt($request->get('foodPreference'));
        }

        $user->save();

        return null;
    }
}
