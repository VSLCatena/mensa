<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\ExtraOptionResponseModel;
use App\Models\ExtraOption;


trait ExtraOptionsMapper
{
    /**
     * @param ExtraOption $extraOption
     * @return ExtraOptionResponseModel
     */
    function mapExtraOption(ExtraOption $extraOption): ExtraOptionResponseModel
    {
        return new ExtraOptionResponseModel(
            id: $extraOption->id,
            description: $extraOption->description,
            order: $extraOption->order,
            price: $extraOption->price
        );
    }

    /**
     * @param ExtraOption[] $extraOptions
     * @return ExtraOptionResponseModel[]
     */
    function mapExtraOptions(array $extraOptions): array
    {
        usort($extraOptions, function ($a, $b) {
            return $a->order - $b->order;
        });
        return array_map(function ($item) {
            return self::mapExtraOption($item);
        }, $extraOptions);
    }
}
