<?php
    // Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
    spl_autoload_register(function ($class_name) {
        include_once  $_SERVER['DOCUMENT_ROOT'] . "/include/class/class." . $class_name . '.php';
    });

    $uri = "/audit/zoznam-oblasti-auditu/";
    $list = (isset($_GET['p'])) ? $_GET['p'] : "1" ;

    $homepage = new Page($uri , $list);
    $homepage->zobrazitBublinky = true;

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
        $id = htmlentities($value['ID30']);
        $oblastAuditovania = htmlentities($value['OblastAuditovania']);
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
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

<?php
$homepage->content = ob_get_clean();  // Koniec hlavného obsahu

ob_start();  // Začiatok definície SKRIPTov pre túto stránku
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
            event.preventDefault();
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
                $('#UpravaDat [id^=button]').attr('disabled', null);
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
$homepage->skriptySpecial = ob_get_clean();  // Koniec SKRIPTov


ob_start();  // Začiatok definície CSS pre túto stránku 
?>
    <!-- Font Awesome -->
    <link rel='stylesheet' href='/plugins/fontawesome-free/css/all.min.css'>

    <!-- Ionicons -->
    <link rel='stylesheet' href='/dist/css/ionicons/css/ionicons.min.css'>

    <!-- DataTables -->
    <link rel='stylesheet' href='/plugins/datatables/css/jquery.dataTables.css'>
    <link rel='stylesheet' href='/plugins/datatables/css/dataTables.bootstrap4.min.css'>
    <link rel='stylesheet' href='/plugins/datatables-select/css/select.bootstrap4.min.css'>
    <link rel='stylesheet' href='/plugins/datatables-responsive/css/responsive.bootstrap4.min.css'>

    <!-- Theme style -->
    <link rel='stylesheet' href='/dist/css/adminlte.css'>

    <!-- Google Font: Source Sans Pro -->
    <link rel='stylesheet' href='/dist/css/www/fonts.googleapis.css'>
<?php
$homepage->styles = ob_get_clean();  // Koniec definícií CSS


ob_start();  // Začiatok definície SKRIPTov pre túto stránku
?>

    <!-- START - skripty -->

    <!-- jQuery -->
    <script src='/plugins/jquery/jquery.min.js'></script>
    <!-- Bootstrap 4 -->
    <script src='/plugins/bootstrap/js/bootstrap.bundle.min.js'></script>

    <!-- DataTables -->
    <!-- <script src='/plugins/datatables/jquery.dataTables.min.js'></script> -->
    <script src='/plugins/datatables/js/jquery.dataTables.min.js'></script>
    <script src='/plugins/datatables/js/dataTables.bootstrap4.min.js'></script>

    <script src='/plugins/datatables-select/js/dataTables.select.min.js'></script>
    <script src='/plugins/datatables-select/js/select.bootstrap4.min.js'></script>

    <script src='/plugins/datatables-responsive/js/dataTables.responsive.min.js'></script>
    <script src='/plugins/datatables-responsive/js/responsive.bootstrap4.min.js'></script>

    <!-- AdminLTE App -->
    <script src='/dist/js/adminlte.js'></script>
    <!-- AdminLTE for demo purposes -->
    <script src='/dist/js/demo.js'></script>

    <!-- END - skripty -->

<?php
$homepage->skripty = ob_get_clean();  // Koniec SKRIPTov

$homepage->display();  // vykreslenie stranky
?>