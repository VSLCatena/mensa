<?php

namespace App\Http\Controllers\Api\v1\Faq\Controllers;

use App\Contracts\RemoteUserLookup;
use App\Http\Controllers\Api\v1\Faq\Mappers\FaqMapper;
use App\Http\Controllers\Api\v1\Utils\ValidateOrFail;
use App\Models\Faq;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
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
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private readonly RemoteUserLookup $remoteLookup,
        private readonly FaqMapper $faqMapper,
        private readonly ValidateOrFail $validateOrFail
    )
    {
        Auth::shouldUse('sanctum');
    }

    /** Get a list of faqs
     * @noinspection PhpUnused
     */
    public function getFaqs(): JsonResponse
    {
        $faqs = Faq::orderBy('order')
            ->get()
            ->map(function ($faq) {
                return $this->faqMapper->map($faq);
            });

        return response()->json($faqs);
    }

    /** Sort faqs
     * @throws AuthorizationException
     * @noinspection PhpUnused
     */
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
        $this->validateOrFail->with($validator);

        $faqOrder = array_values($request->input('ids'));
        $faqs = Faq::whereIn('id', $faqOrder)->get();

        foreach ($faqOrder as $order => $faqId) {
            $faqModel = $faqs->where('id', $faqId)->first();
            $faqModel->order = $order;
            $faqModel->save();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Create a new faq
     * @throws AuthorizationException
     * @noinspection PhpUnused
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
        $this->validateOrFail->with($validator);

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
     * @throws AuthorizationException
     * @noinspection PhpUnused
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
        $this->validateOrFail->with($validator);
        $faq->question = $request->input('question');
        $faq->answer = $request->input('answer');
        $faq->save();

        return response()->json(['success' => true]);
    }

    /**
     * Delete a faq
     * @noinspection PhpUnused
     * @throws AuthorizationException
     */
    public function deleteFaq(string $faqId): JsonResponse
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
        // @codeCoverageIgnoreStart
        } catch (ClientExceptionInterface) {
            abort(Response::HTTP_BAD_GATEWAY);
        }
        // @codeCoverageIgnoreEnd
    }
}
