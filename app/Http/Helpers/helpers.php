<?php

if (!function_exists('prettyPricePercent')) {
    function prettyPricePercent($amount){
        return displayPricePercent('%',prettyFormat( (float) $amount));
    }
    if (!function_exists('displayPricePercent')) {
        function displayPricePercent($sign='',$amount=''){
            $spacer=" ";
            return $amount.$spacer.$sign;
        }
    }
    if (!function_exists('prettyFormat')) {
        function prettyFormat($price){

            $decimal=2;
            $decimal_separators='yes';

            $thousand_separator=',';
            $decimal_separator='.';


            $thou_separator='';
            if (!empty($price)){
                if ($decimal==""){
                    $decimal=2;
                }
                if ( $decimal_separators=="yes"){
                    //$thou_separator=",";
                    $thou_separator=$thousand_separator;
                }
                //return number_format((float)$price,$decimal,".",$thou_separator);
                return number_format((float)$price,$decimal,$decimal_separator,$thou_separator);
            }
            if ($decimal==""){
                $decimal=2;
            }
            //return number_format(0,$decimal,".",$thou_separator);
            $thou_separator=$thousand_separator;

            return number_format(0,$decimal,$decimal_separator,$thou_separator);
        }
    }
    if (!function_exists('prettyPrice')) {
        function prettyPrice($amount){
            return displayPrice('$',prettyFormat( (float) $amount));
        }
    }
    if (!function_exists('displayPrice')) {
        function displayPrice($currency='',$amount=''){
            $spacer=" ";
            return $currency.$spacer.$amount;
        }
    }

    if (!function_exists('normalPrettyPrice')) {
        function normalPrettyPrice($price = '')
        {
            if (is_numeric($price)) {
                return number_format($price, 0, '.', '');
            }
            return false;
        }
    }



    if (!function_exists('ratingCalc')) {
        function ratingCalc($user )
        {
            $roundVal = 0;
            if(count($user->feedbackdealfinishesAdvisor)> 0) {
                $sumExperise = $user->feedbackdealfinishesAdvisor->sum('expertise_level');
                $sumAvailability = $user->feedbackdealfinishesAdvisor->sum('availability');
                $sumCommunication = $user->feedbackdealfinishesAdvisor->sum('communication');

                $total = $user->feedbackdealfinishesAdvisor->count() * 3;
                $maxRateRatio = 5;
                $sum_of_rating = $sumExperise + $sumAvailability + $sumCommunication;
                $sum_of_max_rating_of_user_count = $total * $maxRateRatio;
                $rating = ($sum_of_rating * $maxRateRatio) / $sum_of_max_rating_of_user_count;
                $roundVal = round($rating, 1);

                /*if (is_numeric($price)) {
                    return number_format($price, 0, '.', '');
                }*/
            }
            return $roundVal;
        }
    }
}
