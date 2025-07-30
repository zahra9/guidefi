<?php


namespace App\Repositories;


use App\Jobs\SendEmailJob;

class EmailManager
{
    public static function sendMailQueue($subject=null,$emailBody=null,$receiverName=null, $receiverEmail=null){
        dispatch(new SendEmailJob($subject,$emailBody,$receiverName, $receiverEmail));
    }


}