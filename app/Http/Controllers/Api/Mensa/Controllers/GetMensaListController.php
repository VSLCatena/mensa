<?php
namespace App\Http\Controllers\Api\Mensa\Controllers;

use App\Http\Controllers\Api\Mensa\Mappers\MensaMapper;
use App\Http\Helpers\UserHelper;
use App\Models\Mensa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class GetMensaListController extends Controller
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
     * Get a list of mensas
     *
     * Url: mensa/list?limit=[10]&fromLastId=[uuid]
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getMensas(Request $request) {

        $validator = Validator::make($request->all(), [
            'limit' => ['required', 'min:1', 'max: 25'],
            'fromLastId' => ['App\Models\Mensa,id'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $limit = $request->get('limit');

        $query = Mensa::orderByDesc('date')
            ->limit($limit);

        if ($request->has('fromLastId')) {
            $fromLastId = $request->get('fromLastId');

            $dateFrom = Mensa::findOrFail($fromLastId)->date;
            $query = $query->where('date', '<', $dateFrom);
        }

        $mensas = $query->get()->map(function ($mensa) {
            return self::mapMensa(
                $mensa,
                $mensa->users->all(),
                $mensa->extraOptions->all(),
            );
        });

        return response()->json($mensas);
    }
}
