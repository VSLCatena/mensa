<?php
namespace App\Http\Controllers\Api\v1\Mensa\Models;

class MensaDetailItem {
    /**
     * MensaDetailItem constructor.
     * @param string $id
     * @param string $title
     * @param string $description
     * @param string $date
     * @param string $closingTime
     * @param bool $isClosed
     * @param int $maxSignups
     * @param MensaUserItem[] $signups
     * @param ExtraOptionItem[] $extraOptions
     */
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public string $date,
        public string $closingTime,
        public bool $isClosed,
        public int $maxSignups,
        public array $signups,
        public array $extraOptions,
    ) {
    }
}