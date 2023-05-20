<?php

namespace App\Http\Controllers\Api\v1\Utils;

use Illuminate\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

trait ValidateOrFail
{
    public function validateOrFail(Validator $validator): void
    {
        if ($validator->fails()) {
            abort(Response::HTTP_BAD_REQUEST, json_encode([
                'error_code' => ErrorMessages::GENERAL_VALIDATION_ERROR,
                'errors' => $validator->errors(),
            ]));
        }
    }
}
