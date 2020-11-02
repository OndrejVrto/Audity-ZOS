<?php
//  print_r($_POST);

// Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
spl_autoload_register(function ($class_name) {
    include_once  $_SERVER['DOCUMENT_ROOT'] . "/include/class/class." . $class_name . '.php';
});

// založenie novej triedy na stranku
$homepage = new Page('30_zoznam_oblast_auditu', 1);
$uri = "/audit/zoznam-oblasti-auditu-ajax";

// prepísanie hodnôt stránky ručne. Štandardne sa hodnoty načítavajú z _variables.php
// $homepage->nadpis = 'Nadpis';


// inicializácia konštánt formulára v prípade volania metódou GET
$mena_vsetkych_poli = array ('oblast-auditu', 'oblast-auditu-poznamka');
foreach ($mena_vsetkych_poli as $key => $value) {
    $validation_values[$value] = $validation_classes[$value] = $validation_feedback[$value] = '';
}

$request_method = strtoupper($_SERVER['REQUEST_METHOD']);

if ($request_method === 'GET') {
    // spustí sa ak existuje GET, teda aj pri prvom spustení
    // program na vyplnenie formulára údajmi (napr:dotaz do databazy cez najaký class)
} elseif ($request_method === 'POST') {
    // spustí sa ak existuje POST
    if (isset($_POST['submit'])) {
        // spustí sa ak bolo stlačené tlačítko ->  name="submit"

        // inicializácia class Validate
        $validation = new ValidatorOblastAuditu($_POST);

        $validation->odsadenie = 5;  // odsadzuje HTML kod o 5 tabulátorov
        $result = $validation->validateForm();  // validuje formulár - !! kľúče validovaných polí musia byť v zadefinované v triede
        $validation_values = $validation->validateFormGetValues();   // vracia hodnoty polí pre každý kľúč
        $validation_classes = $validation->validateFormGetClasses();  // vracia triedy:  is-valid / is-invalid pre každý kľúč
        $validation_feedback = $validation->validateFormGetFeedback();  // vracia správy pre každý kľúč

        // if result is TRUE (1) --> save data to db  OR  reditect page
        if ($result == 1) {

            
            $sql = "INSERT INTO `30_zoznam_oblast_auditu` (`OblastAuditovania`, `Poznamka`) VALUES ('" . $validation_values['oblast-auditu'] . "', '" . $validation_values['oblast-auditu-poznamka'] . "' );";
            dBzoznam2($sql, $uri);

            header("Location: $uri");
        } else {
            $zobrazmodal = true;
        }
    }
}




ob_start();  // Začiatok definície CSS pre túto stránku 
?>
<!-- Font Awesome -->
<link rel='stylesheet' href='/plugins/fontawesome-free/css/all.min.css'>

<!-- Ionicons -->
<!-- <link rel='stylesheet' href='https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css'> -->
<link rel='stylesheet' href='/dist/css/ionicons/css/ionicons.min.css'>

<!-- DataTables -->
<link rel='stylesheet' href='/plugins/datatables/css/jquery.dataTables.min.css'>
<link rel='stylesheet' href='/plugins/datatables/css/dataTables.bootstrap4.min.css'>
<link rel='stylesheet' href='/plugins/datatables-select/css/select.bootstrap4.min.css'>
<link rel='stylesheet' href='/plugins/datatables-responsive/css/responsive.bootstrap4.min.css'>

<!-- Theme style -->
<link rel='stylesheet' href='/dist/css/adminlte.css'>

<!-- Google Font: Source Sans Pro -->
<!-- <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700' rel='stylesheet'> -->
<link rel='stylesheet' href='/dist/css/www/fonts.googleapis.css'>
<?php
$homepage->styles = ob_get_clean();  // Koniec definícií CSS




ob_start();  // Začiatok definície SKRIPTov pre túto stránku
?>
<!-- START - skripty len pre túto podstránku -->

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

<?php if ($zobrazmodal) { ?>
<script>
    $('#exampleModal').modal('show');
</script>
<?php } ?>

