<?php

namespace App\Http\Controllers\Api\v1\Faq\Models;

class FaqResponseModel
{
    public function __construct(
        public string $id,
        public string $question,
        public string $answer
    ) {
    }
}
