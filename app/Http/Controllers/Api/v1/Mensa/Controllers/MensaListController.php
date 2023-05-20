<?php

namespace App\Http\Controllers\Api\v1\Mensa\Controllers;

use App\Http\Controllers\Api\v1\Mensa\Mappers\MensaMapper;
use App\Http\Controllers\Api\v1\Utils\ValidateOrFail;
use App\Models\ExtraOption;
use App\Models\Mensa;
use App\Models\MenuItem;
use App\Models\Signup;
use App\Models\SignupAndUserCombined;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MensaListController extends Controller
{
    use MensaMapper, ValidateOrFail;

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
     */
    public function __invoke(Request $request): JsonResponse
    {
        $currentUser = Auth::guard('sanctum')->user() ?? Auth::getUser();

        $validator = Validator::make($request->all(), ['weekOffset' => ['integer']]);
        $this->validateOrFail($validator);

        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        if ($request->has('weekOffset')) {
            $startOfWeek = $startOfWeek->addWeeks($request->get('weekOffset'));
        }
        $startTime = $startOfWeek->getTimestamp();
        $endTime = $startOfWeek->addWeeks(2)->getTimestamp() - 1;

        // We do the querying of all data in quite a convoluted way
        // But this is the difference between doing 4 queries per mensa, or 4 queries total
        // Here I choose the optimized 4 queries

        // We reuse the mensasQuery so we only have to manipulate this single query to match affect all other queries
        $mensasQuery = DB::table('mensas')->whereBetween('date', [$startTime, $endTime]);

        $mensas = Mensa::hydrate(
            $mensasQuery->clone()
                ->select('mensas.*')
                ->get()->all()
        );

        $rawUserSignup = $mensasQuery->clone()
            ->join('signups', 'mensas.id', '=', 'signups.mensa_id')
            ->join('users', 'signups.user_id', '=', 'users.id')
            ->select('users.*', 'signups.*')
            ->get()->all();

        $userAndSignups = array_map(function ($user) {
            return new SignupAndUserCombined(
                User::newModelInstance($user),
                Signup::newModelInstance($user)
            );
        }, $rawUserSignup);

        $menuItems = MenuItem::hydrate(
            $mensasQuery->clone()
                ->join('menu_items', 'mensas.id', '=', 'menu_items.mensa_id')
                ->select('menu_items.*')
                ->get()->all()
        );
        $extraOptions = ExtraOption::hydrate(
            $mensasQuery->clone()
                ->join('extra_options', 'mensas.id', '=', 'extra_options.mensa_id')
                ->select('extra_options.*')
                ->get()->all()
        );

        $formattedMensas = $mensas
            ->map(function ($mensa) use ($userAndSignups, $menuItems, $extraOptions, $currentUser) {
                $mensaUsers = array_filter($userAndSignups, function ($uas) use ($mensa) {
                    return $uas->signup->mensa_id == $mensa->id;
                });
                $mensaMenuItems = $menuItems->filter(function ($menuItem) use ($mensa) {
                    return $menuItem->mensa_id == $mensa->id;
                })->all();
                $mensaExtraOptions = $extraOptions->filter(function ($extraOption) use ($mensa) {
                    return $extraOption->mensa_id == $mensa->id;
                })->all();

                return self::mapMensa($mensa, $mensaUsers, $mensaMenuItems, $mensaExtraOptions, $currentUser);
            });

        return response()->json([
            'between' => [
                'start' => $startTime,
                'end' => $endTime,
            ],
            'mensas' => $formattedMensas,
        ]);
    }
}