<script>
    $(document).ready(function() {
        var table = $('#zoznam').DataTable({
            responsive: true,
            select: 'single',
            paging: false,
            info: false,
            language: {
                sEmptyTable:     'Nie sú k dispozícii žiadne dáta',
                sInfo:           'Záznamy _START_ až _END_ z celkom _TOTAL_',
                sInfoEmpty:      'Záznamy 0 až 0 z celkom 0 ',
                sInfoFiltered:   '(vyfiltrované spomedzi _MAX_ záznamov)',
                sInfoPostFix:    '',
                sInfoThousands:  ' ',
                sLengthMenu:     'Zobraz _MENU_ záznamov',
                sLoadingRecords: 'Načítavam...',
                sProcessing:     'Spracúvam...',
                sSearch:         'Hľadať:',
                sZeroRecord:    'Nenašli sa žiadne vyhovujúce záznamy',
                oPaginate: {
                    sFirst:    'Prvá',
                    sLast:     'Posledná',
                    sNext:     'Nasledujúca',
                    sPrevious: 'Predchádzajúca'
                },
                oAria: {
                    sSortAscending:  ': aktivujte na zoradenie stĺpca vzostupne',
                    sSortDescending: ': aktivujte na zoradenie stĺpca zostupne'
                }
            },
            columnDefs: [
                {
                    targets: -2,
                    width: '15%',
                    className: 'dt-body-center'
                }              
            ],
            columns: [
                { responsivePriority: 2 },
                { responsivePriority: 1 }
    ]
        } );

        $('#UpravaDat [id^=button]').addClass('disabled');

        $('#zoznam tbody').on('click', 'tr', function() {
            if ($(this).hasClass('selected')) {
                $(this).removeClass('selected');
                $('#UpravaDat [id^=button]').addClass('disabled');
            } else {
                table.$('tr.selected').removeClass('selected');
                $(this).addClass('selected');
                $('#UpravaDat [id^=button]').removeClass('disabled');
            }
        });

        $('#novy-zaznam').click( function() {
            $(this).attr('data-toggle','modal');
            $(this).attr('data-target','#exampleModal');
        });           

        $('#UpravaDat [id^=button]').click (function() {
            
            var button = this.id;
            
            if ( table.row('.selected').length ) {
                var id = table.row('.selected').data()['DT_RowId'];
                // alert( 'Vybral si riadok ID: '+ id + ' a  stlacil si tlacidlo ' + button);
                switch (button) { 
                    case 'button-detail':
                        $('#exampleModal fieldset')[0].disabled = true;
                        $('#button-ulozit').hide();
                        $('#exampleModal').modal('show');
                        //alert('Kód pre detail');
                        break;
                    case 'button-edit': 
                        alert('Kód pre edit!');
                        break;		
                    case 'button-delete': 
                        alert('ZMAZ');
                        break;
                    default:
                        alert('Ani jedna možnosť');
                };
            };
        });


    });
</script>

<!-- END - skripty len pre túto podstránku -->

<?php
$homepage->skripty = ob_get_clean();  // Koniec SKRIPTov



// vyberovy dotaz na data
$sql = 'SELECT * FROM 30_zoznam_oblast_auditu ORDER BY LOWER(OblastAuditovania) ASC';
$data = dBzoznam($sql, $uri);

// print_r($data);




ob_start();  // Začiatok definície hlavného obsahu
?>
<div class='row justify-content-center pb-3'>
    <div id="UpravaDat" class='button-group'>
        <a ID='novy-zaznam' class='btn btn-primary'>Pridať položku</a>
        <a ID='button-detail' class='btn btn-outline-success mx-1 mx-md-2'>Detaily</a>
        <a ID='button-edit' class='btn btn-outline-warning mx-1 mx-md-2'>Editovať</a>
        <a ID='button-delete' class='btn btn-outline-danger'>Zmazať</a>
    </div>
</div>

<div class='row justify-content-center'>
    <div class='col-12 col-sm-10 col-md-9 col-lg-7' style='max-width: 600px; width:100%;'>

        <div class='card'>
            <div class='card-body p-2'>
                <table class='table table-sm hover compact' id='zoznam'>
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


<!-- Modal -->
<div class='modal fade' id='exampleModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
    <div class='modal-dialog modal-dialog-centered'>
        <div class='modal-content'>

            <form action="<?= $uri ?>" method="post">

                <div class='modal-header'>
                    <h5 class='modal-title' id='exampleModalLabel'><?= $homepage->nadpis ?> - Vytvorenie novej položky</h5>
                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>
                <div class='modal-body'>

                    <div class="register-card-body">
                    <fieldset>
                        <?php $meno_pola = 'oblast-auditu'; ?><!-- FORM - osobne cislo -->
                        <div class="form-group ">
                            <label>Názov oblasti</label>
                            <div class="input-group">
                                <input type="text" class="form-control<?= $validation_classes[$meno_pola]; ?>" value="<?= $validation_values[$meno_pola]; ?>" name="<?= $meno_pola; ?>" placeholder="Položka">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-id-card"></span>
                                    </div>
                                </div>
                                <!-- <small class="d-block w-100 mb-n2 text-muted">Osobné číslo zamestnanca</small> -->
                                <?= $validation_feedback[$meno_pola]; ?>
                            </div>
                        </div>

                        <?php $meno_pola = 'oblast-auditu-poznamka'; ?><!-- FORM - osobne cislo -->
                        <div class="form-group ">
                            <label>Poznámka</label>
                            <textarea class="form-control<?= $validation_classes[$meno_pola]; ?>" name="<?= $meno_pola; ?>"><?= $validation_values[$meno_pola]; ?> </textarea>
                            <!-- <small class="d-block w-100 mb-n2 text-muted">Osobné číslo zamestnanca</small> -->
                            <?= $validation_feedback[$meno_pola]; ?>
                        </div>
                        </fieldset>
                    </div>
                    <!-- /.card-body -->

                </div>
                <div class='modal-footer'>
                    <a href="<?= $uri ?>" type="submit" name="vzad" class="btn btn-secondary mx-1">Späť</a>
                    <button id="button-ulozit" type="submit" name="submit" class="btn btn-primary mx-1">Uložiť</button>
                </div>

            </form>

        </div>
    </div>
</div>

<?php
$homepage->content = ob_get_clean();  // Koniec hlavného obsahu
$homepage->display();  // vykreslenie stranky
?>