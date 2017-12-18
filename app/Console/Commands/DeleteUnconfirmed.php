<?php

namespace App\Console\Commands;

use App\Models\MensaUser;
use App\Traits\Logger;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteUnconfirmed extends Command
{
    use Logger;
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
        $unconfirmedUsers = MensaUser::where('confirmed', '0')->where('created_at', '<', Carbon::now()->subMinutes(15))->get();
        foreach($unconfirmedUsers as $user){
            $user->delete();
            $this->log($user->mensa, $user->user->name.'\'s reservering is verlopen en wordt verwijderd.');
        }
    }
}
