<?php

namespace App\Console\Commands;

use App\Models\Signup;
use App\Services\MensaLogger;
use App\Traits\Logger;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteUnconfirmed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:deleteunconfirmed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete unconfirmed users';


    private MensaLogger $mensaLogger;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(MensaLogger $mensaLogger)
    {
        parent::__construct();
        $this->mensaLogger = $mensaLogger;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $unconfirmedUsers = Signup::where('confirmed', '0')->where('created_at', '<', Carbon::now()->subMinutes(15))->get();
        foreach ($unconfirmedUsers as $user) {
            $user->delete();
            $this->mensaLogger->log($user->mensa, $user->user->name . '\'s reservering is verlopen en wordt verwijderd.');
        }
    }
}
