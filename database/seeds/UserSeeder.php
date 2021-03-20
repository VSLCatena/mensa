<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        foreach (range(0, 5) as $x) {
            $this->makeUser();
        }
    }


    private function makeUser() {
        DB::table('users')->insert([
            'id' => Str::uuid(),
            'name' => Str::random(),
            'email' => Str::random() . '@example.com',
            'vegetarian' => rand(0, 1)
        ]);
    }
}
