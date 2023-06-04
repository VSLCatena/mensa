<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class MensaListDto
{
    /**
     * MensaList constructor.
     *
     * @param BetweenDto $between
     * @param MensaDto[] $mensas
     */
    public function __construct(
        public BetweenDto $between,
        public array      $mensas
    )
    {
    }
}
