<?php

// Inicializačné konštanty každej stránky: audity.zos.sk
class Premenne
{

    public $titulokStranky = "Audity ŽOS Zvolen";
    public $popisStranky = "stránka bez špeciálneho popisu";
    public $nadpisPrvejSekcie = "";
    public $levelStranky = 1;
    public $konstantyStranokKomplet;

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    function __construct($link, $linkZoznam)
    {
        $this->konstantyStranokKomplet = $this->konstantyDoplnkoveStranky + $this->konstantyStrankyMenu;
        
        $this->getParametre($this->konstantyStranokKomplet, $link, $linkZoznam);
    }

    private function getParametre($pole ,$link1, $link2) {
        
        foreach ($pole as $key => $value) {
            if (is_array($value['SUBMENU'])) {
                // rekurzívna funkcia - volá sama seba pri každej ďalšej vrste Menu !!!
                $this->getParametre( $value['SUBMENU'], $link1, $link2 );
            } else {
                //! Level stránky sa môže vyhodnotiť aj na základe materskej stránky vloženej v linku č.2
                if ($value['Link'] == $link1 OR $value['Link'] == $link2) {
                    if (isset($value['LEVEL'])) {
                        $this->levelStranky = $value['LEVEL'];
                    }
                }
                if ($value['Link'] == $link1) {
                    if (isset($value['Title'])) {
                        $this->titulokStranky = $value['Title'];
                    }
                    if (isset($value['Description'])) {
                        $this->popisStranky = $value['Description'];
                    }
                    if (isset($value['Nadpis'])) {
                        $this->nadpisPrvejSekcie = $value['Nadpis'];
                    } 
                }
            }
        }
    }

    // Konštanty stránok individuálne
    public $konstantyDoplnkoveStranky = array(
        array(
            "LEVEL" => 0,
            "Link" => "/errorpages/404",
            "Title" => "Chybová stránka 404",
            "Description" => "chyba 404 - odkaz na stránku neexistuje",
            "Nadpis" => "Chybová stránka 404",
        ),
        array(
            "LEVEL" => 0,
            "Link" => "/errorpages/401",
            "Title" => "Chybová stránka 401",
            "Description" => "Chyba 401 - Neautorizovaný vstup",
            "Nadpis" => "Chybová stránka 401",
        ),
        array(
            "LEVEL" => 0,
            "Link" => "/errorpages/500",
            "Title" => "Chybová stránka 500",
            "Description" => "Chyba 500 - Vyskytla sa chyba v databáze",
            "Nadpis" => "Chybová stránka 500",
        ),
        array(
            "LEVEL" => 0,
            "Link" => "/vyhladavanie",
            "Title" => "Search",
            "Description" => "stránka na vyhľadávanie v databáze",
            "Nadpis" => "Vyhľadávanie v databáze",
        ),
        array(
            "LEVEL" => 0,
            "Link" => false,
            "NazovMENU" => "Uživateľ",
            "SUBMENU" => array(
                array(
                    "LEVEL" => 0,
                    "Link" => "/user/login",
                    "Title" => "Login",
                    "Description" => "prihlasovanie uživateľa",
                    "Nadpis" => "",
                    "NazovMENU" => "Login",                    
                ),
                array(
                    "LEVEL" => 0,
                    "Link" => "/user/signup",
                    "Title" => "Signup",
                    "Description" => "aktivácia účtu uživateľa",
                    "Nadpis" => "",
                    "NazovMENU" => "Signup",
                ),
                array(
                    "LEVEL" => 0,
                    "Link" => "/user/detail",
                    "Title" => "User detail",
                    "Description" => "detialy o prihlásenom užívateľovi",
                    "Nadpis" => "Detaily o prihlásenom užívateľovi",
                    "NazovMENU" => "Detaily",
                ),
                array(
                    "LEVEL" => 0,
                    "Link" => "/user/avatar",
                    "Title" => "Avatar",
                    "Description" => "vytvorenie avatara pre aktuálne prihláseného uživateľa",
                    "Nadpis" => "Vyber si svojho Avatara",
                    "NazovMENU" => "Avatar",
                ),
            ),
        ),
        array(
            "LEVEL" => 0,
            "Link" => "#",
            "Title" => "",
            "Description" => "",
            "Nadpis" => "",
        ),
    );




