<?php
    // globálne premenné
    $pathInclude = $_SERVER['DOCUMENT_ROOT'] . "/include/";

    // funkcia na automaticke registrovanie tried
    // prehľadá adresáre uvedené v poli
    spl_autoload_register ( function ($class) {
        $sources = array(
            "/class/class.$class.php",
            "/class/page/class.$class.php", 
            "/class/validator/class.$class.php",
        );
        foreach ($sources as $source) {
            if (file_exists( $GLOBALS["pathInclude"].$source )) {
                require_once $GLOBALS["pathInclude"].$source;
            } 
        } 
    });

    // zoznam vložených súborov do každej stránky
    // $conn pripojenie do databazy
    require_once $pathInclude . 'inc.dBconnect.php';
    require_once $pathInclude . 'inc.dBfunction.php';
    // funkcie rozne 
    require_once $pathInclude . 'inc.funkction.php';
    // konstanty stránok
    require_once $pathInclude . '_variables.php';

    // derfinície Konštant
    defined("TAB1") or define("TAB1", "\t");
    defined("TAB2") or define("TAB2", "\t\t");
    defined("TAB3") or define("TAB3", "\t\t\t");
    defined("TAB4") or define("TAB4", "\t\t\t\t");
    defined("TAB5") or define("TAB5", "\t\t\t\t\t");
    defined("TAB6") or define("TAB6", "\t\t\t\t\t\t");
    defined("TAB7") or define("TAB7", "\t\t\t\t\t\t\t");