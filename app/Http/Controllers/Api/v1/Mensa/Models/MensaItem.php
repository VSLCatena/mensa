<?php
namespace App\Http\Controllers\Api\v1\Mensa\Models;

class MensaItem {
    /**
     * MensaItem constructor.
     * @param string $id
     * @param string $title
     * @param string $description
     * @param int $date
     * @param int $closingTime
     * @param bool $isClosed
     * @param int $maxSignups
     * @param int $signups
     * @param int $dishwashers
     * @param float $price
     * @param SimpleUserItem[] $cooks
     * @param string[] $foodOptions
     * @param MenuItemItem[] $menu
     * @param ExtraOptionItem[] $extraOptions
     */
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public int $date,
        public int $closingTime,
        public bool $isClosed,
        public int $maxSignups,
        public int $signups,
        public float $price,
        public int $dishwashers,
        public array $cooks,
        public array $foodOptions,
        public array $menu,
        public array $extraOptions,
    ) {
    }
}