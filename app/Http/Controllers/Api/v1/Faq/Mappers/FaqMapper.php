<?php

namespace App\Http\Controllers\Api\v1\Faq\Mappers;

use App\Http\Controllers\Api\v1\Faq\Models\FaqDto;
use App\Models\Faq;

class FaqMapper
{
    public function map(Faq $faq): FaqDto
    {
        return new FaqDto(
            id: $faq->id,
            question: $faq->question,
            answer: $faq->answer,
        );
    }
}
