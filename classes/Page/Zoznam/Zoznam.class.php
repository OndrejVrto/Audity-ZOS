<?php

namespace Page\Zoznam;

class Zoznam extends \Page\Page
{

    public $pagination = false;
    public $info = false;

    function ContentHeaderZoznamTlacitka (){

?>
    <div class='row justify-content-center pb-3'>
        <div id="UpravaDat" class='form-inline'>
<?php if ( isset($_SESSION['Admin']) && $_SESSION['Admin'] == 1 ){ ?>
            <form action="<?= $this->linkCisty ?>novy" method="post">
                <button type="submit" name="novy" ID='novy-zaznam' class="btn btn-primary">Pridať položku</button>
            </form>
<?php } ?>
            <form action="<?= $this->linkCisty ?>detail" method="post" class="mx-1">
                <button type="submit" name="detail" ID='button-detail' value="" class="btn btn-warning" disabled>Detaily</button>
            </form>
<?php if ( isset($_SESSION['Admin']) && $_SESSION['Admin'] == 1 ){ ?>
            <form action="<?= $this->linkCisty ?>edit" method="post" class="mr-1">
                <button type="submit" name="edit" ID='button-edit' value="" class="btn btn-success" disabled>Editovať</button>
            </form>
            <form action="<?= $this->linkCisty ?>delete" method="post">
                <button type="submit" name="delete" ID='button-delete' value="" class="btn btn-danger" disabled>Zmazať</button>
            </form>
<?php } ?>
        </div>
    </div>
<?php
    }

    function ContentHeaderZoznam (){

?>

    <div class="row justify-content-center">
        <div class="<?= $this->bodyClassExtended ?>" style="<?= $this->bodyWidthExtended ?>">

            <div class='card'>
                <div class='card-body p-2'>

                    <table class='table table-sm hover compact' width="100%" id='tabulka'>

<?php
    }

    function ContentFooterZoznam()
    {
?>

                    </table>

                </div>
            </div>

        </div>
    </div>

<?php
    }

    function PredvyplnenieKonstant(){
        
        parent::clearStyles();
        parent::addStyles("Font Awesome", true);
        parent::addStyles("Ionicons", true);
        parent::addStyles("DataTables-jQuery", false);
        parent::addStyles("DataTables-Bootstrap", true);
        parent::addStyles("DataTables-Select", true);
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
        parent::addScripts("DataTables-Responsive",true);
        parent::addScripts("DataTables-Responsive-Bootstrap",true);
        parent::addScripts("AdminLTE App",true);
        parent::addScripts("AdminLTE for demo purposes", false);
        
        $this->skriptySpecial = $this->ScriptyZoznam() . $this->skriptySpecial;

    }

    function ScriptyZoznam()
    {

ob_start();  // Začiatok definície Špeciálnych SKRIPTov pre túto stránku
?>

    <!-- START - skripty SPECIAL -->
    <script>
    $(document).ready(function() {
        var table = $('#tabulka').DataTable({
            responsive: true,
            select: 'single',
            paging: <?php if ($this->pagination) { echo 'true'; } else { echo 'false'; } ?>,
            info: <?php if ($this->info) { echo 'true'; } else { echo 'false'; } ?>,
            language: {
                sEmptyTable:     "Nie sú k dispozícii žiadne dáta",
                sInfo:           "Záznamy _START_ až _END_ z celkom _TOTAL_",
                sInfoEmpty:      "Záznamy 0 až 0 z celkom 0 ",
                sInfoFiltered:   "(vyfiltrované spomedzi _MAX_ záznamov)",
                sInfoPostFix:    "",
                sInfoThousands:  " ",
                sLengthMenu:     "Zobraz _MENU_ záznamov",
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