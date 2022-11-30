<?php

namespace App\Http\Controllers\Api\v1\User\Controllers;

use App\Http\Controllers\Api\v1\Common\Models\FoodOption;
use App\Http\Controllers\Api\v1\User\Mappers\UserMapper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use App\Models\User;

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
        $this->systemUser = User::where('name', 'SYSTEM')->first();             
    }

    /**
     * Get the user object currently logged in
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getSelf(Request $request): JsonResponse
    {
        $user = Auth::user();
        if ($user == null) {
            Log::error([
                "category" => "user",
                "text" => "getSelf / HTTP_UNAUTHORIZED",
                "user_id" =>$this->systemUser->id,
                "object_id" =>$this->systemUser->id
                
            ]);               
            abort(Response::HTTP_UNAUTHORIZED);
        }

        return response()->json(self::mapUser($user));
    }

    /**
     * Update the user currently logged in
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateSelf(Request $request)
    {
        $user = Auth::user();
        if ($user == null) {
            Log::error([
                "category" => "user",
                "text" => "updateSelf / HTTP_UNAUTHORIZED",
                "user_id" =>$this->systemUser->id,
                "object_id" =>$this->systemUser->id
                
            ]);             
            abort(Response::HTTP_UNAUTHORIZED);
        }

        $validator = Validator::make($request->all(), [
            'allergies' => ['string'],
            'extraInfo' => ['string'],
            'foodPreference' => ['string', 'nullable', Rule::in(FoodOption::allNames())],
        ]);

        if ($validator->fails()) {
            Log::error([
                "category" => "user",
                "text" => "updateSelf / Validator / HTTP_BAD_REQUEST",
                "user_id" =>$user->id,
                "object_id" =>$user->id
                
            ]);             
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        if ($request->has('allergies')) $user->allergies = $request->get('allergies');
        if ($request->has('extraInfo')) $user->extra_info = $request->get('extraInfo');
        if ($request->has('foodPreference'))
            $user->food_preference = $this->mapFoodOptionFromNameToInt($request->get('foodPreference'));

        $user->save();
        Log::info([
            "category" => "user",
            "text" => "updateSelf / succes",
            "user_id" =>$user->id,
            "object_id" =>$user->id
        ]);   
        return null;
    }
}
