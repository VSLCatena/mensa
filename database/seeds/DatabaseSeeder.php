<?php

use App\Models\ExtraOption;
use App\Models\Faq;
use App\Models\Mensa;
use App\Models\MenuItem;
use App\Models\Signup;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(
        $userCount = 30,
        $mensaCount = 50,
        $maxMenuItemPerMensaCount = 5,
        $maxExtraOptionPerMensaCount = 5,
        $maxSignupPerMensaCount = 10,
        $faqCount = 10,
        $enforceAtLeastOne = false

    )
    {
        DB::transaction(function () use (
            $userCount,
            $mensaCount,
            $maxMenuItemPerMensaCount,
            $maxExtraOptionPerMensaCount,
            $maxSignupPerMensaCount,
            $faqCount,
            $enforceAtLeastOne
        ) {
            $this->command?->info('-- Starting Mensa seeder --');
            $this->mensaSeeder(
                $userCount,
                $mensaCount,
                $maxMenuItemPerMensaCount,
                $maxExtraOptionPerMensaCount,
                $maxSignupPerMensaCount,
                $enforceAtLeastOne
            );
            $this->command?->info('-- Starting Faq seeder --');
            $this->faqSeeder($faqCount);
        });
    }

    private function mensaSeeder(
        $userCount = 30,
        $mensaCount = 50,
        $maxMenuItemPerMensaCount = 5,
        $maxExtraOptionPerMensaCount = 5,
        $maxSignupPerMensaCount = 10,
        $enforceAtLeastOne = false
    )
    {
        $faker = Faker\Factory::create();

        $this->command?->info('- Creating users -');
        $users = User::factory()
            ->count($userCount)
            ->create();

        $this->command?->info('- Creating mensas -');
        $mensas = Mensa::factory()
            ->count($mensaCount)
            ->create();

        foreach ($mensas as $key => $mensa) {
            $this->command?->info("- Seeding mensa $mensa->id ($key/50) -");

            $this->command?->info('Creating menu items');
            MenuItem::factory()
                ->count($faker->numberBetween($enforceAtLeastOne ? 1 : 0, $maxMenuItemPerMensaCount))
                ->for($mensa)
                ->create();

            $this->command?->info('Creating extra options');
            $extraOptions = ExtraOption::factory()
                ->count($faker->numberBetween($enforceAtLeastOne ? 1 : 0, $maxExtraOptionPerMensaCount))
                ->for($mensa)
                ->create();

            $this->command?->info('Signup a random amount of users');
            $userList = $users->random(
                rand($enforceAtLeastOne ? 1 : 0, min($users->count(), $mensa->max_signups, $maxSignupPerMensaCount)));
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

                $userOptions = $extraOptions->random(rand($enforceAtLeastOne ? 1 : 0, $extraOptions->count()));
                foreach ($userOptions as $option) {
                    $signup->extraOptions()->attach($option->id);
                }

            }
        }
    }

    private function faqSeeder($faqCount)
    {
        Faq::factory()
            ->count($faqCount)
            ->create();
    }
}
