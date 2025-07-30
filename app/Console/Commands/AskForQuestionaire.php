<?php

namespace App\Console\Commands;

use App\Api\V1\Helpers\FcmController;
use App\Models\User;
use App\Therapist;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AskForQuestionaire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'select:questionaire';

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
     *
     * @return mixed
     */
    public function handle()
    {
        $users= \App\User::where('is_questionare_completed', 0)->whereHas('roles',function($q) {
            $q->where('slug','client');
            $q->orWhere('slug','therapist');
        })->get();

        $fcmController = new FcmController(env('FCM_SERVER_KEY'));
        $description = 'Please fill out your intake questionnaire';

        foreach ($users as $key => $user) {

            Log::info(' === === = = = Questions notification sent to ' . $user->id . ' name ' .  $user->name . ' ' . ' email ' . $user->email . ' at ' . Carbon::now()->format('m/d/Y h:i A'));

            $fcmController->pushNotification('Fill Questions', $description, 'questionaire', $user->id);
        }
        $this->info('All notifications are sent.');
    }
}