    // Konštanty stránok ktoré sa nachádzajú v hlavnom menu
    public $konstantyStrankyMenu = array(
        array("Hlavicka" => "Reporty"),
        array(
            "LEVEL" => 0,
            "Link" => "#",
            "Title" => "",
            "Description" => "",
            "Nadpis" => "",
            "NazovMENU" => "Prehľad platných certifikátov",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-certificate",
            "SUBMENU" => false,
        ),        
        array(
            "LEVEL" => 0,
            "Link" => false,
            "Title" => "",
            "Description" => "",
            "Nadpis" => "",
            "NazovMENU" => "Stav plnenia zistení",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-table",
            "SUBMENU" => false,
        ),
        array(
            "LEVEL" => 0,
            "Link" => false,
            "Title" => "",
            "Description" => "",
            "Nadpis" => "",
            "NazovMENU" => "Vývoj plnenia v čase",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-chart-area",
            "SUBMENU" => false,
        ),


        array("Hlavicka" => "Audity"),   
        array(
            "LEVEL" => 0,
            "Link" => "#",
            "Title" => "",
            "Description" => "",
            "Nadpis" => "",
            "NazovMENU" => "Zoznam certifikátov",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-copy",
            "SUBMENU" => false,
        ),
        array(
            "LEVEL" => 0,  //!  na hlavnej stránke musí byť 0, inak sa zacyklí načítavanie stránky
            "Link" => "/",
            "Title" => "Zoznam auditov",
            "Description" => "Predľad všetkých auditov",
            "Nadpis" => "Zoznam auditov",
            "NazovMENU" => "Zoznam auditov",
            "Doplnok" => "badge badge-info",
            "PopisDoplnku" => "návrh",
            "Ikona" => "fas fa-check-double",
            "SUBMENU" => false,
        ),
        array(
            "LEVEL" => 0,
            "Link" => "#",
            "Title" => "",
            "Description" => "",
            "Nadpis" => "",
            "NazovMENU" => "Zoznam zisteni",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-binoculars",
            "SUBMENU" => false,
        ),
        array(
            "LEVEL" => 0,
            "Link" => "#",
            "Title" => "",
            "Description" => "",
            "Nadpis" => "",
            "NazovMENU" => "Zoznam opatrení",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-praying-hands",
            "SUBMENU" => false,
        ),
        array(    
            "LEVEL" => 0,      
            "Link" => "#",
            "Title" => "",
            "Description" => "",
            "Nadpis" => "",
            "NazovMENU" => "Zoznam plnení",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-flag-checkered",
            "SUBMENU" => false,
        ),
        array(  
            "LEVEL" => 0,         
            "Link" => "#",
            "Title" => "",
            "Description" => "",
            "Nadpis" => "",
            "NazovMENU" => "Zoznam súborov",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-paperclip",
            "SUBMENU" => false,
        ),


        array("Hlavicka" => "Zoznamy"),
        array(
            "LEVEL" => 0,
            "Link" => false,
            "Title" => "",
            "Description" => "",
            "Nadpis" => "",
            "NazovMENU" => "Personál",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-house-user",
            "SUBMENU" => array(
                array(
                    "LEVEL" => 0,
                    "Link" => false,
                    "Title" => "",
                    "Description" => "",
                    "Nadpis" => "",
                    "NazovMENU" => "Zoznam firiem",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false
                ),
                array(
                    "LEVEL" => 0,
                    "Link" => false,
                    "Title" => "",
                    "Description" => "",
                    "Nadpis" => "",
                    "NazovMENU" => "Osoby zúčastnené pri audite",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false
                ),
                array(
                    "LEVEL" => 0,
                    "Link" => false,
                    "Title" => "",
                    "Description" => "",
                    "Nadpis" => "",
                    "NazovMENU" => "Útvary ŽOS",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
                array(
                    "LEVEL" => 0,
                    "Link" => false,
                    "Title" => "",
                    "Description" => "",
                    "Nadpis" => "",
                    "NazovMENU" => "Roly pri audite",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
                array(
                    "LEVEL" => 0,
                    "Link" => false,
                    "Title" => "",
                    "Description" => "",
                    "Nadpis" => "",
                    "NazovMENU" => "Roly pri opatreniach",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
            ),
        ),
        array(
            "LEVEL" => 0,
            "Link" => false,
            "Title" => "",
            "Description" => "",
            "Nadpis" => "",
            "NazovMENU" => "Vlastnosti",
            "Doplnok" => "badge badge-danger",
            "PopisDoplnku" => "kuk",
            "Ikona" => "fas fa-clipboard-list",
            "SUBMENU" => array(
                array(
                    "LEVEL" => 0,
                    "Link" => "/vlastnosti/oblasti-auditov/zoznam",
                    "Title" => "Zoznam oblastí auditovania",
                    "Description" => "zoznam oblastí auditovania",
                    "Nadpis" => "Zoznam oblastí auditovania",
                    "NazovMENU" => "Oblasti auditov",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-check-circle text-success",
                    "SUBMENU" => false,
                ),
                array(
                    "LEVEL" => 0,
                    "Link" => "/vlastnosti/typ-auditu/zoznam",
                    "Title" => "Typ auditu",
                    "Description" => "zoznam typov auditov a odkaz na referrenčný dokument",
                    "Nadpis" => "Typ auditu",
                    "NazovMENU" => "Typ auditu",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-check-circle text-success",
                    "SUBMENU" => false,
                ),
                array(
                    "LEVEL" => 20,
                    "Link" => "/vlastnosti/typy-internych-zisteni/zoznam",
                    "Title" => "",
                    "Description" => "",
                    "Nadpis" => "",
                    "NazovMENU" => "Typy internych zistení",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle text-danger",
                    "SUBMENU" => false,
                ),                
                array(
                    "LEVEL" => 0,
                    "Link" => "/vlastnosti/typy-externych-zisteni/zoznam",
                    "Title" => "Zoznam externých zistení",
                    "Description" => "Zoznam názvov externých zistení",
                    "Nadpis" => "Zoznam názvov externých zistení",
                    "NazovMENU" => "Typy externych zistení",
                    "Doplnok" => "badge badge-warning",
                    "PopisDoplnku" => "TODO",
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
                array(
                    "LEVEL" => 0,
                    "Link" => false,
                    "Title" => "",
                    "Description" => "",
                    "Nadpis" => "",
                    "NazovMENU" => "Prívlastky auditu",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
                array(
                    "LEVEL" => 0,
                    "Link" => false,
                    "Title" => "",
                    "Description" => "",
                    "Nadpis" => "",
                    "NazovMENU" => "Typy zistení",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
                array(
                    "LEVEL" => 0,
                    "Link" => false,
                    "Title" => "",
                    "Description" => "",
                    "Nadpis" => "",
                    "NazovMENU" => "Typy príloh",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),                
            ),
        ),
        array(
            "LEVEL" => 10,
            "Link" => false,
            "Title" => "",
            "Description" => "",
            "Nadpis" => "",
            "NazovMENU" => "Správa",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-user-cog",
            "SUBMENU" => array(
                array(
                    "LEVEL" => 10,
                    "Link" => "#",
                    "Title" => "",
                    "Description" => "",
                    "Nadpis" => "",
                    "NazovMENU" => "Zoznam uživateľov",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
                array(
                    "LEVEL" => 10,                    
                    "Link" => false,
                    "Title" => "",
                    "Description" => "",
                    "Nadpis" => "",
                    "NazovMENU" => "Zoznam správcov",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
                array(
                    "LEVEL" => 14,
                    "Link" => "/sprava/zoznam-pracovnikov-zos",
                    "Title" => "Zoznam pracovníkov ŽOS Zvolen",
                    "Description" => "aktuálny zoznam zamestnancov z dochádzkového systému",
                    "Nadpis" => "Aktuálny zoznam zamestnancov ŽOS Zvolen",
                    "NazovMENU" => "Aktívny zamestnanci ŽOS",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-check-circle text-warning",
                    "SUBMENU" => false,
                ),                
                array(
                    "LEVEL" => 16,
                    "Link" => "/sprava/zoznam-pracovnikov-zos-komplet",
                    "Title" => "Kompletný zoznam pracovníkov",
                    "Description" => "kompletný zoznam pracovníkov ŽOS ktorý boli evidovaný v dochádzkovom systéme Human",
                    "Nadpis" => "Kompletný zoznam zamestnancov evidovaných v HUMANe",
                    "NazovMENU" => "Všetci zamestnanci ŽOS",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-check-circle text-danger",
                    "SUBMENU" => false,
                ),
            ),
        ),
    );

}
