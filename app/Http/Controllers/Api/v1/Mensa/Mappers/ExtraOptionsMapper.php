<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\ExtraOptionItem;
use App\Http\Controllers\Api\v1\Mensa\Models\SimpleUserItem;
use App\Models\ExtraOption;


trait ExtraOptionsMapper {
    /**
     * @param ExtraOption $extraOption
     * @return ExtraOptionItem
     */
    function mapExtraOption(ExtraOption $extraOption): ExtraOptionItem {
        return new ExtraOptionItem(
            $extraOption->id,
            $extraOption->description,
            $extraOption->order,
            $extraOption->price
        );
    }

    /**
     * @param ExtraOption[] $extraOptions
     * @return ExtraOptionItem[]
     */
    function mapExtraOptions(array $extraOptions): array {
        usort($extraOptions, function ($a, $b) { return $a->order - $b->order; });
        return array_map(function ($item) { return self::mapExtraOption($item); }, $extraOptions);
    }
}
