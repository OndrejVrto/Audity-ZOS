<?php
    // pri hodnote TRUE zapne časti ktorá slúžia ako pomôcka pri vývoji tejto stránky + zobrazovanie chýb
    defined("VYVOJ") or define("VYVOJ", TRUE);
    //defined("VYVOJ") or define("VYVOJ", FALSE);

    if (VYVOJ) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL & ~E_NOTICE);
    } else {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);
    }

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

    // Konštanty stránok
    require_once $pathInclude . '_variables.php';
    // Vytvorenie triedy "db" na pripojenie do databázy
    require_once $pathInclude . 'inc.dBconnect.php';
    // Globálne funkcie
    require_once $pathInclude . 'inc.funkction.php';

    // derfinície Konštant
    defined("TAB1") or define("TAB1", "\t");
    defined("TAB2") or define("TAB2", "\t\t");
    defined("TAB3") or define("TAB3", "\t\t\t");
    defined("TAB4") or define("TAB4", "\t\t\t\t");
    defined("TAB5") or define("TAB5", "\t\t\t\t\t");
    defined("TAB6") or define("TAB6", "\t\t\t\t\t\t");
    defined("TAB7") or define("TAB7", "\t\t\t\t\t\t\t");

    // Set Time Zone
    date_default_timezone_set('Europe/Bratislava');
    
    // zapnutie session
    session_start();

    // automaticke odhlásenie po 30 minútach bez obnovenia stránok (30min*60s = 1800 sekúnd)
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
        // last request was more than 30 minutes ago
        session_unset();     // unset $_SESSION variable for the run-time 
        session_destroy();   // destroy session data in storage
        $odhlasenie = true;
    }
    // resetnutie času pre automaticke odhlásenie po 30 minútach pre prihláseného uživateľa
    if (isset($_SESSION['userId'])) {
        $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
    }