<?php

namespace App\Api\V1\Transformers;

use App\Appointment;
use App\Package;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use League\Fractal\TransformerAbstract;

class PackagesTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include.
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * List of resources to automatically include.
     *
     * @var array
     */
    protected $defaultIncludes = [];

    /**
     * @property string name
     * @property string email
     */
    public function transform(Package $package)
    {
        $monthNr = Carbon::now()->month;

        $appointmentType = 0;
        $appointmentLimit = 0;
        if($package->voice_call_limit > 0) {
            $appointmentLimit = $package->voice_call_limit;
            $appointmentType = 'Audio';
        }elseif($package->video_call_limit > 0){
            $appointmentLimit = $package->video_call_limit;
            $appointmentType = 'Video';
        }
        $user = Auth::user();
        $currentMonthAppointments = Appointment::where('type',  $appointmentType)->whereMonth('datetime',  $monthNr)->where('user_id',$user->id)->count();

        return [
            'id' => $package->id,
            'title' => $package->title,
            'price' => $package->price,
            'package_type' => $package->package_type,
            'text_limit' => $package->text_limit,
            'voice_call_limit' => $package->voice_call_limit,
            'video_call_limit' => $package->video_call_limit,
            'voice_call_duration' => $package->voice_call_duration,
            'video_call_duration' => $package->video_call_duration,
            'totalAppointments' => $appointmentLimit,
            'availableAppointments' => $currentMonthAppointments,
        ];
    }

}
