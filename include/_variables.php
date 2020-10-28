<?php

// Inicializačné konštanty každej stránky: audity.zos.sk
class Premenne
{

    public $titulokStranky;
    public $popisStranky;
    public $bublinkoveMenu;
    public $nadpisPrvejSekcie;

    // MENU hlavné a zoznam liniek do stránok jednotlivých sekcií
    public $menuHlavne = array (
        "Farnosť" => array (
            1	=> array ("farnost"								,"text-success"		,"/"														,"Aktuality"),
            5	=> "separator",
            6	=> array ("farnost->historia-kostola"			,""					,"/farnost/historia-kostola-sv-frantiska-v-detve"			,"História kostola"),

        ),

        "Spoločenstvá" => array (
            1	=> array ("spolocenstva->zbor-hosanna"			,"text-danger"		,"/spolocenstva/mladeznicky-zbor-hosanna" 		,"Mládežnícky zbor Hosanna"),
            5	=> "separator",
            6	=> array ("spolocenstva->lektori"				,"text-danger"		,"/spolocenstva/lektori" 						,"Lektori"),

        ),

        "Liturgia" => array (
            1	=> array ("liturgia->sviatost-krstu"			,"text-warning"		,"/liturgia/sviatost-krstu" 			 	,"Sviatosť krstu"),
            8	=> "separator",
            9	=> array ("liturgia->pohreb"					,"text-danger"		,"/liturgia/pohreb" 					 	,"Pohreb"),

        ),

        "Dekanát" => array (
            1	=> array ("dekanat->schematizmus"				,"text-warning"		,"/dekanat/schematizmus-dekanatu-detva-zoznam-farnosti-a-knazov"	,"Mapa a zoznam farností"),
            4	=> "separator",
            5	=> array ("dekanat->svate-omse-v-okoli"			,"text-danger"		,"/dekanat/svate-omse-v-okoli" 										,"Sväté omše v okolí"),

        ),

    );

    // Konštanty stránok individuálne
    Public $konstantyStranok = array(

        // Meta značka stránky - TITLE -> Zobrazuje sa ako názov okna.
        "Titulok Stránky" => array(
            "Chybová 404" 						=>	"Farnosť Detva - chybová stránka",
            "Čistá" 							=>	"Farnosť Detva - oficiálna stránka farnosti aj dekanátu, na stránke sa pracuje",
            "index" 					        =>	"Home | Audity ŽOS Zvolen",
            "login" 					        =>	"Login | Audity ŽOS Zvolen",
            "signup" 					        =>	"Signup | Audity ŽOS Zvolen",

        ),	// "Titulok Stránky"

        // Meta značka stránky - description -> popisuje stránku
        "Popis Stránky" => array(
            "Chybová 404" 						=>	"Farnosť Detva - stránka chybová - Error 404",
            "Čistá" 							=>	"Farnosť Detva - čistá stránka, na ktorej nieje žiadny obsah. Zobrazuje sa pri neexistujúcom odkaze.",
            "index" 					        =>	"Audity ŽOS Zvolen - hlavná stránka",
            "login" 					        =>	"Audity ŽOS Zvolen - prihlasovanie uživateľa",
            "signup" 					        =>	"Audity ŽOS Zvolen - vytvorenie nového účtu uživateľa",            

        ),	// "Popis Stránky"


        // Bublinkové menu ->  určuje či sa na stránke zobrazí bublinkové menu a následne ho naplní
        "Bublinkové Menu" => array(
            "Chybová 404" 						=>	false,
            "Čistá" 							=>	false,
            "index"  					        =>	array ( array("", "Prehľad auditov")),
            "login" 					        =>	false,
            "signup" 					        =>	false,

            "farnost"							=>	array ( array("", "Farnosť")),
            "farnost->liturgicke-oznamy"		=>	array ( array("/farnost", "Farnosť"),
                                                            array("", "Litgurgické oznamy")),
            "farnost->rozpisy-lektorov-detva"	=>	array ( array("/farnost", "Farnosť"),
                                                            array("", "Rozpisy lektorov")),
            "farnost->svate-omse-farnosti-detva"=>	array ( array("/farnost", "Farnosť"),
                                                            array("", "Omše vo farnosti")),
            "farnost->historia-kostola"			=>	array ( array("/farnost", "Farnosť"),
                                                            array("", "História kostola sv. Frantiska v Detve")),
        ),	// "Bublinkové Menu"

        // Nadpisy prvej kapitoly.
        "Nadpis" => array(
            "Chybová 404" 						=>	"Farnosť Detva - chybová stránka",
            "Čistá" 							=>	"Farnosť Detva - oficiálna stránka farnosti aj dekanátu, na stránke sa pracuje",
            "index" 					        =>	"Predľad všetkých auditov",
            "login" 					        =>	false,
            "signup" 					        =>	false,

        ),	// "Titulok Stránky"
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