<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

use App\Http\Controllers\Api\v1\Common\Models\SimpleUserDto;

class MensaDto
{
    /**
     * MensaDto constructor.
     *
     * @param  int|MensaUserDto[]  $signups
     * @param  SimpleUserDto[]  $cooks
     * @param  string[]  $foodOptions
     * @param  MenuItemDto[]  $menu
     * @param  ExtraOptionDto[]  $extraOptions
     * @param  int  $dishwashers
     * @param  float  $price
     * @param  int  $maxSignups
     * @param  bool  $isClosed
     * @param  int  $closingTime
     * @param  int  $date
     * @param  string  $description
     * @param  string  $title
     * @param  string  $id
     * @return void
     */
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public int $date,
        public int $closingTime,
        public bool $isClosed,
        public int $maxSignups,
        public int|array $signups,
        public float $price,
        public int $dishwashers,
        public array $cooks,
        public array $foodOptions,
        public array $menu,
        public array $extraOptions,
    ) {
    }
}
