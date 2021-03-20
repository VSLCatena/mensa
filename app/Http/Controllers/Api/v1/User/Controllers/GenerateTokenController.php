<?php
namespace App\Http\Controllers\Api\v1\User\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GenerateTokenController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get a single mensa
     *
     * Url: mensa/[uuid]
     *
     * @param Request $request
     * @param $mensaId
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse {
        $azureUser = Socialite::driver('azure')->stateless()->user();

        $user = User::find($azureUser->id) ?? new User();

        if (!$user->exists)
            $user->id = $azureUser->id;


        $user->name = $azureUser->name;
        $user->email = $azureUser->email;
        $user->mensa_admin = in_array(env('AZURE_ADMIN_GROUP_ID'), $azureUser->groups);
        $user->save();

        return response()->json([
            'token' => $user->createToken(Str::uuid())->plainTextToken
        ]);
    }
}
