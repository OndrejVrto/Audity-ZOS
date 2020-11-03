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
            "/404"                                  =>    "Chybová stránka 404 | Audity ŽOS Zvolen",
            "/"                                     =>    "Home | Audity ŽOS Zvolen",
            "/login"                                =>    "Login | Audity ŽOS Zvolen",
            "/signup"                               =>    "Signup | Audity ŽOS Zvolen",
            "/audit/zoznam-oblasti-auditu"          =>    "Zoznam oblastí auditovania | Audity ŽOS Zvolen",
            "/audit/zoznam-oblasti-auditu-ajax"     =>    "AJAX | Zoznam oblastí auditovania | Audity ŽOS Zvolen",
        ),    // "Titulok Stránky"

        // Meta značka stránky - Description -> popisuje stránku
        "Popis Stránky" => array(
            "/404"                                  =>    "Audity ŽOS Zvolen - chyba 404 - odkaz neexistuje",
            "/"                                     =>    "Audity ŽOS Zvolen - hlavná stránka",
            "/login"                                =>    "Audity ŽOS Zvolen - prihlasovanie uživateľa",
            "/signup"                               =>    "Audity ŽOS Zvolen - vytvorenie nového účtu uživateľa",
            "/audit/zoznam-oblasti-auditu"          =>    "Audity ŽOS Zvolen - zoznam oblastí auditovania",
            "/audit/zoznam-oblasti-auditu-ajax"     =>    "Audity ŽOS Zvolen - AJAXový zoznam",
        ),    // "Popis Stránky"

        // Nadpisy prvej kapitoly.
        "Nadpis" => array(
            "/404"                                  =>    "Chybová stránka 404",
            "/"                                     =>    "Predľad všetkých auditov",
            "/login"                                =>    false,
            "/signup"                               =>    false,
            "/audit/zoznam-oblasti-auditu"          =>    "Zoznam oblastí auditovania",
            "/audit/zoznam-oblasti-auditu-ajax"     =>    "Zoznam oblastí auditovania s AJAXom",
        ),    // "Nadpis prvej kapitoly"
    );

    // MENU hlavné
    public $menuHlavne = array(
        array(
            "Link" => false,
            "Nazov" => "Audity",
            "Doplnok" => "badge badge-danger",
            "PopisDoplnku" => "Kuk",
            "Ikona" => "fas fa-check-double",
            "SUBMENU" => array(
                array(
                    "Link" => "/",
                    "Nazov" => "Prehľad auditov",
                    "Doplnok" => "badge badge-warning",
                    "PopisDoplnku" => "návrh",
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false
                ),
                array(
                    "Link" => false,
                    "Nazov" => "Nový audit",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false
                ),
            ),
        ),
        array("Hlavicka" => "Reporty"),
        array(
            "Link" => false,
            "Nazov" => "Grafy",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-chart-area",
            "SUBMENU" => false,
        ),
        array(
            "Link" => false,
            "Nazov" => "Tabulky",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-table",
            "SUBMENU" => false,
        ),
        array(
            "Link" => false,
            "Nazov" => "Prehlady",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-binoculars",
            "SUBMENU" => array(
                array(
                    "Link" => false,
                    "Nazov" => "Prehľad 1",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false
                ),
                array(
                    "Link" => false,
                    "Nazov" => "Tabuľka 2",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false
                ),
            ),
        ),
        array("Hlavicka" => "Zoznamy"),
        array(
            "Link" => false,
            "Nazov" => "Firma",
            "Doplnok" => false,
            "PopisDoplnku" => false,
            "Ikona" => "fas fa-list-ul",
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
                    "Nazov" => "Osoby vo firme",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false
                ),
                array(
                    "Link" => false,
                    "Nazov" => "Útvary",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
            ),
        ),
        array(
            "Link" => false,
            "Nazov" => "Audit",
            "Doplnok" => "badge badge-danger",
            "PopisDoplnku" => "kuk",
            "Ikona" => "fas fa-clipboard-list",
            "SUBMENU" => array(
                array(
                    "Link" => false,
                    "Nazov" => "Zoznam oblastí",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => array(
                        array(
                            "Link" => "/audit/zoznam-oblasti-auditu-ajax",
                            "Nazov" => "AJAX",
                            "Doplnok" => false,
                            "PopisDoplnku" => false,
                            "Ikona" => "fab fa-js text-info",
                            "SUBMENU" => false,
                        ),
                        array(
                            "Link" => "/audit/zoznam-oblasti-auditu-ajax-post",
                            "Nazov" => "AJAX + POST",
                            "Doplnok" => "badge badge-danger",
                            "PopisDoplnku" => "Áno",
                            "Ikona" => "fab fa-java text-success",
                            "SUBMENU" => false,
                        ),
                        array(
                            "Link" => "/audit/zoznam-oblasti-auditu",
                            "Nazov" => "PHP",
                            "Doplnok" => "badge badge-warning",
                            "PopisDoplnku" => "Nie",
                            "Ikona" => "fab fa-php text-primary",
                            "SUBMENU" => false,
                        ),
                    ),
                ),
                array(
                    "Link" => false,
                    "Nazov" => "Rola pri audite",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
                array(
                    "Link" => false,
                    "Nazov" => "Rola pri opatrení",
                    "Doplnok" => false,
                    "PopisDoplnku" => false,
                    "Ikona" => "far fa-circle",
                    "SUBMENU" => false,
                ),
            ),
        ),
    );
}
