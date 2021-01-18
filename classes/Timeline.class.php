<?php

class Timeline
{

    private $odsadenie = 5;
    private $user;
    private $datum_Od;
    private $datum_Do;

    function __construct()
    {

    }

    function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function ZobrazTimeLine ()
    {

        global $db;
        $datumy = $db->query(
            'SELECT DISTINCT DATE(`DatumCas`) AS Datum
            FROM `80_timeline_zaznamy`
            ORDER BY `ID80` DESC;'
            )->fetchAll();

        ob_start(); // spustenie nahravania Cache

        $this->TimeLine_Begin();

        foreach ($datumy as $key => $value) {

            $this->TimeLine_Label_Datum ($value['Datum']);
            $dataTimeline = $db->query(
                'SELECT * 
                FROM `80_timeline_zaznamy`
                LEFT JOIN `50_sys_users`
                ON `ID50` = `ID50_sys_users`
                WHERE DATE(`DatumCas`) = ?
                ORDER BY ID80 DESC;',
                $value['Datum'] )->fetchAll();
            
            foreach ($dataTimeline as $key => $value) {
                $this->TimeLine_Prispevok(
                    $ikona = 'fas fa-envelope',
                    $farbaPozadia = 'bg-blue', 
                    $cas = $value['DatumCas'],
                    $header = array("Link" => $value['OsobneCislo'], 
                                    "Meno" => $value['Meno'].' '.$value['Priezvisko'],
                                    "Text" => $value['TypPrispevku'].' - '.$value['Header_Text']), 
                    $body = $value['Body_Text'], 
                    $footer = $value['Footer_Text']
                    );
            }
        }

        $this->TimeLine_EndIcon();
        $this->TimeLine_END();
        
        $html = ob_get_clean(); // načítanie Cache
        return $this->pridaj_tabulator_html($html, $this->odsadenie);
    }



    private function TimeLine_Begin (){
?>

<!-- TimeLine -->
<div class="timeline">
<?php
    }

    private function TimeLine_END (){
?>

</div>
<!-- END TimeLine -->
<?php
    }

    private function TimeLine_Label_Datum ($datum)
    {
        if (!isset($datum)) $datum = date('j M. Y');

        $datum = date_format(date_create($datum), "j M. Y");
        $den = $this->denTyzdna($datum);
        $farba = $this->FarbaDna($datum);
?>

    <!-- TimeLine Date Label -->
    <div class="time-label">
        <span class="px-2 <?= $farba ?>"><?= $datum ?><small class="pl-2">(<?= $den ?>)</small></span>
    </div>
    <!-- END TimeLine Date Label -->
<?php
    }

    private function TimeLine_Prispevok($ikona, $farbaPozadia, $cas, $header, $body = false, $footer = false)
    {
        if ($body == null) $body = false;
        if ($footer == null) $footer = false;
?>

        <!-- TimeLine položka -->
        <div>
            <i class="<?= htmlspecialchars($ikona.' '.$farbaPozadia) ?>"></i>
            <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> <?= $this->dateDiff($cas) ?></span>
                <h3 class="timeline-header<?= ($body AND $footer) ? '' : ' no-border' ?>">
                    <a href="<?= $header['Link'] ?>"><?= $header['Meno'] ?></a> <?= $this->htmlAntiPurify($header['Text']) . PHP_EOL ?>
                </h3>
<?php
    if ($body) {
?>
                <div class="timeline-body">
                    <?= $this->htmlAntiPurify($body) . PHP_EOL ?>
                </div>
<?php }
    if ($footer) {
?>
                <div class="timeline-footer">
                    <?= $this->htmlAntiPurify($footer) . PHP_EOL ?>
                </div>
<?php } ?>
            </div>
        </div>
        <!-- END TimeLine položka -->
<?php
    }

    private function TimeLine_EndIcon (){
?>

    <!-- TimeLine Koncová Ikona -->
    <div>
        <i class="fas fa-clock bg-gray"></i>
    </div>
    <!-- END TimeLine Koncová Ikona -->
<?php
    }


    function dateDiff($date)
    {
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
                case "1": $text .= 'sekundou'; break;
                default:  $text .= $sec.' sekundami'; break;
            }
        } else if ($interval->format('%h%d%m%y') == "0000") {
            switch ($min) {
                case "0": 
                case "1": $text .= 'minútou'; break;
                default:  $text .= $min.' minútami'; break;
            }
        } else if ($interval->format('%d%m%y') == "000") {
            switch ($hour) {
                case "0": 
                case "1": $text .= 'hodinou'; break;
                default:  $text .= $hour.' hodinami'; break;
            }
        } else if ($interval->format('%m%y') == "00") {
            switch ($day) {
                case "0": 
                case "1": $text = 'včera'; break;
                default:  $text .= $day.' dňami'; break;
            }
        } else if ($interval->format('%y') == "0") {
            switch ($mon) {
                case "0": 
                case "1": $text .= 'mesiacom'; break;
                default:  $text .= $mon.' mesiacmi'; break;
            }
        } else {
            switch ($year) {
                case "0": 
                case "1": $text .= 'rokom'; break;
                default:  $text .= $year.' rokmi'; break;
            }
        }

        return $text;
        //echo $interval->format('%s Seconds %i Minutes %h Hours %d days %m Months %y Year Ago')."<br>";
    }

    // vráti názov dňa z dátumu v slovenčine
    public function denTyzdna ($datum) {
        $den = array(1 => 'pondelok', 'utorok', 'streda', 'štvrtok', 'piatok', 'sobota', 'nedeľa');
        return $den[date_format(date_create($datum), "N")];
    }

    // vráti farbu dňa pre TimeLine
    public function FarbaDna($datum)
    {
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
}