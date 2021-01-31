<?php
    // pri hodnote TRUE zapne časti ktorá slúžia ako pomôcka pri vývoji tejto stránky + zobrazovanie chýb
    //defined("VYVOJ") or define("VYVOJ", TRUE);
    defined("VYVOJ") or define("VYVOJ", FALSE);

    if (VYVOJ) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL & ~E_NOTICE);
    } else {
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);
        error_reporting(0);
    }

    // trait roznych funkcií používaných v triedach
    require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Funkcie.trait.php';
    
    // funkcia na automaticke registrovanie tried
    // prehľadá adresáre uvedené v poli
    spl_autoload_register ( function ($class) {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/classes/" . str_replace("\\", "/", $class).".class.php";
    });    

    // globálne premenné
    $pathInclude = $_SERVER['DOCUMENT_ROOT'] . "/include/";

    // zoznam vložených súborov do každej stránky
    // Vytvorenie triedy "db" na pripojenie do databázy
    require_once $pathInclude . 'inc.dBconnect.php';
    // Globálne funkcie
    require_once $pathInclude . 'inc.funkction.php';
    require_once $pathInclude . 'inc.Paginate.php';
    require_once $pathInclude . 'inc.cron.php';


    // derfinície Konštant
    defined("TAB1") or define("TAB1", "\t");
    defined("TAB2") or define("TAB2", "\t\t");
    defined("TAB3") or define("TAB3", "\t\t\t");
    defined("TAB4") or define("TAB4", "\t\t\t\t");
    defined("TAB5") or define("TAB5", "\t\t\t\t\t");
    defined("TAB6") or define("TAB6", "\t\t\t\t\t\t");
    defined("TAB7") or define("TAB7", "\t\t\t\t\t\t\t");
    defined("TAB8") or define("TAB8", "\t\t\t\t\t\t\t\t");
    defined("TAB9") or define("TAB9", "\t\t\t\t\t\t\t\t\t");
    defined("TAB10") or define("TAB10", "\t\t\t\t\t\t\t\t\t\t");

    defined("TRUE") or define("TRUE", 1);
    defined("FALSE") or define("FALSE", 0);

    // Set Time Zone
    date_default_timezone_set('Europe/Bratislava');
    
    // nastavenie znakovej sady
    header('Content-Type: text/html; charset=utf-8');
    
    // nastavenie Content-Security-Policy
    $nonce = base64_encode(RandomToken(16));
    header("Content-Security-Policy: default-src 'self'; script-src 'strict-dynamic' 'nonce-" . $nonce ."'; object-src 'none';");

    // zapnutie session
    session_start();

    // automatické odhlásenie po 30 minútach bez aktivity na stránke. Pri vývoji 24 hodín.
    defined("TIME_OUT") or define("TIME_OUT", 30);
    if (VYVOJ) { $logoutTime = 24*60*60; } else { $logoutTime = TIME_OUT*60; }

    if ( ( isset($_SESSION['LAST_ACTIVITY']) AND ((time() - $_SESSION['LAST_ACTIVITY']) > $logoutTime) ) ) {
        $user = $_SESSION['LoginUser'];
        $meno = $_SESSION['userNameShort'];
        session_unset();
        $_SESSION['LoginUser'] = $user;
        $_SESSION['userNameShort'] = $meno;
        header("Location: /user/lock");
        exit();
    }

    // resetnutie času pre automaticke odhlásenie
    if ( isset($_SESSION['Login']) AND $_SESSION['Login'] == 'true' ) {
        $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
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