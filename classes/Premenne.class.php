<?php

// Inicializačné konštanty každej stránky: audity.zos.sk
class Premenne
{

    public $titulokStranky;
    public $popisStranky;
    public $bublinkoveMenu;
    public $nadpisPrvejSekcie;
    public $MenuLevel = 0;

    function __construct($link, $linkZoznam)
    {
        // Naplnenie konštánt pre konkrétnu stránku z poľa:  $konstantyStranok
        // ak na stránke nieje premenná ktorá označuje o akú stránku ide
        // alebo v poli $konstantyStranok chýba hodnota prislúchajúca stránke, nastavia sa štandardné hodnoty

        // Meta značky stránky
        if (array_key_exists($link, $this->konstantyStranok["Titulok Stránky"])) {
            $this->titulokStranky = $this->konstantyStranok["Titulok Stránky"][$link] . " | Audity ŽOS Zvolen";
        } else {
            $this->titulokStranky = "Audity ŽOS Zvolen";
        }

        if (array_key_exists($link, $this->konstantyStranok["Popis Stránky"])) {
            $this->popisStranky = "Audity ŽOS Zvolen - " . $this->konstantyStranok["Popis Stránky"][$link];
        } else {
            $this->popisStranky = "stránka bez špeciálneho popisu";
        }

        // určuje či sa na stránke zobrazí bublinkové menu a následne ho naplní
        if (array_key_exists($link, $this->konstantyStranok["Nadpis"])) {
            $this->nadpisPrvejSekcie = $this->konstantyStranok["Nadpis"][$link];
        } else {
            $this->nadpisPrvejSekcie = "";
        }
        //* LEVEL = -1 ručne deaktivovaný uživateľ
        //* LEVEL = 0 neprihlásený uživateľ alebo bývalý zamestnanec
        //* LEVEL = 1 read
        //* LEVEL = 2 edit
        //* LEVEL = 3 admin
        $this->getLevelMenu($this->menuHlavne, $link, $linkZoznam);
    }

    private function getLevelMenu($pole ,$link1, $link2) {
        
        foreach ($pole as $key => $value) {
            if (is_array($value['SUBMENU'])) {
                // rekurzívna funkcia - volá sama seba pri každej ďalšej vrste Menu !!!
                $this->getLevelMenu( $value['SUBMENU'], $link1, $link2 );
            } else {
                if ($value['Link'] == $link1 OR $value['Link'] == $link2) {
                    if (isset($value['MinUserLEVEL'])) {
                        $this->MenuLevel = $value['MinUserLEVEL'];
                    }
                }
            }
        }
    }

    // Konštanty stránok individuálne
    public $konstantyStranok = array(

        // Meta značka stránky - TITLE -> Zobrazuje sa ako názov okna.
        "Titulok Stránky" => array(
            "/errorpages/404"                           =>    "Chybová stránka 404",
            "/errorpages/401"                           =>    "Chybová stránka 401",
            "/"                                         =>    "Home",
            "/vyhladavanie"                             =>    "Search",
            "/user/login"                               =>    "Login",
            "/user/signup"                              =>    "Signup",
            "/user/detail"                              =>    "User detail",
            "/user/avatar"                              =>    "Avatar",            
            "/vlastnosti/oblasti-auditov/zoznam"        =>    "Zoznam oblastí auditovania",
            "/vlastnosti/typ-auditu/zoznam"             =>    "Typ auditu",
            "/vlastnosti/typy-externych-zisteni/zoznam" =>    "Zoznam externých zistení",
            "/sprava/zoznam-pracovnikov-zos"            =>    "Zoznam pracovníkov ŽOS Zvolen",
            "/sprava/zoznam-pracovnikov-zos-komplet"    =>    "Kompletný zoznam pracovníkov",
            
        ),    // "Titulok Stránky"

        // Meta značka stránky - Description -> popisuje stránku
        "Popis Stránky" => array(
            "/errorpages/404"                           =>    "chyba 404 - odkaz na stránku neexistuje",
            "/errorpages/401"                           =>    "Chyba 401 - Neautorizovaný vstup",            
            "/"                                         =>    "hlavná stránka",
            "/vyhladavanie"                             =>    "stránka na vyhľadávanie v databáze",
            "/user/login"                               =>    "prihlasovanie uživateľa",
            "/user/signup"                              =>    "vytvorenie nového účtu uživateľa",
            "/user/detail"                              =>    "detialy o prihlásenom užívateľovi",
            "/user/avatar"                              =>    "vytvorenie avatara pre aktuálne prihláseného uživateľa",  
            "/vlastnosti/oblasti-auditov/zoznam"        =>    "zoznam oblastí auditovania",
            "/vlastnosti/typ-auditu/zoznam"             =>    "zoznam typov auditov a odkaz na referrenčný dokument",
            "/vlastnosti/typy-externych-zisteni/zoznam" =>    "Zoznam názvov externých zistení",
            "/sprava/zoznam-pracovnikov-zos"            =>    "aktuálny zoznam zamestnancov z dochádzkového systému",
            "/sprava/zoznam-pracovnikov-zos-komplet"    =>    "kompletný zoznam pracovníkov ŽOS ktorý boli evidovaný v dochádzkovom systéme Human",
        ),    // "Popis Stránky"

        // Nadpisy prvej kapitoly.
        "Nadpis" => array(
            "/errorpages/404"                           =>    "Chybová stránka 404",
            "/errorpages/401"                           =>    "Chybová stránka 401",
            "/"                                         =>    "Predľad všetkých auditov",
            "/vyhladavanie"                             =>    "Vyhľadávanie v databáze",
            "/user/login"                               =>    false,
            "/user/signup"                              =>    false,
            "/user/detail"                              =>    "Detaily o prihlásenom užívateľovi",
            "/user/avatar"                              =>    "Vyber si svojho Avatara",
            "/vlastnosti/oblasti-auditov/zoznam"        =>    "Zoznam oblastí auditovania",
            "/vlastnosti/typ-auditu/zoznam"             =>    "Typ auditu",
            "/vlastnosti/typy-externych-zisteni/zoznam" =>    "Zoznam názvov externých zistení",
            "/sprava/zoznam-pracovnikov-zos"            =>    "Aktuálny zoznam zamestnancov ŽOS Zvolen",
            "/sprava/zoznam-pracovnikov-zos-komplet"    =>    "Kompletný zoznam zamestnancov evidovaných v HUMANe",
        ),    // "Nadpis prvej kapitoly"
    );

