<?php

class Vyhladavanie {

    private $Tabulka_Cislo;
    private $Tabulka_ID;
    private $Tabulka_Stlpec;

    private $Hodnota_orginal;
    private $Hodnota_cista;
    private $Titulok;

    private $user;

    private $Link = NULL;
    private $Nasobitel = 7;
    private $PriponaSuboru = NULL;

    private $hladanaHodnota;
    private $hladanaHodnotaPole;
    private $SQLprikaz;
    private $pocetVysledkov;
    private $VysledokHladania = false;
    private $stranka;
    private $zaznamov = 5;

    public function __set($name, $value)
    {
        if ($name == 'Hodnota') {
            $this->Hodnota_orginal = $this->gramatika($value)['sGramatikou'];
            $this->Hodnota_cista = $this->gramatika($value)['bezGramatiky'];
        } elseif ($name == 'url' AND $value == TRUE) {
            $this->Link = $this->Link . "?id=" . $this->Tabulka_ID;
        } else {
            $this->$name = $value;
        }
    }

    function setHladanaHodnota($vyraz)
    {
        if ($vyraz == '') {
            $this->hladanaHodnota = NULL;
        } else {
            global $db;
            $this->hladanaHodnota = $db->escapeString($this->gramatika($vyraz)['bezGramatiky']);
            $this->hladanaHodnotaPole = preg_split("/\s+/", $this->hladanaHodnota);
        }
    }

    function __construct($uzivatel)
    {
        $this->user = $uzivatel;
        $this->stranka = $_GET['p'];
    }

    public function Hladat()
    {
        if ($this->hladanaHodnota == NULL) {
            $this->VysledokHladania = false;
        } else {

            $sql_1 = 'SELECT *, 
                    LOCATE("?", Hodnota_CISTA) AS poloha,
                    MATCH(Hodnota_CISTA) AGAINST("?" IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION) as score
                    FROM 70_search_zaznamy
                    WHERE MATCH(Hodnota_CISTA) AGAINST("?" IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION)';
            // nahradí všetky otázniky v dotaze hľadanou hodnotou
            $sql_1 = sprintf(str_replace('?','%1$s', $sql_1 ), $this->hladanaHodnota);

            $sql_2 = 'SELECT *,
                    LOCATE("' . $this->hladanaHodnotaPole[0] . '", Hodnota_CISTA) AS poloha,
                    0.1 as score
                    FROM 70_search_zaznamy
                    WHERE ';
            foreach ($this->hladanaHodnotaPole as $value) {
                $sql_2 .= 'Hodnota_CISTA LIKE "%' . $value . '%" AND ';
            }
            // odstráni z dotazu poslené AND
            $sql_2 = rtrim($sql_2, ' AND ');

            $sql_3 = '(' . $sql_1 . ') UNION ALL (' . $sql_2 . ') 
                    ORDER BY score DESC';
            
            $sql = 'SELECT DISTINCT 
                        LEFT(X.`Hodnota_ORGINAL`, 150) AS TEXT, 
                        X.`Link`,
                        X.`Titulok`
                    FROM (' . $sql_3 . ') AS X';

            $this->SQLprikaz = $sql;

            global $db;
            $db->query($sql);
            $this->pocetVysledkov = $db->numRows();

            if ($this->pocetVysledkov == 0) {
                $this->VysledokHladania = false;
            } else {
                $this->VysledokHladania = $db->fetchAll();
            }
            $this->insertLOG();
        }
    }

