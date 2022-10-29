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
    public function run()
    {
        $this->command->info('-- Starting Mensa seeder --');
        $this->mensaSeeder();
        $this->command->info('-- Starting Faq seeder --');
        $this->faqSeeder();
    }

    private function mensaSeeder()
    {
        $faker = Faker\Factory::create();

        $this->command->info('- Creating SYSTEM user -');
        $system = new User;
        $system->id="1";
        $system->name="SYSTEM";
        $system->email='';
        $system->remote_last_check=0;
        $system->remote_principal_name='';
        $system->save();        
        
        $this->command->info('- Creating users -');
        $users = User::factory()
            ->count(30)
            ->create();

        $this->command->info('- Creating mensas -');
        $mensas = Mensa::factory()
            ->count(50)
            ->create();

        foreach ($mensas as $key => $mensa) {
            $this->command->info("- Seeding mensa $mensa->id ($key/50) -");

            $this->command->info('Creating menu items');
            MenuItem::factory()
                ->count($faker->numberBetween(0, 5))
                ->for($mensa)
                ->create();

            $this->command->info('Creating extra options');
            $extraOptions = ExtraOption::factory()
                ->count($faker->numberBetween(0, 5))
                ->for($mensa)
                ->create();

            $this->command->info('Signup a random amount of users');
            $userList = $users->random(rand(0, min($users->count(), $mensa->max_users)));
            foreach ($userList as $user) {
                if($user->name == $system->name) {continue;}
                /** @var Signup $signup */
                $signup = Signup::factory()
                    ->for($user)
                    ->for($mensa)
                    ->createOne();

                \App\Models\Log::factory()
                ->state([
                    'category' => 'mensa',
                    'text' => 'DatabaseSeeder/mensaSeeder/signup/Signed up '.$user->name,
                    ])->for(Mensa::factory(), 'loggable')
                    ->for($system)          
                    ->create();

                $userOptions = $extraOptions->random(rand(0, $extraOptions->count()));
                foreach ($userOptions as $option) {
                    $signup->extraOptions()->attach($option->id);
                }

            }
        }
    }

    private function faqSeeder()
    {
        Faq::factory()
            ->count(30)
            ->create();
    }
}
