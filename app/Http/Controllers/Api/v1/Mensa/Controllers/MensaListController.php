<?php
namespace App\Http\Controllers\Api\v1\Mensa\Controllers;

use App\Http\Controllers\Api\v1\Mensa\Mappers\MensaMapper;
use App\Models\Mensa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class MensaListController extends Controller
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

        $validator = Validator::make($request->all(), ['weekOffset' => ['integer'],]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        if ($request->has('weekOffset')) {
            $startOfWeek = $startOfWeek->addWeeks($request->get('weekOffset'));
        }
        $startTime = $startOfWeek->getTimestamp();
        $endTime = $startOfWeek->addWeeks(2)->getTimestamp()-1;

        $mensas = Mensa::orderBy('date')
            ->whereBetween('date', [$startTime, $endTime])
            ->get()
            ->map(function ($mensa) {
                return self::mapMensa(
                    $mensa,
                    $mensa->users->all(),
                    $mensa->menuItems->all(),
                    $mensa->extraOptions->all(),
                );
            });

        return response()->json([
            "between" => [
                "start" => $startTime,
                "end" => $endTime
            ],
            "mensas" => $mensas
        ]);
    }
}