    public function zobrazVysledok()
    {   

        if ( $this->hladanaHodnota == NULL) {
            return '<p class="mx-5 mb-3 mt-n3">Ak chcete vyhľadávať, musíte niečo napísať do poľa.</p>';
        }
        
        if ( $this->VysledokHladania == false) {
            return '<p class="mx-5 mb-3 mt-n3">Hľadaný výraz nepriniesol žiadne výsledky ...</p>';
        }

        $html = '<p class="mx-5 mb-3 mt-n3">Počet výsledkov: <span class="font-weight-bold">' . $this->pocetVysledkov . '</span></p>';

        for ($i = ($this->stranka - 1) * $this->zaznamov; 
            $i < min($this->stranka * $this->zaznamov, $this->pocetVysledkov); 
            $i++) { 
            
            $html .= '<div class="card py-2 px-3 mb-2">';
            $html .= '<a class="h5" href="'.$this->VysledokHladania[$i]['Link'].'" title="'.($i+1).'">'.$this->VysledokHladania[$i]['Titulok'].'</a>';
            $html .= '<a class="text-decoration-none text-success" href="'.$this->VysledokHladania[$i]['Link'].'">';
            $html .= $this->VysledokHladania[$i]['Link'].'</a>';
            $html .= '<span class="text-break text-truncate">'.$this->VysledokHladania[$i]['TEXT'].'</span>';
            $html .= '</div>';
            
        }

        $html .= pagination_vrto($this->stranka, ceil($this->pocetVysledkov / $this->zaznamov),"/vyhladavanie/", '/'.$this->hladanaHodnota);

        return $html;
    }




    public function zobrazVyvoj ()
    {
        $html = PHP_EOL . PHP_EOL . '<!--  pre vývoj -->' . PHP_EOL;
        $html .= '<hr class="pt-3"><div>' . PHP_EOL;
        $html .= $this->SQLinHTML() . PHP_EOL . PHP_EOL;
        $html .= $this->DATAinHTML() . PHP_EOL . PHP_EOL;
        $html .= '</div>' . PHP_EOL;
        return $html;
    }

    public function SQLinHTML()
    {
        return SqlFormatter::format($this->SQLprikaz);
    }

    public function DATAinHTML()
    {
        $html = '<pre class="bg-dark text-white">';
        $html .= print_r($this->hladanaHodnotaPole, true);
        $html .= "</pre>";
        $html .= '<pre class="bg-dark text-white">';
        $html .= print_r($this->VysledokHladania, true);

        return $html;
    }

    public function insertLOG()
    {
        global $db;
        // záznam do logu
        $db->query('INSERT INTO `71_search_vyhladavane_vyrazy` (`HladanyVyraz`, `PocetVysledkov`, `DatumCas`, `KtoVykonalZmenu`)
                    VALUES (?,?,NOW(),?)', $this->hladanaHodnota, $this->pocetVysledkov, $this->user);
    }

    public function insertSearch ()
    {
        global $db;
        $db->query('INSERT INTO `70_search_zaznamy` (`Tabulka_Cislo`, `Tabulka_ID`, `Tabulka_Stlpec`, 
                        `Hodnota_ORGINAL`, `Hodnota_CISTA`, `ID72_search_nasobitel`, `Titulok`,
                        `PriponaSuboru`, `Link`, `KtoVykonalZmenu`) 
                    VALUES (?,?,?,?,?,?,?,?,?,?)',
                    $this->Tabulka_Cislo, $this->Tabulka_ID, $this->Tabulka_Stlpec, 
                    $this->Hodnota_orginal,  $this->Hodnota_cista, $this->Nasobitel, $this->Titulok,
                    $this->PriponaSuboru, $this->Link, $this->user);
    }

    public function updateSearch()
    {
        global $db;
        $db->query("UPDATE `70_search_zaznamy` 
                    SET `Hodnota_ORGINAL` = ?, `Hodnota_CISTA` = ?, `ID72_search_nasobitel` = ?, `Titulok` = ?, 
                        `PriponaSuboru` = ?, `Link` = ?, `KtoVykonalZmenu` = ? 
                    WHERE `Tabulka_Cislo` = ? AND `Tabulka_ID` = ? AND `Tabulka_Stlpec` = ?",
                    $this->Hodnota_orginal, $this->Hodnota_cista, $this->Nasobitel, $this->Titulok,
                    $this->PriponaSuboru, $this->Link, $this->user,
                    $this->Tabulka_Cislo, $this->Tabulka_ID, $this->Tabulka_Stlpec);

        // v prípade predvyplnenej hodnoty v databáze sa táto vloží ako nová hodnota
        if ($db->affectedRows() == 0) {
            $this->insertSearch();
        }
    }