    // MENU hlavné
    public $menuHlavne = array(
        array("Hlavicka" => "Reporty"),
        array(
            "Link" => "#",
            "Nazov" => "Prehľad platných certifikátov",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-certificate",
            "SUBMENU" => false,
        ),        
        array(
            "Link" => false,
            "Nazov" => "Stav plnenia zistení",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-table",
            "SUBMENU" => false,
        ),
        array(
            "Link" => false,
            "Nazov" => "Vývoj plnenia v čase",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-chart-area",
            "SUBMENU" => false,
        ),


        array("Hlavicka" => "Audity"),   
        array(
            "Link" => "#",
            "Nazov" => "Zoznam certifikátov",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-copy",
            "SUBMENU" => false,
        ),
        array(
            "MinUserLEVEL" => 0,  //!  Musí byť 0, inak sa zacyklí načítavanie stránky
            "Link" => "/",
            "Nazov" => "Zoznam auditov",
            "Doplnok" => "badge badge-info",
            "PopisDoplnku" => "návrh",
            "Ikona" => "fas fa-check-double",
            "SUBMENU" => false,
        ),
        array(
            "Link" => "#",
            "Nazov" => "Zoznam zisteni",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-binoculars",
            "SUBMENU" => false,
        ),
        array(
            "Link" => "#",
            "Nazov" => "Zoznam opatrení",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-praying-hands",
            "SUBMENU" => false,
        ),
        array(          
            "Link" => "#",
            "Nazov" => "Zoznam plnení",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-flag-checkered",
            "SUBMENU" => false,
        ),
        array(           
            "Link" => "#",
            "Nazov" => "Zoznam súborov",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-paperclip",
            "SUBMENU" => false,
        ),


        array("Hlavicka" => "Zoznamy"),
        array(
            "Link" => false,
            "Nazov" => "Personál",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-house-user",
            "SUBMENU" => array(
                array(
                    "Link" => false,
                    "Nazov" => "Zoznam firiem",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false
                ),
                array(
                    "Link" => false,
                    "Nazov" => "Osoby zúčastnené pri audite",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false
                ),
                array(
                    "Link" => false,
                    "Nazov" => "Útvary ŽOS",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
                array(
                    "Link" => false,
                    "Nazov" => "Roly pri audite",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
                array(
                    "Link" => false,
                    "Nazov" => "Roly pri opatreniach",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
            ),
        ),
        array(
            "Link" => false,
            "Nazov" => "Vlastnosti",
            "Doplnok" => "badge badge-danger",
            "PopisDoplnku" => "kuk",
            "Ikona" => "fas fa-clipboard-list",
            "SUBMENU" => array(
                array(
                    "Link" => "/vlastnosti/oblasti-auditov/zoznam",
                    "Nazov" => "Oblasti auditov",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-check-circle text-success",
                    "SUBMENU" => false,
                ),
                array(
                    "Link" => "/vlastnosti/typ-auditu/zoznam",
                    "Nazov" => "Typ auditu",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-check-circle text-success",
                    "SUBMENU" => false,
                ),
                array(
                    "MinUserLEVEL" => 10,
                    "Link" => "/vlastnosti/typy-internych-zisteni/zoznam",
                    "Nazov" => "Typy internych zistení",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle text-danger",
                    "SUBMENU" => false,
                ),                
                array(
                    "Link" => "/vlastnosti/typy-externych-zisteni/zoznam",
                    "Nazov" => "Typy externych zistení",
                    "Doplnok" => "badge badge-warning",
                    "PopisDoplnku" => "TODO",
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
                array(
                    "Link" => false,
                    "Nazov" => "Prívlastky auditu",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
                array(
                    "Link" => false,
                    "Nazov" => "Typy zistení",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
                array(
                    "Link" => false,
                    "Nazov" => "Typy príloh",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),                
            ),
        ),
        array(
            "MinUserLEVEL" => 1,
            "Link" => false,
            "Nazov" => "Správa",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-user-cog",
            "SUBMENU" => array(
                array(
                    "MinUserLEVEL" => 1,                    
                    "Link" => "#",
                    "Nazov" => "Zoznam uživateľov",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
                array(
                    "MinUserLEVEL" => 1,                    
                    "Link" => false,
                    "Nazov" => "Zoznam správcov",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
                array(
                    "MinUserLEVEL" => 3,
                    "Link" => "/sprava/zoznam-pracovnikov-zos",
                    "Nazov" => "Aktívny zamestnanci ŽOS",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-check-circle text-warning",
                    "SUBMENU" => false,
                ),                
                array(
                    "MinUserLEVEL" => 10,
                    "Link" => "/sprava/zoznam-pracovnikov-zos-komplet",
                    "Nazov" => "Všetci zamestnanci ŽOS",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-check-circle text-danger",
                    "SUBMENU" => false,
                ),
            ),
        ),
    );

}
