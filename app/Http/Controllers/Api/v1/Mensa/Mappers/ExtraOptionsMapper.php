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
    function mapExtraOptions(ExtraOption $extraOption): ExtraOptionItem {
        return new ExtraOptionItem(
            $extraOption->id,
            $extraOption->description,
            $extraOption->price
        );
    }
}
