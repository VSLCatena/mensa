<?php

namespace App\Http\Controllers\Api\v1\Faq\Mappers;

use App\Http\Controllers\Api\v1\Faq\Models\FaqResponseModel;
use App\Models\Faq;

trait FaqMapper
{
    function mapFaq(Faq $faq): FaqResponseModel
    {
        return new FaqResponseModel(
            id: $faq->id,
            question: $faq->question,
            answer: $faq->answer,
        );
    }
}