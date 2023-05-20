<?php

namespace App\Http\Controllers\Api\v1\Mensa\Controllers;

use App\Contracts\RemoteUserLookup;
use App\Http\Controllers\Api\v1\Common\Models\FoodOption;
use App\Http\Controllers\Api\v1\Mensa\Mappers\MensaMapper;
use App\Http\Controllers\Api\v1\Utils\ValidateOrFail;
use App\Models\ExtraOption;
use App\Models\Mensa;
use App\Models\MenuItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use InvalidArgumentException;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class MensaController extends Controller
{
    use MensaMapper, ValidateOrFail;

    private RemoteUserLookup $remoteLookup;

    /**
     * Create a new controller instance.
     */
    public function __construct(RemoteUserLookup $remoteLookup)
    {
        Auth::shouldUse('sanctum');
        $this->remoteLookup = $remoteLookup;
    }

    /**  Get a single mensa */
    public function getMensa(Request $request, string $mensaId): JsonResponse
    {
        $mensa = Mensa::findOrFail($mensaId);

        $currentUser = $this->currentUser();

        return response()->json(self::mapMensa(
            $mensa,
            $mensa->users->all(),
            $mensa->menuItems->all(),
            $mensa->extraOptions->all(),
            $currentUser
        ));
    }

    public function newMensa(Request $request): ?JsonResponse
    {
        if ($request->has('id')) {
            throw new InvalidArgumentException('id can\'t exist in request');
        }

        return $this->internalUpdateMensa($request, null);
    }

    public function updateMensa(Request $request, string $mensaId): ?JsonResponse
    {
        if (! $request->has('id')) {
            throw new InvalidArgumentException('id doesn\'t exist in request');
        }

        return $this->internalUpdateMensa($request, $mensaId);
    }

    private function internalUpdateMensa(Request $request, string|null $mensaId): ?JsonResponse
    {
        $currentUser = $this->currentUser();

        // All the fields and requirements are defined here
        $fields = [
            'title' => ['string'],
            'description' => ['string'],
            'date' => ['integer'],
            'closingTime' => ['integer'],
            'maxUsers' => ['integer', 'min:0', 'max:999'],
            'closed' => ['boolean'],
            'foodOptions' => ['array', Rule::in(FoodOption::allNames())],
            'price' => ['numeric', 'between:0,999'],

            'menu.*.id' => ['exists:menu_items,id'],
            'menu.*.text' => ['string', 'required'],

            'extraOptions.*.id' => ['exists:extra_options,id'],
            'extraOptions.*.price' => ['numeric', 'between:0,999', 'required'],
            'extraOptions.*.description' => ['string', 'required'],
        ];
        $hardRequestFields = ['date', 'closing_time', 'max_users', 'closed'];

        /** @var Mensa $mensa */
        $mensa = null;
        // If mensa is null we create a new one
        if ($mensaId == null) {
            // For creating we require the create permission
            Gate::forUser($currentUser)->authorize('create', Mensa::class);

            // We create a new mensa
            $mensa = Mensa::create(['id' => Str::uuid()]);

            // For new mensas we require everything
            array_walk($fields, function (&$value) {
                $value[] = 'required';
            });
        } else {
            // And we get the mensa
            $mensa = Mensa::findOrFail($mensaId);

            // For editing we require soft editing permission
            Gate::forUser($currentUser)->authorize('softEdit', $mensa);
            if ($request->hasAny($hardRequestFields)) {
                // And for some fields hard editing permission
                Gate::forUser($currentUser)->authorize('hardEdit', $mensa);
            }
        }

        // Check if all validations pass
        $validator = Validator::make($request->all(), $fields);
        $this->validateOrFail($validator);

        // Update the mensa fields
        $fields = ['title', 'description', 'date', 'closingTime', 'maxUsers', 'closed', 'price'];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                $mensa->$field = $request->get($field);
            }
        }

        if ($request->has('foodOptions')) {
            // We map the options to an int
            $options = $this->mapFoodOptionsFromNamesToInt($request->get('foodOptions'));
            $mensa->food_options = $options;
        }

        // We want to save and delete items only when everything is correctly validated.
        $itemsToSave = [];
        $itemsToDelete = [];

        // Menu
        if ($request->has('menu')) {
            $result = $this->compareAndUpdate(
                $mensa, $mensa->menuItems(), MenuItem::class, $request->get('menu'),
                function (MenuItem $menuItem, $rawData) {
                    $menuItem->text = $rawData['text'];
                }
            );

            $itemsToSave = array_merge($itemsToSave, $result['save']);
            $itemsToDelete = array_merge($itemsToDelete, $result['delete']);
        }

        // Extra options
        if ($request->has('extraOptions')) {
            $result = $this->compareAndUpdate(
                $mensa, $mensa->extraOptions(), ExtraOption::class, $request->get('extraOptions'),
                function (ExtraOption $extraOption, $rawData) {
                    $extraOption->description = $rawData['description'];
                    $extraOption->price = $rawData['price'];
                }
            );

            $itemsToSave = array_merge($itemsToSave, $result['save']);
            $itemsToDelete = array_merge($itemsToDelete, $result['delete']);
        }

        // And now save and delete
        $mensa->save();

        foreach ($itemsToSave as $save) {
            $save->save();
        }

        foreach ($itemsToDelete as $delete) {
            $delete->delete();
        }

        return null;
    }

    public function deleteMensa(Request $request, string $mensaId): ?JsonResponse
    {
        $mensa = Mensa::findOrFail($mensaId);

        $currentUser = $this->currentUser();
        Gate::forUser($currentUser)->authorize('delete', $mensa);

        $mensa->delete();

        return null;
    }

    /**
     * We moved a lot of duplicate code to a separate function so it's less error-prone.
     * Summed up: You give it a list of a current collection, an array with form data, and a transformer.
     * - It will loop over the form data, grab the item if the form data contains an id, otherwise creates a new one
     * - It will update the order on the object
     * - It will call the updater function with the item and the raw data
     * - It will figure out with the current collection which one are not in it anymore (up for deletion)
     * - It will return an associated array with models to save, and models to delete
     */
    #[ArrayShape([
        'save' => 'Illuminate\\Database\\Eloquent\\Model[]',
        'delete' => 'Illuminate\\Database\\Eloquent\\Model[]',
    ])]
    private function compareAndUpdate(
        Mensa $mensa,
        HasMany $collection,
        string $class,
        array $rawData,
        $updater
    ): array {
        $optionsToSave = [];
        $optionsToDelete = [];

        // Loop over all the raw data
        foreach ($rawData as $order => $rawItem) {
            /** @var Model $item */
            $item = null;
            // If the raw data contains an id we try to find it in the collection given or fail if it wasn't able to
            if ($rawItem['id'] != null) {
                $item = $collection->clone()->findOrFail($rawItem['id']);
            } else {
                // Otherwise we create a new object and associate it to the mensa
                $item = $class::create(['id' => Str::uuid()]);
                $item->mensa()->associate($mensa);
            }

            // We update the order
            $item->order = $order;

            // We call the updater function with the item and the raw data
            $updater($item, $rawItem);
            // And add it to the save list
            $optionsToSave[] = $item;
        }

        // Then we check if we should delete any items by going over all previous items, and see if there are items that
        // are not in the new $optionsToSave array.
        foreach ($collection as $prevItem) {
            $comparer = function ($item) use ($prevItem) {
                return $item->id == $prevItem->id;
            };

            // If we were able to find the item we skip it
            if (array_first($optionsToSave, $comparer) != null) {
                continue;
            }

            // If we weren't able to find the item we add it up for deletion
            $optionsToDelete[] = $prevItem;
        }

        // Return the save and delete list
        return [
            'save' => $optionsToSave,
            'delete' => $optionsToDelete,
        ];
    }

    private function currentUser(): ?User
    {
        try {
            return $this->remoteLookup->currentUpdatedIfNecessary();
        } catch (ClientExceptionInterface) {
            abort(Response::HTTP_BAD_GATEWAY);
        }
    }
}
