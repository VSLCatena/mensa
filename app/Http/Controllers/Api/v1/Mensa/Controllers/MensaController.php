<?php
namespace App\Http\Controllers\Api\v1\Mensa\Controllers;

use App\Contracts\RemoteUserLookup;
use App\Http\Controllers\Api\v1\Mensa\Mappers\MensaMapper;
use App\Models\Mensa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class MensaController extends Controller
{

    use MensaMapper;

    private RemoteUserLookup $remoteLookup;

    /**
     * Create a new controller instance.
     *
     * @param RemoteUserLookup $remoteLookup
     */
    public function __construct(RemoteUserLookup $remoteLookup)
    {
        $this->remoteLookup = $remoteLookup;
    }

    /**
     * Get a single mensa
     *
     * @param Request $request
     * @param string $mensaId
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getMensa(Request $request, string $mensaId): JsonResponse {
        $mensa = Mensa::findOrFail($mensaId);

        $currentUser = $this->currentUser();
        Gate::forUser($currentUser)->authorize('seeOverview', $mensa);

        return response()->json(self::mapMensaDetails(
            $mensa,
            $mensa->users->all(),
            $mensa->extraOptions->all(),
        ));
    }


    public function newMensa(Request $request): ?JsonResponse {
        $currentUser = $this->currentUser();
        Gate::forUser($currentUser)->authorize('create', Mensa::class);

        $validator = Validator::make($request->all(), [
            'title' => ['string', 'required'],
            'description' => ['string', 'required'],
            'date' => ['date_format:'.\DateTime::RFC3339, 'required'],
            'closing_time' => ['date_format:'.\DateTime::RFC3339, 'required'],
            'max_users' => ['integer', 'min:0', 'max:999', 'required'],
            'food_options' => ['integer', 'min:1', 'max:7', 'required'],
        ]);
        if ($validator->fails()) return response()->json([ "errors" => $validator->errors()], Response::HTTP_BAD_REQUEST);

        $mensa = Mensa::create([
            'id' => Str::uuid(),
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'date' => Carbon::parse($request->get('date')),
            'closing_time' => Carbon::parse($request->get('closing_time')),
            'food_options' => $request->get('food_options'),
            'max_users' => $request->get('max_users'),
            'closed' => false
        ]);

        $mensa->save();
        return null;
    }

    public function updateMensa(Request $request, string $mensaId): ?JsonResponse {
        $mensa = Mensa::findOrFail($mensaId);

        $currentUser = $this->currentUser();
        Gate::forUser($currentUser)->authorize('softEdit', $mensa);

        if ($request->hasAny(['date', 'closing_time', 'max_users', 'closed'])) {
            Gate::forUser($currentUser)->authorize('hardEdit', $mensa);
        }

        $validator = Validator::make($request->all(), [
            'title' => ['string'],
            'description' => ['string'],
            'date' => ['date_format:'.\DateTime::RFC3339],
            'closing_time' => ['date_format:'.\DateTime::RFC3339],
            'max_users' => ['integer', 'min:0', 'max:999'],
            'closed' => ['boolean'],
            'food_options' => ['integer', 'min:1', 'max:7', 'required'],
        ]);
        if ($validator->fails()) return response()->json([ "errors" => $validator->errors()], Response::HTTP_BAD_REQUEST);

        if ($request->has('title')) $mensa->title = $request->get('title');
        if ($request->has('description')) $mensa->description = $request->get('description');
        if ($request->has('date')) $mensa->date = Carbon::parse($request->get('date'));
        if ($request->has('closing_time')) $mensa->closing_time = Carbon::parse($request->get('closing_time'));
        if ($request->has('max_users')) $mensa->max_users = $request->get('max_users');
        if ($request->has('closed')) $mensa->closed = $request->get('closed');
        if ($request->has('food_options')) $mensa->food_options = $request->get('food_options');

        $mensa->save();
        return null;
    }

    public function deleteMensa(Request $request, string $mensaId): ?JsonResponse {
        $mensa = Mensa::findOrFail($mensaId);

        $currentUser = $this->currentUser();
        Gate::forUser($currentUser)->authorize('delete', $mensa);

        $mensa->delete();
        return null;
    }


    private function currentUser(): ?User {
        try {
            return $this->remoteLookup->currentUpdatedIfNecessary();
        } catch (ClientExceptionInterface) { abort(Response::HTTP_BAD_GATEWAY); }
    }
}
