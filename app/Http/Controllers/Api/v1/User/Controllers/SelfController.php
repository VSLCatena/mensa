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

use App\Models\Log;

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
    public function getSelf(Request $request): JsonResponse
    {
        $user = Auth::user();
        if ($user == null) {
            $log = new Log;
            $log->category = "SelfController";
            $log->user_id = "SYSTEM";
            $log->text = "getSelf Auth returned null for " . $request -> getClientIp();
            $log->save();            
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
            $log = new Log;
            $log->category = "SelfController";
            $log->user_id = "SYSTEM";
            $log->text = "updateSelf Auth returned null for " . $request -> getClientIp();
            $log->save();                        
            abort(Response::HTTP_UNAUTHORIZED);
        }

        $validator = Validator::make($request->all(), [
            'allergies' => ['string'],
            'extraInfo' => ['string'],
            'foodPreference' => ['string', 'nullable', Rule::in(FoodOption::allNames())],
        ]);

        if ($validator->fails()) {
            $log = new Log;
            $log->category = "SelfController";
            $log->user_id = "SYSTEM";
            $log->text = "updateSelf validator failed for $user->name";
            $log->save();             
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        if ($request->has('allergies')) $user->allergies = $request->get('allergies');
        if ($request->has('extraInfo')) $user->extra_info = $request->get('extraInfo');
        if ($request->has('foodPreference'))
            $user->food_preference = $this->mapFoodOptionFromNameToInt($request->get('foodPreference'));

        $user->save();
        
        $log = new Log;
        $log->category = "SelfController";
        $log->user_id = "SYSTEM";
        $log->text = "updateSelf performed for ". $user->name;
        $log->save();       
        return null;
    }
}
