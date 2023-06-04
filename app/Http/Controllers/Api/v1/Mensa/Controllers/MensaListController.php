<?php

namespace App\Http\Controllers\Api\v1\Mensa\Controllers;

use App\Http\Controllers\Api\v1\Mensa\Mappers\MensaMapper;
use App\Http\Controllers\Api\v1\Mensa\Models\BetweenDto;
use App\Http\Controllers\Api\v1\Mensa\Models\MensaDto;
use App\Http\Controllers\Api\v1\Mensa\Models\MensaListDto;
use App\Http\Controllers\Api\v1\Utils\ValidateOrFail;
use App\Models\ExtraOption;
use App\Models\Mensa;
use App\Models\MenuItem;
use App\Models\Signup;
use App\Models\SignupAndUserCombined;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MensaListController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private readonly MensaMapper $mensaMapper,
        private readonly ValidateOrFail $validateOrFail
    )
    {
    }

    /**
     * Get a list of mensas
     */
    public function __invoke(Request $request): JsonResponse
    {
        $currentUser = Auth::guard('sanctum')->user() ?? Auth::getUser();

        $validator = Validator::make($request->all(), ['weekOffset' => ['integer']]);
        $this->validateOrFail->with($validator);

        $startOfWeek = Carbon::now()->startOfWeek(CarbonInterface::MONDAY);
        if ($request->has('weekOffset')) {
            $startOfWeek = $startOfWeek->addWeeks($request->get('weekOffset'));
        }
        $startTime = $startOfWeek->getTimestamp();
        $endTime = $startOfWeek->addWeeks(2)->getTimestamp() - 1;

        // We do the querying of all data in quite a convoluted way
        // But this is the difference between doing 4 queries per mensa, or 4 queries total
        // Here I choose the optimized 4 queries

        // We reuse the mensasQuery, so we only have to manipulate this single query to match affect all other queries
        $mensasQuery = DB::table('mensas')->whereBetween('date', [$startTime, $endTime]);

        // Get all mensas
        $mensas = Mensa::hydrate(
            $mensasQuery->clone()
                ->select('mensas.*')
                ->get()->all()
        )->all();

        // Get a combined list of users and their signups
        $rawUserSignup = $mensasQuery->clone()
            ->join('signups', 'mensas.id', '=', 'signups.mensa_id')
            ->join('users', 'signups.user_id', '=', 'users.id')
            ->select('users.*', 'signups.*')
            ->get()->all();

        // Map the raw user and signup data to a combined model
        $userAndSignups = array_map(function ($user) {
            return new SignupAndUserCombined(
                User::newModelInstance($user),
                Signup::newModelInstance($user)
            );
        }, $rawUserSignup);

        // Get all menu items and extra options
        $menuItems = MenuItem::hydrate(
            $mensasQuery->clone()
                ->join('menu_items', 'mensas.id', '=', 'menu_items.mensa_id')
                ->select('menu_items.*')
                ->get()->all()
        )->all();
        $extraOptions = ExtraOption::hydrate(
            $mensasQuery->clone()
                ->join('extra_options', 'mensas.id', '=', 'extra_options.mensa_id')
                ->select('extra_options.*')
                ->get()->all()
        )->all();

        // Map all mensas to a formatted mensa
        $mensas = $this->mapMensaList($mensas, $userAndSignups, $menuItems, $extraOptions, $currentUser);
        $between = new BetweenDto($startTime, $endTime);
        $mensaList = new MensaListDto($between, $mensas);

        return response()->json($mensaList);
    }

    /**
     * @param Mensa[] $mensas
     * @param SignupAndUserCombined[] $userAndSignups
     * @param MenuItem[] $menuItems
     * @param ExtraOption[] $extraOptions
     * @param User $currentUser
     * @return MensaDto[]
     */
    private function mapMensaList(
        array $mensas,
        array $userAndSignups,
        array $menuItems,
        array $extraOptions,
        User $currentUser
    ): array
    {
        return array_values(
            array_map(
                fn(Mensa $mensa) => $this->mensaMapper->map(
                    $mensa,
                    $this->filterUserAndSignups($userAndSignups, $mensa),
                    $this->filterMenuItems($menuItems, $mensa),
                    $this->filterExtraOptions($extraOptions, $mensa),
                    $currentUser
                ),
                $mensas
            )
        );
    }

    /**
     * @param SignupAndUserCombined[] $userAndSignups
     * @param Mensa $mensa
     * @return SignupAndUserCombined[]
     */
    private function filterUserAndSignups(array $userAndSignups, Mensa $mensa): array
    {
        return array_values(
            array_filter(
                $userAndSignups,
                fn(SignupAndUserCombined $userAndSignup) => $userAndSignup->signup->mensa_id == $mensa->id
            )
        );
    }

    /**
     * @param MenuItem[] $menuItems
     * @param Mensa $mensa
     * @return MenuItem[]
     */
    private function filterMenuItems(array $menuItems, Mensa $mensa): array
    {
        return array_values(
            array_filter(
                $menuItems,
                fn(MenuItem $menuItem) => $menuItem->mensa_id == $mensa->id
            )
        );
    }

    /**
     * @param ExtraOption[] $extraOptions
     * @param Mensa $mensa
     * @return ExtraOption[]
     */
    private function filterExtraOptions(array $extraOptions, Mensa $mensa): array
    {
        return array_values(
            array_filter(
                $extraOptions,
                fn(ExtraOption $extraOption) => $extraOption->mensa_id == $mensa->id
            )
        );
    }
}
