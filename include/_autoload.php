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
    require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/trait.Funkcie.php';
    
    // funkcia na automaticke registrovanie tried
    // prehľadá adresáre uvedené v poli
    spl_autoload_register ( function ($class) {
        $pole = explode("\\", $class);  // rozdelí namespace do prvkov
        $index = count($pole) - 1;
        $value = $pole[$index];
        $pole[$index] = "class." . $value . ".php"; //premenuje posledný prvok poľa na názov súboru triedy
        include_once $_SERVER['DOCUMENT_ROOT'] . "/classes/" . implode("/", $pole);
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

    // Set Time Zone
    date_default_timezone_set('Europe/Bratislava');

//! Nastavenie hlavičiek
    // kontrola poslednej zmeny dát v aplikácii
    // poľle hlavičku poslednej zmeny
    $row = $db->query("SELECT UNIX_TIMESTAMP(`PoslednaAktualizacia`) AS Cas
                        FROM `52_sys_cache_cron_and_clean`
                        WHERE `NazovCACHE` = 'LAST CHANGE APP'")->fetchArray();
    $last_modified = $row['Cas'];
    if ($_SERVER["HTTP_IF_MODIFIED_SINCE"] && $last_modified <= strtotime($_SERVER["HTTP_IF_MODIFIED_SINCE"])) {
        header("HTTP/1.1 304 Not Modified");
        exit;
    }
    header("Last-Modified: " . gmdate("D, d M Y H:i:s", $last_modified) . " GMT");

    // nastavenie znakovej sady
    header('Content-Type: text/html; charset=utf-8');
    // jazyk stránky
    header('Content-Language: sk-SK');
    
    // nastavenie Content-Security-Policy
    $nonce = base64_encode(RandomToken(16));
    header("Content-Security-Policy: default-src 'self' 'unsafe-inline'; script-src 'nonce-" . $nonce ."' 'unsafe-eval'; object-src 'none'; img-src 'self'; child-src 'self' *.mapy.cz;");
    header("X-XSS-Protection: 1; mode=block");
    
    // prepísanie hlavičky s informáciami o serveri informáciami o autorovi
    header("X-Powered-By: \"Aplikaciu vytvoril Ondrej Vrto\"");
    
    // zabránenie otvácania súborov priamo v prehliadači
    header("X-Download-Options: noopen");
    
    // lavička ochraňuje uživatele od zneužití pomocí tzv. „clickjackingu“, kdy útočník na podvodné stránce 
    // vloží cizí stránku a nad ní umístí průhlednou vrstvu s vlastními událostmi a odkazy o kterých návštěvník netuší.
    header("X-Frame-Options: SAMEORIGIN");
    // Pokud prohlížeč ignoruje hlášený typ MIME, webmaster či administrátor webu nemá plnou kontrolu nad tím, 
    // jak má být obsah zpracováván. Když webový server nebo aplikace hlásí nesprávný typ MIME pro obsah, 
    // webový prohlížeč nemůže zjistit, jak se má soubor zpracovat a může se rozhodnout jiným způsobem než bylo zamýšleno.
    // Nastavením X-Content-Type-Options sdělujeme prohlížečům, že nemají odhadovat typ souboru/média a řídit se pouze 
    // předanými informacemi. Tím zvýšíme bezpečnost uživatele před škodlivým obsahem, který předstírá, že je bezpečným typem dokumentu.
    header("X-Content-Type-Options: nosniff");
    
    //! nastavenie platnosti certifikátu na HTTPS na 1 rok
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
    header("Referrer-Policy: strict-origin-when-cross-origin");
    header("Feature-Policy: microphone 'none'; camera 'none'; geolocation 'none';");


//! / Nastavenie hlavičiek

    // nastavenie coockie pre session
    $maxlifetime = 8*60*60; // 8 hodín v sekundách
    $secure = false; // if you only want to receive the cookie over HTTPS
    $httponly = true; // prevent JavaScript access to session cookie
    $samesite = 'strict';
    session_set_cookie_params($maxlifetime, '/; samesite='.$samesite, $_SERVER['HTTP_HOST'], $secure, $httponly);

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