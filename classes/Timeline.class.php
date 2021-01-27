<?php

class Timeline {
    // trait import
    use Funkcie;

    private $odsadenie = 5;
    
    private $user;
    private $datum_Od;
    private $datum_Do;
    private $limit_days = 5;

    function __construct() {
    }

    function __set($name, $value) {
        $this->$name = $value;
    }

    public function ZobrazTimeLine() {

        global $db;
        $datumy = $db->query(
            'SELECT DISTINCT DATE(`DatumCas`) AS Datum
            FROM `80_timeline_zaznamy`
            ORDER BY `ID80` DESC;'
        )->fetchAll();

        ob_start(); // spustenie nahravania Cache

        $this->TimeLine_Begin();

        foreach ($datumy as $value) {

            $this->TimeLine_Label_Datum($value['Datum']);
            $dataTimeline = $db->query(
                'SELECT * 
                FROM `80_timeline_zaznamy`
                LEFT JOIN `50_sys_users`
                ON `ID50` = `ID50_sys_users`
                WHERE DATE(`DatumCas`) = ?
                ORDER BY ID80 DESC;',
                $value['Datum']
            )->fetchAll();

            foreach ($dataTimeline as $value) {
                $this->TimeLine_Prispevok(
                    $ikona = 'fas fa-envelope',
                    $farbaPozadia = 'bg-blue',
                    $cas = $value['DatumCas'],
                    $header = array(
                        "Link" => $value['OsobneCislo'],
                        "Meno" => $value['Meno'] . ' ' . $value['Priezvisko'],
                        "Text" => $value['TypPrispevku'] . ' - ' . $value['Header_Text']
                    ),
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



    private function TimeLine_Begin() {
?>

        <!-- TimeLine -->
        <div class="timeline">
        <?php
    }

    private function TimeLine_END() {
        ?>

        </div>
        <!-- END TimeLine -->
    <?php
    }

    private function TimeLine_Label_Datum($datum) {
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

    private function TimeLine_Prispevok($ikona, $farbaPozadia, $cas, $header, $body = false, $footer = false) {
        if ($body == null) $body = false;
        if ($footer == null) $footer = false;
    ?>

        <!-- TimeLine položka -->
        <div>
            <i class="<?= htmlspecialchars($ikona . ' ' . $farbaPozadia) ?>"></i>
            <div class="timeline-item">
                <span class="time"><i class="fas fa-clock"></i> <?= $this->dateDiff($cas) ?></span>
                <h3 class="timeline-header<?= ($body and $footer) ? '' : ' no-border' ?>">
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

    private function TimeLine_EndIcon() {
    ?>

        <!-- TimeLine Koncová Ikona -->
        <div>
            <i class="fas fa-clock bg-gray"></i>
        </div>
        <!-- END TimeLine Koncová Ikona -->
<?php
    }
}
