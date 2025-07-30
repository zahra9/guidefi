<?php


namespace App\Api\V1\Helpers;


use DateTime;
use DateTimeZone;
use http\Env\Request;
use Illuminate\Support\Facades\Config;

class Encryption
{



    /**
     * @return string
     */
    function custom_encrypt($string) {
        $result = '';
        $key = 'Roav9jzlVZwAgoZloNsfU18C+ADiy/mnQX!==3';
        for($i=0, $k= strlen($string); $i<$k; $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)+ord($keychar));
            $result .= $char;
        }
        return base64_encode($result);
    }

    /**
     * @return string
     */
    function custom_decrypt($string) {
        $result = '';
        $key = 'Roav9jzlVZwAgoZloNsfU18C+ADiy/mnQX!==3';
        $string = base64_decode($string);
        for($i=0,$k=strlen($string); $i< $k ; $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)-ord($keychar));
            $result.=$char;
        }
        return $result;
    }

    /**
     * @return encrypted string
     * //            "aes-256-ecb",
    //            '26kozQaKwRuNJ24t26kozQaKwRuNJ24t',
    //            OPENSSL_RAW_DATA
     */
    function encrypt($string) {
        return base64_encode(openssl_encrypt(
            $string,
            base64_decode(env('ENCRYPTION_METHOD')),
            base64_decode(env('ENCRYPTION_KEY')),
            OPENSSL_RAW_DATA
        ));
    }
    /**
     * @return decrepted string
     */
    function decrypt($string) {
        $method = Config::get('constants.ENCRYPTION_METHOD');
        $key = Config::get('constants.ENCRYPTION_KEY');

        return openssl_decrypt(
            base64_decode($string),
            base64_decode($method),
            base64_decode($key),
            OPENSSL_RAW_DATA
        );
    }

}
