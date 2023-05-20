<?php

namespace App\Http\Controllers\Api\v1\Faq\Controllers;

use App\Contracts\RemoteUserLookup;
use App\Http\Controllers\Api\v1\Faq\Mappers\FaqMapper;
use App\Http\Controllers\Api\v1\Utils\ValidateOrFail;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Psr\Http\Client\ClientExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class FaqController extends Controller
{
    use FaqMapper, ValidateOrFail;

    private RemoteUserLookup $remoteLookup;

    /**
     * Create a new controller instance.
     */
    public function __construct(RemoteUserLookup $remoteLookup)
    {
        Auth::shouldUse('sanctum');
        $this->remoteLookup = $remoteLookup;
    }

    /** Get a list of faqs */
    public function getFaqs(Request $request): JsonResponse
    {
        $faqs = Faq::orderBy('order')
            ->get()
            ->map(function ($faq) {
                return self::mapFaq($faq);
            });

        return response()->json($faqs);
    }

    /** Sort faqs */
    public function sortFaqs(Request $request): JsonResponse
    {
        // For sorting we require the sort permission
        $currentUser = $this->currentUser();
        Gate::forUser($currentUser)->authorize('sort', Faq::class);

        $fields = [
            'ids' => 'required|array',
            'ids.*' => 'exists:faqs,id',
        ];

        // Check if all validations pass
        $validator = Validator::make($request->all(), $fields);
        $this->validateOrFail($validator);

        $faqOrder = $request->input('ids');
        $faqs = Faq::whereIn('id', $faqOrder)->get();

        foreach ($faqOrder as $order => $faq) {
            $faqModel = $faqs->where('id', $faq)->first();
            $faqModel->order = $order;
            $faqModel->save();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Create a new faq
     */
    public function newFaq(Request $request): JsonResponse
    {
        // For creating we require the create permission
        $currentUser = $this->currentUser();
        Gate::forUser($currentUser)->authorize('create', Faq::class);

        $fields = [
            'question' => 'required|string',
            'answer' => 'required|string',
        ];

        // Check if all validations pass
        $validator = Validator::make($request->all(), $fields);
        $this->validateOrFail($validator);

        $faq = new Faq();
        $faq->id = Str::uuid();
        $faq->question = $request->input('question');
        $faq->answer = $request->input('answer');
        $faq->order = Faq::count();
        $faq->save();

        return response()->json(['success' => true]);
    }

    /**
     * Update a faq
     */
    public function updateFaq(Request $request, string $faqId): JsonResponse
    {

        $faq = Faq::findOrFail($faqId);
        // For updating we require the edit permission
        $currentUser = $this->currentUser();
        Gate::forUser($currentUser)->authorize('edit', $faq);

        $fields = [
            'question' => 'required|string',
            'answer' => 'required|string',
        ];

        // Check if all validations pass
        $validator = Validator::make($request->all(), $fields);
        $this->validateOrFail($validator);
        $faq->question = $request->input('question');
        $faq->answer = $request->input('answer');
        $faq->save();

        return response()->json(['success' => true]);
    }

    /**
     * Delete a faq
     */
    public function deleteFaq(Request $request, string $faqId): JsonResponse
    {
        $faq = Faq::findOrFail($faqId);

        // For deleting we require the delete permission
        $currentUser = $this->currentUser();
        Gate::forUser($currentUser)->authorize('delete', $faq);

        $faq->delete();

        return response()->json(['success' => true]);
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
