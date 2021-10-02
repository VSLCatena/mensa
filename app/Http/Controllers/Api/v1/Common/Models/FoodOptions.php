<?php

namespace App\Http\Controllers\Api\v1\Common\Models;

class FoodOptions {
    const VEGAN = 1 << 0;
    const VEGETARIAN = 1 << 1;
    const MEAT = 1 << 2;
}