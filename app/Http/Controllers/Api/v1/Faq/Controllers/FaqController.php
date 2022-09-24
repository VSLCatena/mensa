<?php

namespace App\Http\Controllers\Api\v1\Faq\Controllers;

use App\Http\Controllers\Api\v1\Faq\Mappers\FaqMapper;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FaqController extends Controller
{

    use FaqMapper;

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
        $faqs = Faq::orderBy('order')
            ->get()
            ->map(function ($faq) {
                return self::mapFaq($faq);
            });

        return response()->json([
            "faqs" => $faqs
        ]);
    }
}
