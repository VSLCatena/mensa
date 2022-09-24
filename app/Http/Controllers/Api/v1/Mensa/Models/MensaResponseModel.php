<?php

namespace App\Http\Controllers\Api\v1\Mensa\Models;

class MensaResponseModel
{
    /**
     * MensaResponseModel constructor.
     * @param string $id
     * @param string $title
     * @param string $description
     * @param int $date
     * @param int $closingTime
     * @param bool $isClosed
     * @param int $maxSignups
     * @param int|SimpleUserResponseModel[] $signups
     * @param int $dishwashers
     * @param float $price
     * @param SimpleUserResponseModel[] $cooks
     * @param string[] $foodOptions
     * @param MenuItemResponseModel[] $menu
     * @param ExtraOptionResponseModel[] $extraOptions
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
    )
    {
    }
}