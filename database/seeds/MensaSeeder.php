<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MensaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        foreach (range(1, 20) as $x) {
            $this->makeMensa();
        }
    }

    private function makeMensa() {
        $date = Carbon::createFromTimestamp(rand());
        DB::table('mensas')->insert([
            'id' => Str::uuid(),
            'title' => Str::random(),
            'description' => Str::random(),
            'date' => $date,
            'closing_time' => $date,
            'max_users' => 42,
        ]);
    }
}
