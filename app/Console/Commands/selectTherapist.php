<?php

namespace App\Console\Commands;

use App\Api\V1\Helpers\FcmController;
use App\User;
use Illuminate\Console\Command;

class selectTherapist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'select:therapist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'If therapist is not selected then select the therapist.  Cron job Cron job (9:0 am)';

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
     *``
     * @return mixed
     */
    public function handle()
    {

        $clienthavenottherapists = User::whereHas('roles',function($q){
            $q->where('slug','client');
        })->where('is_questionare_completed',1)->has('subscribedPlans')->whereHas('selectedTherapists', function($q){
            $q->where('is_active', 0);
        })->get();
        $fcmController = new FcmController(env('FCM_SERVER_KEY'));
        foreach ($clienthavenottherapists as $key => $user) {
            $description = 'Dear '. $user->name .' Please select your therapist.';
            $fcmController->pushNotification('Select Therapist', $description, 'client', $user->id);
        }
        $this->info('Therapist selection notifications are sent.');
    }
}
