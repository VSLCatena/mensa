<?php

namespace App\Http\Controllers\Api\v1\Mensa\Controllers;

use App\Http\Controllers\Api\v1\Mensa\Mappers\MensaMapper;
use App\Http\Controllers\Api\v1\Utils\ErrorMessages;
use App\Models\Mensa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

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
    public function __invoke(Request $request): JsonResponse
    {
        $user = Auth::guard('sanctum')->user() ?? Auth::getUser();

        $validator = Validator::make($request->all(), ['weekOffset' => ['integer'],]);

        if ($validator->fails()) {
            return response()->json([
                "error_code" => ErrorMessages::GENERAL_VALIDATION_ERROR,
                "errors" => $validator->errors()
            ], Response::HTTP_BAD_REQUEST);
        }

        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        if ($request->has('weekOffset')) {
            $startOfWeek = $startOfWeek->addWeeks($request->get('weekOffset'));
        }
        $startTime = $startOfWeek->getTimestamp();
        $endTime = $startOfWeek->addWeeks(2)->getTimestamp() - 1;

        $mensas = Mensa::orderBy('date')
            ->whereBetween('date', [$startTime, $endTime])
            ->get()
            ->map(function ($mensa) use ($user) {
                return self::mapMensa(
                    $mensa,
                    $mensa->users->all(),
                    $mensa->menuItems->all(),
                    $mensa->extraOptions->all(),
                    $user != null
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
