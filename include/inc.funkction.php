<?php

// vyčistí text pred vložením na stránku
function vycistiText($text){

    return (string)trim(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));

}


// rekurzívna funkcia na komnverziu celého poľa vrátených dát
// z kódovania Windows-1250 do UTF-8
function array_convert_MAX(&$items){
    foreach ($items as &$item) {
        if(is_array($item))
        array_convert_MAX($item);
        else
        $item = trim(iconv('Windows-1250', 'UTF-8', $item));
    }
}

// funkcia pre debugging
// vypíše do konzoly prehliadača obsah premennej
function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
}

/**
 * Add leading zeros to a number, if necessary
 *
 * @var int $value The number to add leading zeros
 * @var int $threshold Threshold for adding leading zeros (number of digits 
 *                     that will prevent the adding of additional zeros)
 * @return string
 * 
 * add_leading_zero(1);      // 01
 * add_leading_zero(5);      // 05
 * add_leading_zero(100);    // 100
 * add_leading_zero(1);      // 001
 * add_leading_zero(5, 3);   // 005
 * add_leading_zero(100, 3); // 100
 * add_leading_zero(1, 7);   // 0000001
 */
function add_leading_zero($value, $threshold = 2) {
    return sprintf('%0' . $threshold . 's', $value);
}

function RandomToken($length = 32){
    if(!isset($length) || intval($length) <= 8 ){
        $length = 32;
    }
    if (function_exists('random_bytes')) {
        return bin2hex(random_bytes($length));
    }
    if (function_exists('mcrypt_create_iv')) {
        return bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
    }
    if (function_exists('openssl_random_pseudo_bytes')) {
        return bin2hex(openssl_random_pseudo_bytes($length));
    }
}
