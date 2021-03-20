<?php
namespace App\Http\Controllers\Api\Mensa\Controllers;

use App\Http\Controllers\Api\Mensa\Mappers\MensaMapper;
use App\Http\Helpers\Permissions;
use App\Http\Helpers\UserHelper;
use App\Models\Mensa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GetMensaController extends Controller
{

    use MensaMapper, UserHelper;

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
    public function getMensa(Request $request, $mensaId) {
        $mensa = Mensa::findOrFail($mensaId);

        $canRequestMoreInfo = $this->hasUserPermission($request->user(), Permissions::MENSA_MORE_INFO);

        return response()->json(self::mapMensa(
            $mensa,
            $mensa->users->all(),
            $mensa->extraOptions->all(),
            $canRequestMoreInfo,
            $canRequestMoreInfo
        ));
    }
}
