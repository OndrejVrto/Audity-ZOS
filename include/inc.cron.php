<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";
    
    // prihlásenie do databázy pod účtom CRON, ktorý má prístup len k jedinej tabuľke a má obmedzené práva na DELETE A INSERT
    $db = new db($dbhost, $dbuserCRON, $dbpassCRON, $dbname);
    $row = $db->query("SELECT `PoslednaAktualizacia` FROM `52_sys_cache_cron_and_clean` WHERE `NazovCACHE` = 'UPDATE maxdata.uoscis';")->fetchArray();
    
    // dátum poslednej úspešnej aktualizácie
    $poslednaAktualizacia = date_create(date("d-m-Y H:i:s", strtotime($row['PoslednaAktualizacia'])));
    // dátum terajší pre porovnanie
    $teraz = date_create(date("d-m-Y H:i:s",time()));
    // rozdiel dátumov v dňoch
    $rozdiel = date_diff($teraz, $poslednaAktualizacia)->format("%a");
    // ak bola posledná aktualizácia urobená pred menej ako 1 dňom, ukončí sa skript
    if ($rozdiel < 1 ) { 
        exit;
    }


    try {
        $pdo = new PDO('odbc:MAXDATA', '', '');
        // set the PDO error mode to exception
        //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
    } catch(PDOException $e) {
        // v prípade že sa nepripojí do databázy MAX ukončí skritp a teda nepríde k zmazaniu údajov
        exit;
        //echo "Connection failed: " . trim(iconv('Windows-1250', 'UTF-8', $e->getMessage()));
    }

    if ($pdo) {

        $stmt = $pdo->prepare("SELECT * FROM maxmast.uoscis");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // opraví formátovanie dát načítaných z MAXu
        array_convert_MAX($data);

        $sql = "INSERT INTO `51_sys_users_maxmast_uoscis` (`ucislo`, `umeno`, `upriezv`, `utitul`, `ustred`, `nazstred`, `firma`, `ondate`, `offdate`) VALUES";

        foreach ($data as $key => $value) {

            $osCislo        = $value['ucislo'];
            $meno           = $value['umeno'];
            $priezvisko     = $value['upriezv'];
            $titul          = $value['utitul'];
            $strediskoCislo = $value['ustred'];
            $stredisko      = $value['nazstred'];
            $firma          = $value['firma'];
            $nastup         = $value['ondate'];
            $vystup         = $value['offdate'];

            $sql .= PHP_EOL;
            $sql .= "('$osCislo', '$meno', '$priezvisko', '$titul', '$strediskoCislo', '$stredisko', '$firma', '$nastup', '$vystup'),";

        }

        $sql = substr($sql, 0, -1) . ';';
        //print_r($sql);

        // TODO: Prerobiť nasledovné 4 príkazy cez Transakciu
        // vyčistí tabuľku
        $db->query("DELETE FROM `51_sys_users_maxmast_uoscis`;");
        // nastaví počítadlo od 1
        $db->query("ALTER TABLE `51_sys_users_maxmast_uoscis` AUTO_INCREMENT=1;");
        // vloží dáta
        $db->query($sql);
        // zapíše do logu poslednú aktualizáciu
        $db->query("UPDATE `52_sys_cache_cron_and_clean` 
                    SET `PoslednaAktualizacia` = NOW()
                    WHERE `NazovCACHE` = 'UPDATE maxdata.uoscis';");

    }