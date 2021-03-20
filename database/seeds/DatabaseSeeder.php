<?php

use Database\Seeders\MensaSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MensaSeeder::class);
        $this->call(UserSeeder::class);
    }
}
