<?php
namespace App\Http\Controllers\Api\v1\Mensa\Controllers;

use App\Http\Controllers\Api\v1\Mensa\Mappers\MensaMapper;
use App\Http\Helpers\Permissions;
use App\Http\Helpers\UserHelper;
use App\Models\Mensa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class GetMensaController extends Controller
{

    use MensaMapper;

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
     * Get a single mensa
     *
     * @param Request $request
     * @param string $mensaId
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Request $request, string $mensaId): JsonResponse {
        $mensa = Mensa::findOrFail($mensaId);

        Gate::authorize('seeOverview', $mensa);

        return response()->json(self::mapMensaDetails(
            $mensa,
            $mensa->users->all(),
            $mensa->extraOptions->all(),
        ));
    }
}
