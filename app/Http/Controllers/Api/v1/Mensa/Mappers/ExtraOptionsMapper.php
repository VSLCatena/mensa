<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\ExtraOptionItem;
use App\Http\Controllers\Api\v1\Mensa\Models\SimpleUserItem;
use App\Models\MensaExtraOption;


trait ExtraOptionsMapper {
    /**
     * @param MensaExtraOption $extraOption
     * @return ExtraOptionItem
     */
    function mapExtraOptions(MensaExtraOption $extraOption): ExtraOptionItem {
        return new ExtraOptionItem(
            $extraOption->id,
            $extraOption->description,
        );
    }
}
