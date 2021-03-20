<?php

namespace App\Http\Controllers\Api\v1\Mensa\Mappers;

use App\Http\Controllers\Api\v1\Mensa\Models\ExtraOption;
use App\Http\Controllers\Api\v1\Mensa\Models\SimpleUser;
use App\Models\MensaExtraOption;


trait ExtraOptionsMapper {
    /**
     * @param MensaExtraOption $extraOption
     * @return SimpleUser
     */
    function mapExtraOptions(MensaExtraOption $extraOption): ExtraOption {
        return new ExtraOption(
            $extraOption->id,
            $extraOption->description,
        );
    }
}
