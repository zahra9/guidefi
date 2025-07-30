<?php

namespace App\Console\Commands;

use App\Api\V1\Helpers\FcmController;
use App\Clientrequest;
use App\Repositories\EmailManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConnectedAdvisor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:connected_advisor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a cron job that sync the client about their advisors count';

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

        $clients = Clientrequest::where('advisor_exist',1)->get();

        foreach($clients as $client){
            $userId = $client->user->id;
            $result = DB::select("CALL getAdvisorList($userId)");
            if(count($result) > 0 ){
                // EMAIL TRIGGER after client confirmation of advisor selection
                $text = "We found new matches! Log into the Guidefi app to see your advisor matches!<br>
                For technical difficulties, please email<br><br>";

                EmailManager::sendMailQueue('CLIENT CONFIRMATION', $text, $client->user->name, $client->user->email);

                // NOTIFICATION TRIGGER after client confirmation of advisor selection
                $fcmController = new FcmController(env('FCM_SERVER_KEY'));
                $response = $fcmController->pushNotification('Match Added', $text, 'feedback', $client->user->id);
                $client->update(['advisor_exist'=>0]);

            }
        }
        Log::info('This is a cron job that sync the client about their advisors match!!!');
    }
}