    public function deleteSearch()
    {
        global $db;
        $db->query("DELETE FROM `70_search_zaznamy` 
                    WHERE `Tabulka_Cislo` = ? AND `Tabulka_ID` = ? AND `Tabulka_Stlpec` = ?",
                    $this->Tabulka_Cislo, $this->Tabulka_ID, $this->Tabulka_Stlpec);
    }


    public function gramatika($vstup)
    {
	
        $prevodni_tabulka = Array(
            'ä'=>'a', 'Ä'=>'A', 'á'=>'a', 'Á'=>'A', 'a'=>'a', 'A'=>'A', 'a'=>'a', 'A'=>'A', 'â'=>'a', 'Â'=>'A',
            'č'=>'c', 'Č'=>'C', 'ć'=>'c', 'Ć'=>'C', 'ď'=>'d', 'Ď'=>'D', 'ě'=>'e', 'Ě'=>'E', 'é'=>'e', 'É'=>'E',
            'ë'=>'e', 'Ë'=>'E', 'e'=>'e', 'E'=>'E', 'e'=>'e', 'E'=>'E', 'í'=>'i', 'Í'=>'I', 'i'=>'i', 'I'=>'I',
            'i'=>'i', 'I'=>'I', 'î'=>'i', 'Î'=>'I', 'ľ'=>'l', 'Ľ'=>'L', 'ĺ'=>'l', 'Ĺ'=>'L', 'ń'=>'n', 'Ń'=>'N',
            'ň'=>'n', 'Ň'=>'N', 'n'=>'n', 'N'=>'N', 'ó'=>'o', 'Ó'=>'O', 'ö'=>'o', 'Ö'=>'O', 'ô'=>'o', 'Ô'=>'O',
            'o'=>'o', 'O'=>'O', 'o'=>'o', 'O'=>'O', 'ő'=>'o', 'Ő'=>'O', 'ř'=>'r', 'Ř'=>'R', 'ŕ'=>'r', 'Ŕ'=>'R',
            'š'=>'s', 'Š'=>'S', 'ś'=>'s', 'Ś'=>'S', 'ť'=>'t', 'Ť'=>'T', 'ú'=>'u', 'Ú'=>'U', 'ů'=>'u', 'Ů'=>'U',
            'ü'=>'u', 'Ü'=>'U', 'u'=>'u', 'U'=>'U', 'u'=>'u', 'U'=>'U', 'u'=>'u', 'U'=>'U', 'ý'=>'y', 'Ý'=>'Y',
            'ž'=>'z', 'Ž'=>'Z', 'ź'=>'z', 'Ź'=>'Z', ','=>'',  '.'=>'-', 'ˇ'=>'' , '´'=>''
            );
    
        if ( $vstup == '' or $vstup == NULL ){
            return array( 'sGramatikou'=>NULL , 'bezGramatiky'=>NULL );
        } else {
            // odstráni všetky tabulátory a konce riadkov
            $vystup_gramatika = str_replace(array("\n\r", "\n", "\r", "\t"), " ", $vstup);
            //od-eskejpuje uvodzovky
            $vystup_gramatika = str_replace(array("'", '"'), array("\\'", "\\\""), $vystup_gramatika);
            // dve a viac medzier nahradí jednou medzerou
            $vystup_gramatika = preg_replace("/( ){2,}/", " ", $vystup_gramatika);
            // nahradí pevnú medzeru za normálnu
            $vystup_gramatika = preg_replace("/\xC2\xA0/", " ", $vystup_gramatika);
            // odstráni diakritiku
            $vystup_bez_gramatiky = strtr($vystup_gramatika, $prevodni_tabulka);
            // odstráni prázdne miesto na konci a na začiatku
            $vystup_gramatika = trim($vystup_gramatika);
            // všetky znaky malé
            $vystup_bez_gramatiky = strtolower($vystup_bez_gramatiky);
            //navratova hodnota v poli
            return array( 'sGramatikou'=>(string)$vystup_gramatika , 'bezGramatiky'=>(string)$vystup_bez_gramatiky );
        }
    }

}