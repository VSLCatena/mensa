<?php

namespace App\Console\Commands;

use App\Models\Mensa;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MensaAutocreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mensa:autocreate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Autocreate a new mensa in two weeks if none exist yet';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // We want to create a mensa 2 weeks from now. We do this every day (Except weekends)
        // To do that we need to check
        $count = Mensa::whereBetween('date', [Carbon::today()->addWeeks(2), Carbon::today()->addWeeks(2)->addDay()])->count();
        if ($count < 1) {
            $this->info('No mensas found two weeks from now, creating...');
            $mensa = new Mensa();
            $mensa->title = config('mensa.default.name');
            $mensa->max_users = config('mensa.default.max_users');
            $mensa->price = config('mensa.default.price');
            $mensa->date = Carbon::today()->addWeeks(2)->setTimeFromTimeString(config('mensa.default.start_time'));
            $mensa->closing_time = Carbon::today()->addWeeks(2)->setTimeFromTimeString(config('mensa.default.closing_time'));
            $mensa->save();
            $this->info('Created a new mensa using the default values');
        } else {
            $this->info('Already found an existing mensa in two weeks, all good!');
        }
    }
}
