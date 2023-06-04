<?php

namespace App\Http\Controllers\Api\v1\User\Controllers;

use App\Http\Controllers\Api\v1\Common\Mappers\FoodOptionsMapper;
use App\Http\Controllers\Api\v1\Common\Models\FoodOption;
use App\Http\Controllers\Api\v1\User\Mappers\FullUserMapper;
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
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private readonly FullUserMapper    $userMapper,
        private readonly FoodOptionsMapper $foodOptionsMapper,
        private readonly ValidateOrFail    $validateOrFail
    )
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Get the user object currently logged in
     * @noinspection PhpUnused
     */
    public function getSelf(): ?JsonResponse
    {
        $user = Auth::user();
        return response()->json($this->userMapper->map($user));
    }

    /**
     * Update the user currently logged in
     *
     * @noinspection PhpUnused
     */
    public function updateSelf(Request $request): ?JsonResponse
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'allergies' => ['string'],
            'extraInfo' => ['string'],
            'foodPreference' => ['string', 'nullable', Rule::in(FoodOption::allNames())],
        ]);
        $this->validateOrFail->with($validator);

        if ($request->has('allergies')) {
            $user->allergies = $request->get('allergies');
        }
        if ($request->has('extraInfo')) {
            $user->extra_info = $request->get('extraInfo');
        }
        if ($request->has('foodPreference')) {
            $user->food_preference = $this->foodOptionsMapper->fromNameToInt($request->get('foodPreference'));
        }

        $user->save();

        return null;
    }
}
