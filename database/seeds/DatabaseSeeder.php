<?php

use App\Models\ExtraOption;
use App\Models\Faq;
use App\Models\Mensa;
use App\Models\MenuItem;
use App\Models\Signup;
use App\Models\SignupExtraOption;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $this->mensaSeeder();
        $this->faqSeeder();
    }

    private function mensaSeeder() {
        $faker = Faker\Factory::create();

        $users = User::factory()
            ->count(50)
            ->create();

        $mensas = Mensa::factory()
            ->count(20)
            ->create();

        foreach ($mensas as $mensa) {
            MenuItem::factory()
                ->count($faker->numberBetween(0, 5))
                ->for($mensa)
                ->create();

            $extraOptions = ExtraOption::factory()
                ->count($faker->numberBetween(0, 5))
                ->for($mensa)
                ->create();

            $userList = $users->random(rand(0, $users->count()));
            foreach ($userList as $user) {
                /** @var Signup $signup */
                $signup = Signup::factory()
                    ->for($user)
                    ->for($mensa)
                    ->createOne();

                \App\Models\Log::factory()
                    ->for($user)
                    ->for($mensa)
                    ->create();

                $userOptions = $extraOptions->random(rand(0, $extraOptions->count()));
                foreach ($userOptions as $option) {
                    $signup->extraOptions()->attach($option->id);
                }

            }
        }
    }

    private function faqSeeder() {
        Faq::factory()
            ->count(30)
            ->create();
    }
}
