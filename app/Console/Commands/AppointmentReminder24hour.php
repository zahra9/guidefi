<?php

namespace App\Console\Commands;

use App\Api\V1\Helpers\FcmController;
use App\Api\V1\Helpers\Utility;
use App\Appointment;
use App\Therapist;
use App\TherapistClientAppointment;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentReminder24hour extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:reminder24hour';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Appointment reminder notifications 24 hr before. On the day of appointment.';

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
        $timeFrom = Carbon::now()->addDay();
        $timeTo = Carbon::now()->addDay()->addMinutes(14);

//        $appointments = DB::select( DB::raw("SELECT  *, appt.datetime,TIMESTAMPDIFF(MINUTE,NOW(),appt.datetime) diff, NOW() FROM appointments appt  WHERE TIMESTAMPDIFF(MINUTE,NOW(),appt.datetime) BETWEEN 1440 AND 1454") );
//        $appointments = DB::select( DB::raw("SELECT  *, appt.datetime,TIMESTAMPDIFF(MINUTE,NOW(),appt.datetime) diff, NOW() FROM appointments appt  WHERE TIMESTAMPDIFF(MINUTE,NOW(),appt.datetime) BETWEEN 1440 AND 1454") );


        $appointments = TherapistClientAppointment::
        whereDate('appointment_date', Carbon::now())
            ->whereBetween('start_time',[$timeFrom, $timeTo])
            ->whereHas('therapist')->whereHas('user')->get();
//        dd( DB::getQueryLog());
//        dd($appointments->toArray());
//        $appointments = Appointment::where('datetime', '[$from, $to]')->whereHas('therapist')->whereHas('user')->get();

        $fcmController = new FcmController(env('FCM_SERVER_KEY'));
        $utilityClass = new Utility();

        $this->info('*****************FCM_SERVER_KEY******************');
        foreach ($appointments as $key => $appointment) {
            $this->info('*****************FCM_SERVER_KEY******************');
            $this->info($appointment);

            $forTherapist = Carbon::parse($appointment->start_time);
            $forClient = Carbon::parse($appointment->start_time);
            if(!is_null($appointment->client_timezone) && $appointment->client_timezone !== config('app.timezone')){
                $time =  Carbon::parse($appointment->start_time);
                $from = config('app.timezone');
                $to = $appointment->client_timezone;
                $clientTimeZone = $utilityClass->convertTimeZone($time, $from, $to);
                $forClient = $clientTimeZone;
            }

            if(!is_null($appointment->therapist_timezone) && $appointment->therapist_timezone !== config('app.timezone')){
                $time = Carbon::parse($appointment->start_time);
                $from = config('app.timezone');
                $to = $appointment->therapist_timezone;
                $therapistTimeZone = $utilityClass->convertTimeZone($time, $from, $to);
                $forTherapist = $therapistTimeZone;
            }

            $descriptiontherapist = 'Dear '. $appointment->therapist->user->name .' You have an appointment booked with '. $appointment->user->name. ' on '. Carbon::parse($forTherapist)->format('m/d/Y') .' at ' . Carbon::parse($forTherapist)->format('h:i A');
            $fcmController->pushNotification('Appointment Reminder', $descriptiontherapist, 'appointment', $appointment->therapist->user_id);

            $descriptionUser = 'Dear '.  $appointment->user->name .' You have an appointment booked with '.  $appointment->therapist->user->name . ' on '. Carbon::parse($forClient)->format('m/d/Y') .' at ' . Carbon::parse($forClient)->format('h:i A');
            $fcmController->pushNotification('Appointment Reminder', $descriptionUser, 'appointment', $appointment->user->id);
        }
        $this->info('Appointment reminder notifications are sent. 24 hour');
        Log::info('Appointment reminder notifications are sent. 24 hour');

    }
}
