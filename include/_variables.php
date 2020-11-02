<?php

// Inicializačné konštanty každej stránky: audity.zos.sk
class Premenne
{

    public $titulokStranky;
    public $popisStranky;
    public $bublinkoveMenu;
    public $nadpisPrvejSekcie;

    // Konštanty stránok individuálne
    Public $konstantyStranok = array(

        // Meta značka stránky - TITLE -> Zobrazuje sa ako názov okna.
        "Titulok Stránky" => array(
            "Chybová 404" 						=>	"Chybová stránka 404 | Audity ŽOS Zvolen",
            "Čistá" 							=>	"Prázdna stránka | Audity ŽOS Zvolen",
            "index" 					        =>	"Home | Audity ŽOS Zvolen",
            "login" 					        =>	"Login | Audity ŽOS Zvolen",
            "signup" 					        =>	"Signup | Audity ŽOS Zvolen",
            "30_zoznam_oblast_auditu"          =>	"Zoznam oblastí auditovania | Audity ŽOS Zvolen",
        ),	// "Titulok Stránky"

        // Meta značka stránky - description -> popisuje stránku
        "Popis Stránky" => array(
            "Chybová 404" 						=>	"Audity ŽOS Zvolen - chyba 404 - odkaz neexistuje",
            "Čistá" 							=>	"Audity ŽOS Zvolen - prázdna stránka pre potreby vývoja",
            "index" 					        =>	"Audity ŽOS Zvolen - hlavná stránka",
            "login" 					        =>	"Audity ŽOS Zvolen - prihlasovanie uživateľa",
            "signup" 					        =>	"Audity ŽOS Zvolen - vytvorenie nového účtu uživateľa",            
            "30_zoznam_oblast_auditu"           =>	"Audity ŽOS Zvolen - zoznam oblastí auditovania",

        ),	// "Popis Stránky"

        // Nadpisy prvej kapitoly.
        "Nadpis" => array(
            "Chybová 404" 						=>	"Chybová stránka 404",
            "Čistá" 							=>	"Prázdna stránka pre potreby vývoja",
            "index" 					        =>	"Predľad všetkých auditov",
            "login" 					        =>	false,
            "signup" 					        =>	false,
            "30_zoznam_oblast_auditu"           =>	"Zoznam oblastí auditovania",               

        ),	// "_Nadpis kapitoly"

        // Bublinkové menu ->  určuje či sa na stránke zobrazí bublinkové menu a následne ho naplní
        "Bublinkové Menu" => array(
            "Chybová 404" 						=>	false,
            "Čistá" 							=>	false,
            "index"  					        =>	array ( array("", "Prehľad auditov")),
            "login" 					        =>	false,
            "signup" 					        =>	false,
            "30_zoznam_oblast_auditu"           =>	array ( array("", "Audit"),
                                                            array("", "Zoznam oblasti")),           
            

            // VZORY
            "podstranka"						=>	array ( array("", "Audity")),
            "podstranka->stranka"       		=>	array ( array("/audity", "Audity"),
                                                            array("", "Zoznam auditov")),
        ),	// "Bublinkové Menu"
    );

