<?php

    // funkcia  upravLink  vymaže posledný podadresár z cesty
    // sample:  $uri = upravLink($_SERVER['REQUEST_URI']);
    // sample:  /test/prvy/druhy -> /test/prvy

    function upravLink($linkCely){
        $position = strripos($linkCely, "/", 0);  
        if ($position == true){
            return substr($linkCely, 0, $position + 1);
        }
        else{
            return $linkCely;
        }
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