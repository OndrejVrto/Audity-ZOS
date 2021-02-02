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

// https://stackoverflow.com/questions/6174691/sql-injection-detection-have-compiled-regexes-looking-for-test-injections
function testSqlInjestion($varvalue, $_comment_loop = false)
{
    $total = 0;
    $varvalue_orig = $varvalue;
    $quote_pattern = '\%27|\'|\%22|\"|\%60|`';
//      detect base64 encoding
    if(preg_match('/^[a-zA-Z0-9\/+]*={0,2}$/', $varvalue) > 0 && base64_decode($varvalue) !== false)
    {
        $varvalue = base64_decode($varvalue);
    }

//      detect and remove comments
    if(preg_match('!/\*.*?\*/!s', $varvalue) > 0)
    {
        if($_comment_loop === false)
        { 
            $total += test($varvalue_orig, true);
            $varvalue = preg_replace('!/\*.*?\*/!s', '', $varvalue);
        }
        else
        {
            $varvalue = preg_replace('!/\*.*?\*/!s', ' ', $varvalue);
        }
        $varvalue = preg_replace('/\n\s*\n/', "\n", $varvalue);
    }
    $varvalue = preg_replace('/((\-\-|\#)([^\\n]*))\\n/si', ' ', $varvalue);

//      detect and replace hex encoding
//      detect and replace decimal encodings
    if(preg_match_all('/&#x([0-9]{2});/', $varvalue, $matches) > 0 || preg_match_all('/&#([0-9]{2})/', $varvalue, $matches) > 0)
    {
//          replace numeric entities
        $varvalue = preg_replace('/&#x([0-9a-f]{2});?/ei', 'chr(hexdec("\\1"))', $varvalue);
        $varvalue = preg_replace('/&#([0-9]{2});?/e', 'chr("\\1")', $varvalue);
//          replace literal entities
        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        $trans_tbl = array_flip($trans_tbl);
        $varvalue = strtr($varvalue, $trans_tbl);
    }

    $and_pattern = '(\%41|a|\%61)(\%4e|n|%6e)(\%44|d|\%64)';
    $or_pattern = '(\%6F|o|\%4F)(\%72|r|\%52)';
    $equal_pattern = '(\%3D|=)';
    $regexes = array(
            '/(\-\-|\#|\/\*)\s*$/si',
            '/('.$quote_pattern.')?\s*'.$or_pattern.'\s*(\d+)\s*'.$equal_pattern.'\s*\\4\s*/si',
            '/('.$quote_pattern.')?\s*'.$or_pattern.'\s*('.$quote_pattern.')(\d+)\\4\s*'.$equal_pattern.'\s*\\5\s*/si',
            '/('.$quote_pattern.')?\s*'.$or_pattern.'\s*(\d+)\s*'.$equal_pattern.'\s*('.$quote_pattern.')\\4\\6?/si',
            '/('.$quote_pattern.')?\s*'.$or_pattern.'\s*('.$quote_pattern.')?(\d+)\\4?/si',
            '/('.$quote_pattern.')?\s*'.$or_pattern.'\s*('.$quote_pattern.')([^\\4]*)\\4\\5\s*'.$equal_pattern.'\s*('.$quote_pattern.')/si',
            '/((('.$quote_pattern.')\s*)|\s+)'.$or_pattern.'\s+([a-z_]+)/si',
            '/('.$quote_pattern.')?\s*'.$or_pattern.'\s+([a-z_]+)\s*'.$equal_pattern.'\s*(d+)/si',
            '/('.$quote_pattern.')?\s*'.$or_pattern.'\s+([a-z_]+)\s*'.$equal_pattern.'\s*('.$quote_pattern.')/si',
            '/('.$quote_pattern.')?\s*'.$or_pattern.'\s*('.$quote_pattern.')([^\\4]+)\\4\s*'.$equal_pattern.'\s*([a-z_]+)/si',
            '/('.$quote_pattern.')?\s*'.$or_pattern.'\s*('.$quote_pattern.')([^\\4]+)\\4\s*'.$equal_pattern.'\s*('.$quote_pattern.')/si',
            '/('.$quote_pattern.')?\s*\)\s*'.$or_pattern.'\s*\(\s*('.$quote_pattern.')([^\\4]+)\\4\s*'.$equal_pattern.'\s*('.$quote_pattern.')/si',
            '/('.$quote_pattern.'|\d)?(;|%20|\s)*(union|select|insert|update|delete|drop|alter|create|show|truncate|load_file|exec|concat|benchmark)((\s+)|\s*\()/ix',
            '/from(\s*)information_schema.tables/ix',
        );

    foreach ($regexes as $regex)
    {
        $total += preg_match($regex, $varvalue);
    }
    return $total;
}