<?php

trait Funkcie {

    // pridá ku každému riadku niekoľko tabulátorov
    function pridaj_tabulator_html($code, $num) {
        $tabs = str_repeat("\t", $num); // or spaces if you want
        return $tabs . str_replace("\n", "\n$tabs", $code) . PHP_EOL;
    }

    // odstráni z čistého HTML kódu všetky znaky a pripraví ho pre vloženie do databázy
    public function htmlPurify($html) {
        return htmlentities(htmlspecialchars($html));
    }

    // po načítaní textu z databázy vráti text do formátu HTML
    public function htmlAntiPurify($text) {
        return html_entity_decode(htmlspecialchars_decode($text));
    }

    // vráti názov dňa z dátumu v slovenčine
    public function denTyzdna($datum) {
        $den = array(1 => 'pondelok', 'utorok', 'streda', 'štvrtok', 'piatok', 'sobota', 'nedeľa');
        return $den[date_format(date_create($datum), "N")];
    }

    // vráti rozdiel dátumov v textovej podobe
    function dateDiff($date) {
        $mydate = date("Y-m-d H:i:s");
        $datetime1 = date_create($date);
        $datetime2 = date_create($mydate);
        $interval = date_diff($datetime1, $datetime2);

        $min = $interval->format('%i');
        $sec = $interval->format('%s');
        $hour = $interval->format('%h');
        $mon = $interval->format('%m');
        $day = $interval->format('%d');
        $year = $interval->format('%y');

        $text = 'pred ';
        if ($interval->format('%i%h%d%m%y') == "00000") {
            switch ($sec) {
                case "0":
                case "1":
                    $text .= 'sekundou';
                    break;
                default:
                    $text .= $sec . ' sekundami';
                    break;
            }
        } else if ($interval->format('%h%d%m%y') == "0000") {
            switch ($min) {
                case "0":
                case "1":
                    $text .= 'minútou';
                    break;
                default:
                    $text .= $min . ' minútami';
                    break;
            }
        } else if ($interval->format('%d%m%y') == "000") {
            switch ($hour) {
                case "0":
                case "1":
                    $text .= 'hodinou';
                    break;
                default:
                    $text .= $hour . ' hodinami';
                    break;
            }
        } else if ($interval->format('%m%y') == "00") {
            switch ($day) {
                case "0":
                case "1":
                    $text = 'včera';
                    break;
                default:
                    $text .= $day . ' dňami';
                    break;
            }
        } else if ($interval->format('%y') == "0") {
            switch ($mon) {
                case "0":
                case "1":
                    $text .= 'mesiacom';
                    break;
                default:
                    $text .= $mon . ' mesiacmi';
                    break;
            }
        } else {
            switch ($year) {
                case "0":
                case "1":
                    $text .= 'rokom';
                    break;
                default:
                    $text .= $year . ' rokmi';
                    break;
            }
        }

        return $text;
        //echo $interval->format('%s Seconds %i Minutes %h Hours %d days %m Months %y Year Ago')."<br>";
    }

    // vráti farbu dňa pre TimeLine
    public function FarbaDna($datum) {
        $farba = array(
            1 => 'bg-primary',
            2 => 'bg-success',
            3 => 'bg-warning text-dark',
            4 => 'bg-danger',
            5 => 'bg-info',
            6 => 'bg-dark',
            7 => 'bg-secondary'
        );
        return $farba[date_format(date_create($datum), "N")];
    }

    public function MinifiHTML($html)
    {
        $vstup = array(
                    "/<!--.*-->/m",     // odstráni komentáre
                    "/\s{2,}/",         // dve medzery, konce riadkov a tabulátory za jednu medzeru
                    "/\n/",             // odstráni zvyšné konce riadkov
                    "/> </",            // odstráni medery medzi TAGmi
                    );
        $vystup = array(""," ","","><");

        return preg_replace($vstup, $vystup, $html);
    }
}
