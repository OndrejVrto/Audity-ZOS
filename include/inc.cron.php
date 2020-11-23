<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    // prihlásenie do databázy pod účtom CRON, ktorý má prístup len k dvom tabuľkám s obmedzenými právami
    $dbCRON = new db($dbhost, $dbuserCRON, $dbpassCRON, $dbname);

    // funkcia AktualizujUsersMAX načíta dáta z databázy max4 cez prihlásenie ODBC s názvom MAXMAST nastavené na počítačoch ŽOS
    // následne tieto dáta uloží do lokálnej tabuľky č.51, porovná s pracovnými dátami v tabuľke č.50 a aktualizuje ich
    function AktualizujMAX (){

        global $dbCRON;

        $row = $dbCRON->query("SELECT `PoslednaAktualizacia` FROM `52_sys_cache_cron_and_clean` WHERE `NazovCACHE` = 'UPDATE maxdata.uoscis';")->fetchArray();
        
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
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            // v prípade že sa nepripojí do databázy MAX ukončí skritp a teda nepríde k zmazaniu údajov
            exit;
            //echo "Connection failed: " . trim(iconv('Windows-1250', 'UTF-8', $e->getMessage()));
        }

        $stmt = $pdo->prepare("SELECT * FROM maxmast.uoscis");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // opraví formátovanie dát načítaných z MAXu
        array_convert_MAX($data);

        // vytvrí SQL dotaz na vloženie nových dát do tabuľky v lokálnej databáze
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

            $sql .= PHP_EOL . "('$osCislo', '$meno', '$priezvisko', '$titul', '$strediskoCislo', '$stredisko', '$firma', '$nastup', '$vystup'),";

        }

        // odstráni poslednú čiarku a vloží bodkočiarku(koniec dotazu)
        $sql = substr($sql, 0, -1) . ';';

        // TODO: Prerobiť nasledovné 4 príkazy cez Transakciu mimo bd.class.php + vypnúť výpis chýb
        // vyčistí tabuľku
        $dbCRON->query("DELETE FROM `51_sys_users_maxmast_uoscis`;");
        // nastaví počítadlo od 1
        $dbCRON->query("ALTER TABLE `51_sys_users_maxmast_uoscis` AUTO_INCREMENT=1;");
        // vloží dáta
        $dbCRON->query($sql);
        // zapíše do logu poslednú aktualizáciu
        $dbCRON->query("UPDATE `52_sys_cache_cron_and_clean` 
                    SET `PoslednaAktualizacia` = NOW()
                    WHERE `NazovCACHE` = 'UPDATE maxdata.uoscis';");
    }

    function AktualizujUSERS (){
        
        global $db;

        $db->query("SELECT `ucislo`, `utitul`, `umeno`, `upriezv`, `ustred`, `nazstred`, `ondate`, `offdate`
                    FROM `51_sys_users_maxmast_uoscis`
                    WHERE `offdate` > NOW() AND `firma` LIKE '%ŽOS%' AND `ucislo` REGEXP '^[0-9]{4,5}$' AND `umeno` IS NOT NULL 
                    ORDER BY ondate ASC;");

        echo $db->numRows();


    }
    echo "Zaciatok: <br>";
    AktualizujUSERS();

        // TODO: Aktualizácia dát v tabuľke `50_sys_users`
        // ! DOPLNENIE nových zamestnancov do zoznamu
        // ^ vyber len tie záznamy s tabuľky 51_sys_users_maxmast_uoscis ktoré : 
        // sú to zamestnanci ŽOS Zvolen a.s.  //// alebo CellQos, a.s.
        // niesú to živnostníci, ani dohodári
        // majú dátum prepustenia neskôr ako je dnes
        // ^ a porovnaj ich s tabuľkou
        // ? ak je záznam v tabuľke a zároveň medzi dátami je rozdiel -> UPDATE
        // ? ak NIEje záznam v tabuľke -> INSERT + vygeruj im prvotné heslo do premennej Pasword-OLD

        // ! zmena stavu po prepustení so ŽOS v stĺpci NEPOUZIVAT na 1
        // * vyber záznamy kde je osobné číslo rovnaké v obidvoch tabuľkách ale je rozdiel v hodnote offdate
        // ? UPDATE



/*  SELECT `ucislo`, `utitul`, `umeno`, `upriezv`, `ustred`, `nazstred`, `ondate`, `offdate`
    FROM `51_sys_users_maxmast_uoscis`
    WHERE `offdate` > NOW() AND `firma` LIKE '%ŽOS%' AND `ucislo` REGEXP '^[0-9]{4,5}$' AND `umeno` IS NOT NULL 
    ORDER BY ondate ASC; */

/*  UPDATE 50_sys_users SET Password_OLD=CONCAT(
    ELT(1+FLOOR(RAND()*64), 
    'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
    'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
    '0','1','2','3','4','5','6','7','8','9'),
    ELT(1+FLOOR(RAND()*64), 
    'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
    'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
    '0','1','2','3','4','5','6','7','8','9'),
    ELT(1+FLOOR(RAND()*64), 
    'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
    'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
    '0','1','2','3','4','5','6','7','8','9'),
    ELT(1+FLOOR(RAND()*64),  
    'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
    'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
    '0','1','2','3','4','5','6','7','8','9'),
    ELT(1+FLOOR(RAND()*64), 
    'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
    'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
    '0','1','2','3','4','5','6','7','8','9')
    )
    WHERE ID50=4; */

/*  INSERT INTO action_2_members (campaign_id, mobile, vote, vote_date)  
    SELECT campaign_id, from_number, received_msg, date_received
    FROM `received_txts`
    WHERE `campaign_id` = '8' */


/*  UPDATE ips INNER JOIN country
    ON ips.iso = country.iso
    SET ips.countryid = country.countryid */