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

    // funkcia pre debugging
    // vypíše do konzoly prehliadača obsah premennej
    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
    }