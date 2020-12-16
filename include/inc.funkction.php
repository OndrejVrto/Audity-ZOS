<?php

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
