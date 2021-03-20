<?php
namespace App\Http\Controllers\Api\v1\Mensa\Models;

class MensaItem {
    /**
     * MensaItem constructor.
     * @param string $id
     * @param string $title
     * @param string $date
     * @param string $closingTime
     * @param SimpleUser[]|int $users
     * @param int $maxUsers
     * @param bool $isClosed
     * @param SimpleUser[]|int $dishwashers
     * @param SimpleUser[] $cooks
     * @param ExtraOption[] $extraOptions
     */
    public function __construct(
        public string $id,
        public string $title,
        public string $date,
        public string $closingTime,
        public array|int $users,
        public int $maxUsers,
        public bool $isClosed,
        public array|int $dishwashers,
        public array $cooks,
        public array $extraOptions,
    ) {
    }
}