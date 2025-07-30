<?php


namespace App\Api\V1\Helpers;


use DateTime;
use DateTimeZone;
use http\Env\Request;

class Utility
{

    public function convertTimeZone($timestamp, $fromTimeZone = 'UTC', $ToTimeZone = 'UTC')
    {

        $date = new DateTime($timestamp, new DateTimeZone($fromTimeZone));
        $date->setTimezone(new DateTimeZone($ToTimeZone));
        return $date->format('m/d/Y H:i');

    }

    function prettyPricePercent($amount)
    {
        return displayPricePercent('%', prettyFormat((float)$amount));
    }
}