<?php

namespace App\Console\Commands;

use App\Api\V1\Helpers\FcmController;
use App\Appointment;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AppointmentReminder5Minute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:reminder5minute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Appointment reminder notifications 05 Minute before. On the day of appointment.';

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
        $from = Carbon::now();
        $to = Carbon::now()->addMinutes(5);

        $appointments = Appointment::whereBetween('datetime', [$from, $to])->whereHas('therapist')->whereHas('user')->get();

        $fcmController = new FcmController(env('FCM_SERVER_KEY'));
        foreach ($appointments as $key => $appointment) {
            $descriptiontherapist = 'Dear '. $appointment->therapist->user->name .' You have an appointment booked with '. $appointment->user->name. ' on '. Carbon::parse($appointment->datetime)->format('m/d/Y') .' at ' . Carbon::parse($appointment->datetime)->format('h:i A');
            $fcmController->pushNotification('Appointment Reminder', $descriptiontherapist, 'appointment', $appointment->therapist->user_id);

            $descriptionUser = 'Dear '. $appointment->user->name .' You have an appointment booked with '. $appointment->therapist->user->name. ' on '. Carbon::parse($appointment->datetime)->format('m/d/Y') .' at ' . Carbon::parse($appointment->datetime)->format('h:i A');
            $fcmController->pushNotification('Appointment Reminder', $descriptionUser, 'appointment', $appointment->user->id);
        }
        $this->info('Appointment reminder notifications are sent. (5 min) ');
    }
}
