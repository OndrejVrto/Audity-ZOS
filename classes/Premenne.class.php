<?php

// Inicializačné konštanty každej stránky: audity.zos.sk
class Premenne
{

    public $titulokStranky;
    public $popisStranky;
    public $bublinkoveMenu;
    public $nadpisPrvejSekcie;

    function __construct($link)
    {
        // Naplnenie konštánt pre konkrétnu stránku z poľa:  $konstantyStranok
        // ak na stránke nieje premenná ktorá označuje o akú stránku ide
        // alebo v poli $konstantyStranok chýba hodnota prislúchajúca stránke, nastavia sa štandardné hodnoty

        // Meta značky stránky
        if (array_key_exists($link, $this->konstantyStranok["Titulok Stránky"])) {
            $this->titulokStranky = $this->konstantyStranok["Titulok Stránky"][$link];
        } else {
            $this->titulokStranky = "Audity ŽOS Zvolen";
        }

        if (array_key_exists($link, $this->konstantyStranok["Popis Stránky"])) {
            $this->popisStranky = $this->konstantyStranok["Popis Stránky"][$link];
        } else {
            $this->popisStranky = "Audity ŽOS Zvolen - stránka bez špeciálneho popisu";
        }

        // určuje či sa na stránke zobrazí bublinkové menu a následne ho naplní
        if (array_key_exists($link, $this->konstantyStranok["Nadpis"])) {
            $this->nadpisPrvejSekcie = $this->konstantyStranok["Nadpis"][$link];
        } else {
            $this->nadpisPrvejSekcie = "";
        }
    }

    // Konštanty stránok individuálne
    public $konstantyStranok = array(

        // Meta značka stránky - TITLE -> Zobrazuje sa ako názov okna.
        "Titulok Stránky" => array(
            "/errorpages/404"                       =>    "Chybová stránka 404 | Audity ŽOS Zvolen",
            "/errorpages/401"                       =>    "Chybová stránka 401 | Audity ŽOS Zvolen",
            "/"                                     =>    "Home | Audity ŽOS Zvolen",
            "/login"                                =>    "Login | Audity ŽOS Zvolen",
            "/signup"                               =>    "Signup | Audity ŽOS Zvolen",
            "/user-detail"                          =>    "User detail | Audity ŽOS Zvolen",
            "/vlastnosti/oblasti-auditov/zoznam"    =>    "Zoznam oblastí auditovania | Audity ŽOS Zvolen",
            "/vlastnosti/typ-auditu/zoznam"         =>    "Typ auditu | Audity ŽOS Zvolen",
            "/vlastnosti/typy-externych-zisteni/zoznam" =>    "Zoznam externých zistení | Audity ŽOS Zvolen",
            "/sprava/zoznam-pracovnikov-zos"        =>    "Zoznam pracovníkov ŽOS Zvolen | Audity ŽOS Zvolen",
            
        ),    // "Titulok Stránky"

        // Meta značka stránky - Description -> popisuje stránku
        "Popis Stránky" => array(
            "/errorpages/404"                       =>    "Audity ŽOS Zvolen - chyba 404 - odkaz na stránku neexistuje",
            "/errorpages/401"                       =>    "Audity ŽOS Zvolen - Chyba 401 - Neautorizovaný vstup",            
            "/"                                     =>    "Audity ŽOS Zvolen - hlavná stránka",
            "/login"                                =>    "Audity ŽOS Zvolen - prihlasovanie uživateľa",
            "/user-detail"                          =>    "Audity ŽOS Zvolen - detialy o prihlásenom užívateľovi",
            "/signup"                               =>    "Audity ŽOS Zvolen - vytvorenie nového účtu uživateľa",
            "/vlastnosti/oblasti-auditov/zoznam"    =>    "Audity ŽOS Zvolen - zoznam oblastí auditovania",
            "/vlastnosti/typ-auditu/zoznam"         =>    "Audity ŽOS Zvolen - zoznam typov auditov a odkaz na referrenčný dokument",
            "/vlastnosti/typy-externych-zisteni/zoznam" =>    "Audity ŽOS Zvolen - Zoznam názvov externých zistení",
            "/sprava/zoznam-pracovnikov-zos"        =>    "Audity ŽOS Zvolen - aktuálny zoznam zamestnancov z dochádzkového systému",
        ),    // "Popis Stránky"

        // Nadpisy prvej kapitoly.
        "Nadpis" => array(
            "/errorpages/404"                       =>    "Chybová stránka 404",
            "/errorpages/401"                       =>    "Chybová stránka 401",
            "/"                                     =>    "Predľad všetkých auditov",
            "/login"                                =>    false,
            "/signup"                               =>    false,
            "/user-detail"                          =>    "Detaily o užívateľovi",
            "/vlastnosti/oblasti-auditov/zoznam"    =>    "Zoznam oblastí auditovania",
            "/vlastnosti/typ-auditu/zoznam"         =>    "Typ auditu",
            "/vlastnosti/typy-externych-zisteni/zoznam" =>    "Zoznam názvov externých zistení",
            "/sprava/zoznam-pracovnikov-zos"        =>    "Aktuálny zoznam zamestnancov ŽOS Zvolen",
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
                    "Doplnok" => "badge badge-success",
                    "PopisDoplnku" => "Hotovo",
                    "Ikona" => "far fa-check-circle text-success",
                    "SUBMENU" => false,
                ),
                array(
                    "Link" => "/vlastnosti/typ-auditu/zoznam",
                    "Nazov" => "Typ auditu",
                    "Doplnok" => "badge badge-success",
                    "PopisDoplnku" => "Hotovo",
                    "Ikona" => "far fa-check-circle text-success",
                    "SUBMENU" => false,
                ),
                array(
                    "Link" => "/vlastnosti/typy-externych-zisteni/zoznam",
                    "Nazov" => "Typy externych zistení",
                    "Doplnok" => "badge badge-warning",
                    "PopisDoplnku" => "TODO",
                    "Ikona" => "fas fa-exclamation-circle text-warning",
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
            "Link" => false,
            "Nazov" => "Správa",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-user-cog",
            "SUBMENU" => array(
                array(
                    "Link" => "#",
                    "Nazov" => "Zoznam uživateľov",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
                array(
                    "Link" => false,
                    "Nazov" => "Zoznam správcov",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
                array(
                    "Link" => "/sprava/zoznam-pracovnikov-zos",
                    "Nazov" => "Zamestnanci ŽOS",
                    "Doplnok" => "badge badge-success",
                    "PopisDoplnku" => "Hotovo",
                    "Ikona" => "far fa-check-circle text-success",
                    "SUBMENU" => false,
                ),
            ),
        ),
    );
}
