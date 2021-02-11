<?php

namespace Page\Zoznam;

class ZoznamSkripty extends \Page\Page
{

    public $pagination = true;
    public $info = false;
    public $riadkov = 15;

    public function PredvyplnenieKonstant(){
        
        parent::clearStyles();
        parent::addStyles("Font Awesome", true);
        parent::addStyles("animate", true);
        parent::addStyles("Ionicons", true);
        parent::addStyles("DataTables-jQuery", false);
        parent::addStyles("DataTables-Bootstrap", true);
        parent::addStyles("DataTables-Select", true);
        // parent::addStyles("DataTables-Buttons", true);
        parent::addStyles("DataTables-Responsive", true);
        parent::addStyles("Adminlte style", false);
        parent::addStyles("Google Font: Source Sans Pro", false);
        
        // definícia Skriptov
        parent::clearScripts();
        parent::addScripts("jQuery",true);
        parent::addScripts("Bootstrap 4-bundle",true);
        parent::addScripts("DataTables",true);
        parent::addScripts("DataTables-Bootstrap4",true);
        parent::addScripts("DataTables-Select",true);
        parent::addScripts("DataTables-Select-Bootstrap4",true);
        // parent::addScripts("DataTables-Buttons",true);
        // parent::addScripts("DataTables-Buttons-Bootstrap4",true);
        // parent::addScripts("DataTables-Buttons-HTML5",true);
        parent::addScripts("DataTables-Responsive",true);
        parent::addScripts("DataTables-Responsive-Bootstrap",true);
        parent::addScripts("AdminLTE App",true);
        //parent::addScripts("AdminLTE for demo purposes", false);
        
        $this->skriptySpecial = $this->ScriptyZoznam() . $this->skriptySpecial;

    }

    public function ScriptyZoznam()
    {

ob_start();  // Začiatok definície Špeciálnych SKRIPTov pre túto stránku
?>

    <!-- START - skripty SPECIAL -->
    <script nonce="<?= $GLOBALS["nonce"] ?>">
    $(document).ready(function() {
        var table = $('#tabulka').DataTable({
/*             dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'pageLength'
            ], */
            responsive: true,
            select: 'single',
            paging: <?php if ($this->pagination) { echo 'true'; } else { echo 'false'; } ?>,
            info: <?php if ($this->info) { echo 'true'; } else { echo 'false'; } ?>,
            pageLength: <?= $this->riadkov ?>,
            lengthMenu: [
                [ 10, 15, 25, 50, -1 ],
                [ '10 riadkov', '15 riadkov', '25 riadkov', '50 riadkov', 'Všetko' ]
            ],
            language: {
                sEmptyTable:     "Nie sú k dispozícii žiadne dáta",
                sInfo:           "Záznamy _START_ až _END_ z celkom _TOTAL_",
                sInfoEmpty:      "Záznamy 0 až 0 z celkom 0 ",
                sInfoFiltered:   "(vyfiltrované spomedzi _MAX_ záznamov)",
                sInfoPostFix:    "",
                sInfoThousands:  " ",
                sLengthMenu:     "_MENU_",
                sLoadingRecords: "Načítavam...",
                sProcessing:     "Spracúvam...",
                sSearch:         "Hľadať v tabuľke:",
                sZeroRecord:     "Nenašli sa žiadne vyhovujúce záznamy",
                oPaginate: {
                    sFirst:      "Prvá",
                    sLast:       "Posledná",
                    sNext:       "Nasledujúca",
                    sPrevious:   "Predchádzajúca"
                },
                oAria: {
                    sSortAscending:  ": aktivujte na zoradenie stĺpca vzostupne",
                    sSortDescending: ": aktivujte na zoradenie stĺpca zostupne"
                },
                select: {
                    rows: {
                        _: "Označených %d riadkov",
                        0: "",
                        1: "Označený jeden riadok"
                    }
                }
            },
        } );

        $('#tabulka tbody').on('click', 'tr', function(event) {

            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
                $('#UpravaDat [id^=button]').attr({disabled: '',
                                                    value: ''});
            } else {
                table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                
                var id = table.row('.selected').data()['DT_RowId'];
                $('#UpravaDat [id^=button]').removeAttr('disabled').val(id);
            }
        });

    } );    
    </script>
    <!-- END - skripty SPECIAL -->
<?php
    return ob_get_clean();  // Koniec SKRIPTov
    }

}