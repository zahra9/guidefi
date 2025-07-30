<?php

namespace App\Console\Commands;

use App\Api\V1\Helpers\FcmController;
use App\Models\User;
use App\Therapist;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SendNotificationIfTherapistMatchFound extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:match';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is the command to get matches against the client for those who has not picked therapist.';

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

        // get a list of users who has filled questionairs and not selected a therapist.

        $users = \App\User::where('is_questionare_completed', 1)->doesnthave('selectedTherapists')->doesnthave('therapist')->get();

        //connect to database
//        $connection = mysqli_connect("ayana-mariadb.cuprhov6etff.us-west-1.rds.amazonaws.com", "ayana_admin", "WelcomeAyana2020!@", "ayana_prod", "3306");
        $connection = mysqli_connect(env('DB_HOST', 'ayana-mariadb.cuprhov6etff.us-west-1.rds.amazonaws.com'), env('DB_USERNAME', 'ayana_admin'), env('DB_PASSWORD', 'WelcomeAyana2020!@'), env('DB_DATABASE','ayana_prod'), env('DB_PORT', '3306'));

        foreach ($users as $key => $user){
            $result = mysqli_qumysqli_connectery($connection, "CALL getTherapistList($user->id)");
            $ids = [];
            if($connection->affected_rows == 0) {
                $ids = [];
            } else {
                if(!is_bool($result)){
                    while ($row = mysqli_fetch_array($result)){
                        $ids[] = $row[0];
                    }
                }else{
                    continue;
                }
            }

            $therapists = Therapist::whereHas('user',function ($q) use($ids){
                $q->whereIn('user_id',$ids);
            })->get();

            $fcmController = new FcmController(env('FCM_SERVER_KEY'));

            if(count($therapists) > 0){
                $key = count($therapists) > 1 ? ' Therapists' : ' Therapist';
                $description = ' You have '.count($therapists). $key. ' available, you can choose one from list.';
                $fcmController->pushNotification('Match Found', $description, 'MatchFound', $user->id);
            }else{
                $description = ' You don\'t have any match with therapist. Retake questionnaire to find the match or wait we are finding right therapist for you';
                $fcmController->pushNotification('Match Not Found', $description, 'MatchNotFound', $user->id);
            }
        }
        $this->info('Therapist match found cron run');
    }
}
