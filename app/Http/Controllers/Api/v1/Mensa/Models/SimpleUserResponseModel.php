<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class SimpleUserResponseModel
{

    /**
     * SimpleUserResponseModel constructor.
     * @param string $id
     * @param string $name
     */
    public function __construct(
        public string $id,
        public string $name,
    )
    {
    }
}