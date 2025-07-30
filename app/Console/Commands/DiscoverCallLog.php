<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\DiscoverCallLogs;
use App\Api\V1\Helpers\FcmController;
use Illuminate\Support\Facades\Log;

class DiscoverCallLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:discoveryCallLogs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'After discovery call done, this Command is running for sending email and notification with time summary ';

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
        $discoveryCalls = DiscoverCallLogs::with('client.person')->with('advisor.person')->
        where('email_notify',0)->
        selectRaw('TIMEDIFF(end_time, start_time) as total_minutes,client_id,advisor_id,id')->get();

        $groupSameEntity = $discoveryCalls ->groupBy('client_id','advisor_id');

        foreach ($groupSameEntity as $discoveryCall){
            if(count($discoveryCall) >= 1){
            $sum = strtotime('00:00:00');
            $sum2 = 0;
            foreach ($discoveryCall as $v){
                $sum1= strtotime($v->total_minutes)-$sum;
                $sum2 = $sum2 + $sum1;
                \App\DiscoverCallLogs::whereId($v->id)->update(['email_notify'=>1]);
            }
            $sum3 = $sum + $sum2;

            // EMAIL TRIGGER after client confirmation of advisor selection
            $textAdvisor = "Your discovery call is done. Now you can send an offer to client(".$discoveryCall[0]->client->name.")<br>
                For technical difficulties, please email<br><br>";
            $textClient = "Discovery call is done with advisor(".$discoveryCall[0]->advisor->name. "). Total Numbers of mintues are: ".date("H:i:s",$sum3)."<br>
                For technical difficulties, please email<br><br>";

            \App\Repositories\EmailManager::sendMailQueue('Discovery Call Completed',$textClient , $discoveryCall[0]->client->name, $discoveryCall[0]->client->email);
            \App\Repositories\EmailManager::sendMailQueue('Discovery Call Completed', $textAdvisor, $discoveryCall[0]->advisor->name, $discoveryCall[0]->advisor->email);

            // NOTIFICATION TRIGGER after client confirmation of advisor selection
            $fcmController = new FcmController(env('FCM_SERVER_KEY'));
            $fcmController->pushNotification('Discovery Call Completed', $textAdvisor, 'DiscoveryCall', $discoveryCall[0]->advisor_id);
            $fcmController->pushNotification('Discovery Call Completed', $textClient, 'DiscoveryCall', $discoveryCall[0]->client_id);

        }
        }

    }
}
