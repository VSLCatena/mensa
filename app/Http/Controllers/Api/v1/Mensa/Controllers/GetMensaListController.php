<?php
namespace App\Http\Controllers\Api\v1\Mensa\Controllers;

use App\Http\Controllers\Api\v1\Mensa\Mappers\MensaMapper;
use App\Models\Mensa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class GetMensaListController extends Controller
{

    use MensaMapper;

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
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse {

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
