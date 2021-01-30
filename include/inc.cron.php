<?php

// funkcia AktualizujUsersMAX načíta dáta z databázy max4 cez prihlásenie ODBC s názvom MAXMAST nastavené na počítačoch ŽOS
// následne tieto dáta uloží do lokálnej tabuľky č.51, porovná s pracovnými dátami v tabuľke č.50 a aktualizuje ich
function AktualizujMAX() {

    global $dbhost, $dbuserCRON, $dbpassCRON, $dbname;

    // dočasné prihlásenie do databázy pod účtom CRON, ktorý má prístup len k dvom tabuľkám s obmedzenými právami
    $dbCRON = new db($dbhost, $dbuserCRON, $dbpassCRON, $dbname);

    $row = $dbCRON->query("SELECT TIMESTAMPDIFF( HOUR, PoslednaAktualizacia, NOW() ) AS Rozdiel 
                            FROM `52_sys_cache_cron_and_clean` 
                            WHERE `NazovCACHE` = 'UPDATE maxdata.uoscis';")->fetchArray();

    // ak bola posledná aktualizácia urobená pred menej ako 12 hodinami, ukončí sa skript
    if ($row['Rozdiel'] < 12) {
        // uzavrie toto dočasné pripojenie do databazy
        $dbCRON->close();
        return;
    }

    try {
        $pdo = new PDO('odbc:MAXDATA', '', '');
        // set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // v prípade že sa nepripojí do databázy MAX ukončí skritp a teda nepríde k zmazaniu údajov
        // uzavrie toto dočasné pripojenie do databazy
        $dbCRON->close();
        return;
        echo "Connection failed: " . trim(iconv('Windows-1250', 'UTF-8', $e->getMessage()));
    }

    $stmt = $pdo->prepare("SELECT * FROM maxmast.uoscis");
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // opraví formátovanie dát načítaných z MAXu cez DSN v ktorom je nastavené prímanie dát vo formáte cp1250 (Windows-1250)
    // V prípade, ak by som sa vedel pripojiť priamo do databázy (ale bohužiaľ neviem heslo)
    // dalo by sa nastaviť prímanie dát priamo v UTF8 cez nastavenie "SET NAMES utf8"
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

    // uzavrie toto dočasné pripojenie do databazy
    $dbCRON->close();

    if (VYVOJ) {
        echo AktualizujUSERS();
    } else {
        AktualizujUSERS();
    }
}



// ! DOPLNENIE nových zamestnancov do zoznamu
// ^ vyber len tie záznamy s tabuľky 51_sys_users_maxmast_uoscis ktoré : 
// sú to zamestnanci ŽOS Zvolen a.s.  //// alebo CellQos, a.s.
// niesú to živnostníci, ani dohodári
// majú dátum prepustenia neskôr ako je dnes
// ^ a porovnaj ich s tabuľkou
// ? ak je záznam v tabuľke -> UPDATE
// ? ak NIEje záznam v tabuľke -> INSERT -> vygeruj im prvotné heslo do premennej Pasword-OLD

// ! zmena stavu po prepustení so ŽOS v stĺpci NEPOUZIVAT na 1
// * vyber záznamy kde je osobné číslo rovnaké v obidvoch tabuľkách ale je rozdiel v hodnote offdate

function AktualizujUSERS() {

    global $db;
    $html = '';

    $html .= '<u><strong>Štatistika</u></strong><br>' . PHP_EOL;
    // kompletný zoznam nájdených záznamov
    $db->query("SELECT `ucislo`, `utitul`, `umeno`, `upriezv`, `ustred`, `nazstred`, `ondate`, `offdate`
                    FROM `51_sys_users_maxmast_uoscis`
                    WHERE `offdate` > NOW() AND `firma` LIKE '%ŽOS%' AND `ucislo` REGEXP '^[0-9]{4,5}$' AND `umeno` IS NOT NULL 
                    ORDER BY ondate ASC;");
    $html .=  "SELECT: " . $db->numRows() . PHP_EOL . "<br>" . PHP_EOL;

    // aktualizácia existujúcich záznamov
    $db->query("UPDATE `50_sys_users` AS A INNER JOIN
                    (SELECT * FROM `51_sys_users_maxmast_uoscis`) AS B
                    ON A.`OsobneCislo` = B.`ucislo`
                    SET A.`Titul` = B.`utitul`, 
                        A.`Meno` = B.`umeno`,
                        A.`Priezvisko` = B.`upriezv`,
                        A.`Stredisko` = B.`ustred`,
                        A.`NazovStrediska` = B.`nazstred`,
                        A.`Zamestnany_OD` = B.`ondate`,
                        A.`Zamestnany_DO` = B.`offdate`
                    ;");
    $html .= "UPDATE: " . $db->affectedRows() . PHP_EOL . "<br>" . PHP_EOL;

    // aktualizácia existujúcich záznamov - prepustený zamestnanaci
    $db->query("UPDATE `50_sys_users` AS A INNER JOIN
                    (SELECT * FROM `51_sys_users_maxmast_uoscis` WHERE `offdate` < NOW() ) AS B
                    ON A.`OsobneCislo` = B.`ucislo`
                    SET A.`ID53_sys_levels` = 2
                    ;");
    $html .= "OFF: " . $db->affectedRows() . PHP_EOL . "<br>" . PHP_EOL;

    // aktualizácia existujúcich záznamov - znovu prijatý zamestnanaci
    $db->query("UPDATE `50_sys_users` AS A INNER JOIN
                    (SELECT * FROM `51_sys_users_maxmast_uoscis` WHERE `offdate` >= NOW() ) AS B
                    ON A.`OsobneCislo` = B.`ucislo`
                    SET A.`ID53_sys_levels` = 3
                    WHERE A.`ID53_sys_levels` = 2
                    ;");
    $html .= "ON: " . $db->affectedRows() . PHP_EOL . "<br>" . PHP_EOL;

    // vloženie nových záznamov
    $db->query("INSERT INTO `50_sys_users` (`ID53_sys_levels`, `OsobneCislo`, `Titul`, `Meno`, `Priezvisko`, `Stredisko`, `NazovStrediska`, `Zamestnany_OD`, `Zamestnany_DO` )
                    SELECT 3, `ucislo`, `utitul`, `umeno`, `upriezv`, `ustred`, `nazstred`, `ondate`, `offdate` 
                    FROM (
                        SELECT *
                        FROM `51_sys_users_maxmast_uoscis`
                        WHERE `offdate` > NOW() AND `firma` LIKE '%ŽOS%' AND `ucislo` REGEXP '^[0-9]{4,5}$' AND `umeno` <> '') AS A
                    LEFT JOIN `50_sys_users` AS B
                    ON A.`ucislo` = B.`OsobneCislo`
                    WHERE B.`OsobneCislo` IS NULL
                    ;");
    $html .= "INSERT: " . $db->affectedRows() . PHP_EOL . "<br>" . PHP_EOL;

    // pridanie náhodného hesla tým zamestnancom kde je prázdne pole Password_OLD
    // POZOR: treba viackrát zopakovať ak je veľa záznamov naraz
    // potrebné zapnúť  sql-mode="NO_ENGINE_SUBSTITUTION"  v  my.ini
    $db->query("UPDATE `50_sys_users` SET `Password_OLD` = CONCAT(
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
                    WHERE `Password_OLD` IS NULL;");
    $html .= "PASS: " . $db->affectedRows() . PHP_EOL . "<br>" . PHP_EOL;

    // zapíše do logu poslednú aktualizáciu
    $db->query("UPDATE `52_sys_cache_cron_and_clean` 
                    SET `PoslednaAktualizacia` = NOW()
                    WHERE `NazovCACHE` = 'UPDATE users';");
    $html .= "LOG: " . $db->affectedRows() . PHP_EOL . "<br>" . PHP_EOL;

    return $html;
}


function import_LotusTelefonnyZoznam(){

    $poleKonecne = [];

    //* lokálna uri LOTUS servera s klapkami ŽOS Zvolen - Na konci url nutné pridať číslo listu od 1 do 9
    for ($i = 1; $i < 10; $i++) {

        //* vytvorenie kópie HTML stránky
        try {
            $url = 'https://192.168.1.3/Zos/TelZoz.nsf/53e14671a6569f9ec12566a6001ad139?OpenView&Start=1&Count=1000&Expand=' . $i;
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_TIMEOUT, 2);  //čaká na stránku 2 sekundy a potom pokračuje
            //$res = curl_exec($ch);
            $curl_errno = curl_errno($ch);
            $curl_error = curl_error($ch);

            if ($curl_errno > 0) {
                //echo "cURL: $url\nError ($curl_errno): $curl_error\n";
                continue;
            }
        } catch (Exception $e) {
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
            curl_close($ch);
            return false;
        }

        //* pre vývoj mám statické kópie dvoch stránok v súbore
        if ($i > 2) continue;
        $res = file_get_contents("vstup/TelZoznamLOTUS-" . $i . ".html");

        try {
            
            //* rozparsovanie DOm dokumentu stránky
            $dom = new DomDocument();
            $dom->loadHTML($res);
            $xpath = new DomXpath($dom);
            $data = $xpath->query('//td[@nowrap and not(@colspan)]');

            //* vytvorí s objektu Nodelist pole
            $pole = [];
            foreach ($data as $rowNode) {
                $pole[] = trim(utf8_decode($rowNode->nodeValue));
            }

            //* rozdelí pole po 10 položkách
            $poleKonecne = array_chunk($pole, 10);

        } catch (Exception $e) {
            //echo 'Caught exception: ',  $e->getMessage(), "\n";
            return false;
        } finally {
            unset($dom);
            unset($xpath);
            $pole = null;
        }
    }
    
    //print_r($poleKonecne);
    return $poleKonecne;
}


// funkcia AktualizujKlapky načíta dáta z URL (server Lotus Notes ŽOS Zvolen - vis)
// následne tieto dáta uloží do lokálnej tabuľky v databáze
function AktualizujKlapky($rucnaAktualizacia = false) {

    global $dbhost, $dbuserCRON, $dbpassCRON, $dbname;

    // dočasné prihlásenie do databázy pod účtom CRON, ktorý má prístup len k dvom tabuľkám s obmedzenými právami
    $dbCRON = new db($dbhost, $dbuserCRON, $dbpassCRON, $dbname);

    // Ak je zapnutá ručná aktualizácia na TRUE tak sa aktualizuje vždy
    if (!$rucnaAktualizacia) {
        $row = $dbCRON->query("SELECT TIMESTAMPDIFF( HOUR, PoslednaAktualizacia, NOW() ) AS Rozdiel 
                        FROM `52_sys_cache_cron_and_clean` 
                        WHERE `NazovCACHE` = 'IMPORT Lotus Klapky';")->fetchArray();

        // ak bola posledná aktualizácia urobená pred menej ako 48 hodinami, ukončí sa skript
        if ($row['Rozdiel'] < 48) {
            // uzavrie toto dočasné pripojenie do databazy
            $dbCRON->close();
            return;
        }
    }

    $data = import_LotusTelefonnyZoznam();
    if (!$data) return false;

    // vytvrí SQL dotaz na vloženie nových dát do tabuľky v lokálnej databáze
    $sql = "INSERT INTO `54_sys_klapky` (`Klapka`, `Priezvisko`, `Meno`, `Titul`, `Prevadzka`, `Cislo_strediska`, `Mobil`, `Poznamka`, `Tarif`) VALUES";

    foreach ($data as $key => $value) {

        $klapka             = $dbCRON->escapeString($value[1]);
        $priezvisko         = $dbCRON->escapeString($value[2]);
        $meno               = $dbCRON->escapeString($value[3]);
        $titul              = $dbCRON->escapeString($value[4]);
        $prevadzka          = $dbCRON->escapeString($value[5]);
        $stredisko_cislo    = $dbCRON->escapeString($value[6]);
        $mobil              = $dbCRON->escapeString($value[7]);
        $poznamka           = $dbCRON->escapeString($value[8]);
        $tarif              = $dbCRON->escapeString($value[9]);

        $sql .= PHP_EOL . "('$klapka', '$priezvisko', '$meno', '$titul', '$prevadzka', '$stredisko_cislo', '$mobil', '$poznamka', '$tarif'),";
    }

    // odstráni poslednú čiarku a vloží bodkočiarku(koniec dotazu)
    $sql = substr($sql, 0, -1) . ';';

    // vyčistí tabuľku
    $dbCRON->query("DELETE FROM `54_sys_klapky`;");
    // nastaví počítadlo od 1
    $dbCRON->query("ALTER TABLE `54_sys_klapky` AUTO_INCREMENT=1;");
    // vloží dáta
    $dbCRON->query($sql);
    // zapíše do logu poslednú aktualizáciu
    $dbCRON->query("UPDATE `52_sys_cache_cron_and_clean` 
                        SET `PoslednaAktualizacia` = NOW()
                        WHERE `NazovCACHE` = 'IMPORT Lotus Klapky';");

    // uzavrie toto dočasné pripojenie do databazy
    $dbCRON->close();
}