    // MENU hlavné
    public $menuHlavne = array(
        1 => array(
            "KodStranky" => false,
            "Nazov" => "Audity",
            "Link" => "#",
            "Doplnok" => "badge badge-danger",
            "PopisDoplnku" => "Kuk",
            "Ikona" => "fas fa-check-double",
            "SUBMENU" => array(
                1 => array(
                    "KodStranky" => "index",
                    "Nazov" => "Prehľad auditov",
                    "Link" => "/",
                    "Doplnok" => "badge badge-warning",
                    "PopisDoplnku" => "návrh",                    
                ),
                2 => array(
                    "KodStranky" => "Čistá",
                    "Nazov" => "Nový audit",
                    "Link" => "#",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,                    
                ),
            ),
        ),
        2 => "Reporty",
        3 => array(
            "KodStranky" => "Čistá",
            "Nazov" => "Grafy",
            "Link" => "#",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-chart-area",
            "SUBMENU" => false,
        ),
        4 => array(
            "KodStranky" => "Čistá",
            "Nazov" => "Tabulky",
            "Link" => "#",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-table",
            "SUBMENU" => false,
        ),
        5 => array(
            "KodStranky" => false,
            "Nazov" => "Prehlady",
            "Link" => "#",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-binoculars",
            "SUBMENU" => array(
                1 => array(
                    "KodStranky" => "Čistá",
                    "Nazov" => "Prehľad 1",
                    "Link" => "#",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,                    
                ),
                2 => array(
                    "KodStranky" => "Čistá",
                    "Nazov" => "Tabuľka 2",
                    "Link" => "#",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,                    
                ),
            ),
        ),
        6 => "Zoznamy",
        7 => array(
            "KodStranky" => false,
            "Nazov" => "Firma",
            "Link" => "#",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-list-ul",
            "SUBMENU" => array(
                1 => array(
                    "KodStranky" => "Čistá",
                    "Nazov" => "Zoznam firiem",
                    "Link" => "#",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,                    
                ),
                2 => array(
                    "KodStranky" => "Čistá",
                    "Nazov" => "Osoby vo firme",
                    "Link" => "#",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,                    
                ),
                3 => array(
                    "KodStranky" => "Čistá",
                    "Nazov" => "Útvary",
                    "Link" => "#",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,                    
                ),                
            ),
        ),
        8 => array(
            "KodStranky" => false,
            "Nazov" => "Audit",
            "Link" => "#",
            "Doplnok" => "badge badge-danger",
            "PopisDoplnku" => "kuk",
            "Ikona" => "fas fa-clipboard-list",
            "SUBMENU" => array(
                1 => array(
                    "KodStranky" => "30_zoznam_oblast_auditu",
                    "Nazov" => "Zoznam oblastí",
                    "Link" => "/audit/zoznam-oblasti-auditu",
                    "Doplnok" => "badge badge-warning",
                    "PopisDoplnku" => "PHP",
                ),
                2 => array(
                    "KodStranky" => "30_zoznam_oblast_auditu",
                    "Nazov" => "Zoznam oblastí B",
                    "Link" => "/audit/zoznam-oblasti-auditu-ajax",
                    "Doplnok" => "badge badge-warning",
                    "PopisDoplnku" => "AJAX",
                ),                
                3 => array(
                    "KodStranky" => "Čistá",
                    "Nazov" => "Rola pri audite",
                    "Link" => "#",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,                    
                ),
                4 => array(
                    "KodStranky" => "Čistá",
                    "Nazov" => "Rola pri opatrení",
                    "Link" => "#",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,                    
                ),                
            ),
        ),
    );




    function __construct($nazov)
    {
        // Naplnenie konštánt pre konkrétnu stránku z poľa:  $konstantyStranok
        // ak na stránke nieje premenná ktorá označuje o akú stránku ide
        // alebo v poli $konstantyStranok chýba hodnota prislúchajúca stránke, nastavia sa štandardné hodnoty

        // Meta značky stránky
        if (array_key_exists($nazov, $this->konstantyStranok["Titulok Stránky"])) {
                $this->titulokStranky = $this->konstantyStranok["Titulok Stránky"][$nazov];
        } else {$this->titulokStranky = "Audity ŽOS Zvolen";}

        if (array_key_exists($nazov, $this->konstantyStranok["Popis Stránky"])) {
                $this->popisStranky = $this->konstantyStranok["Popis Stránky"][$nazov];
        } else {$this->popisStranky = "Audity ŽOS Zvolen - stránka bez popisu";}

        // určuje či sa na stránke zobrazí bublinkové menu a následne ho naplní
        if (array_key_exists($nazov, $this->konstantyStranok["Bublinkové Menu"])) {
                $this->bublinkoveMenu = $this->konstantyStranok["Bublinkové Menu"][$nazov];
        } else {$this->bublinkoveMenu = false;}

        // určuje či sa na stránke zobrazí bublinkové menu a následne ho naplní
        if (array_key_exists($nazov, $this->konstantyStranok["Nadpis"])) {
                $this->nadpisPrvejSekcie = $this->konstantyStranok["Nadpis"][$nazov];
        } else {$this->nadpisPrvejSekcie = "Nadpis stránky";}     
    }

}