<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\ExtraOptionDto;
use App\Models\ExtraOption;

class ExtraOptionsMapper
{
    public function map(ExtraOption $extraOption): ExtraOptionDto
    {
        return new ExtraOptionDto(
            id: $extraOption->id,
            description: $extraOption->description,
            price: $extraOption->price
        );
    }

    /**
     * @param  ExtraOption[]  $extraOptions
     * @return ExtraOptionDto[]
     */
    public function mapArray(array $extraOptions): array
    {
        usort($extraOptions, function ($a, $b) {
            return $a->order - $b->order;
        });

        return array_map(function ($item) {
            return self::map($item);
        }, $extraOptions);
    }
}
