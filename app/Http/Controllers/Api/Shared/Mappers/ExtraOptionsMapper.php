<?php

namespace App\Http\Controllers\Api\Shared\Mappers;

use App\Http\Controllers\Api\Shared\Models\ExtraOption;
use App\Http\Controllers\Api\Shared\Models\SimpleUser;
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
