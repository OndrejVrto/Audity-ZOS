<?php
    // Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
    spl_autoload_register(function ($class_name) {
        include_once  $_SERVER['DOCUMENT_ROOT'] . "/include/class/class." . $class_name . '.php';
    });

    $uri = "/audit/zoznam-oblasti-auditu/";
    $list = (isset($_GET['p'])) ? $_GET['p'] : "1" ;

    $page = new Page($uri , $list);

    // vyberovy dotaz na data
    $sql = "SELECT * FROM 30_zoznam_oblast_auditu ORDER BY LOWER(OblastAuditovania) ASC";
    $data = dBzoznam($sql, $uri);

ob_start();  // Začiatok definície hlavného obsahu
?>
    <div class='row justify-content-center pb-3'>
        <div id="UpravaDat" class='form-inline'>
            <form action="<?= $uri ?>novy" method="post">
                <button type="submit" name="novy" ID='novy-zaznam' class="btn btn-warning">Pridať položku</button>
            </form>
            <form action="<?= $uri ?>detail" method="post" class="mx-1">
                <button type="submit" name="detail" ID='button-detail' value="" class="btn btn-secondary" disabled>Detaily</button>
            </form>
            <form action="<?= $uri ?>edit" method="post" class="mr-1">
                <button type="submit" name="edit" ID='button-edit' value="" class="btn btn-success" disabled>Editovať</button>
            </form>
            <form action="<?= $uri ?>delete" method="post">
                <button type="submit" name="delete" ID='button-delete' value="" class="btn btn-danger" disabled>Zmazať</button>
            </form>
        </div>
    </div>

    <div class='row justify-content-center'>
        <div class='col-12 col-sm-10 col-md-9 col-lg-7' style='max-width: 600px; width:100%;'>

            <div class='card'>
                <div class='card-body p-2'>

                    <table class='table table-sm hover compact' id='tabulka'>
                        <thead>

                            <tr>
                                <th>P.č.</th>
                                <th>Zoznam oblastí</th>
                            </tr>

                        </thead>
                        <tbody>

<?php
    $poradie = 1;
    foreach ($data as $key => $value) {
        $id = htmlspecialchars($value['ID30']);
        $oblastAuditovania = htmlspecialchars($value['OblastAuditovania']);
?>
                            <tr id='<?= $id ?>'>
                                <td><?= $poradie ?>.</td>
                                <td><?= $oblastAuditovania ?></td>
                            </tr>
<?php
        $poradie += 1;
    }
?>

                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

ob_start();  // Začiatok definície Špeciálnych SKRIPTov pre túto stránku
?>

    <!-- START - skripty SPECIAL -->
    <script>
    $(document).ready(function() {
        var table = $('#tabulka').DataTable({
            responsive: true,
            select: 'single',
            paging: false,
            info: false,
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
                sSearch:         "Hľadať:",
                sZeroRecord:    "Nenašli sa žiadne vyhovujúce záznamy",
                oPaginate: {
                    sFirst:    "Prvá",
                    sLast:     "Posledná",
                    sNext:     "Nasledujúca",
                    sPrevious: "Predchádzajúca"
                },
                oAria: {
                    sSortAscending:  ": aktivujte na zoradenie stĺpca vzostupne",
                    sSortDescending: ": aktivujte na zoradenie stĺpca zostupne"
                }
            },
            columnDefs: [
                {
                    targets: -2,
                    width: '15%',
                    className: 'dt-body-center'
                }              
            ],
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
$page->skriptySpecial = ob_get_clean();  // Koniec SKRIPTov

// definícia CSS knizníc
$page->clearStyles();
$page->addStyles("Font Awesome", true);
$page->addStyles("Ionicons", true);
$page->addStyles("DataTables-jQuery", false);
$page->addStyles("DataTables-Bootstrap", true);
$page->addStyles("DataTables-Select", true);
$page->addStyles("DataTables-Responsive", true);
$page->addStyles("Adminlte style", false);
$page->addStyles("Google Font: Source Sans Pro", false);

// definícia Skriptov
$page->clearScripts();
$page->addScripts("jQuery",true);
$page->addScripts("Bootstrap 4-bundle",true);
$page->addScripts("DataTables",true);
$page->addScripts("DataTables-Bootstrap4",true);
$page->addScripts("DataTables-Select",true);
$page->addScripts("DataTables-Select-Bootstrap4",true);
$page->addScripts("DataTables-Responsive",true);
$page->addScripts("DataTables-Responsive-Bootstrap",true);
$page->addScripts("AdminLTE App",true);
$page->addScripts("AdminLTE for demo purposes", false);

// vykreslenie stranky
$page->display();