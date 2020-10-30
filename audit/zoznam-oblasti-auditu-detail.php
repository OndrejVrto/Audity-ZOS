<?php
//  print_r($_POST);

// Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
spl_autoload_register(function ($class_name) {
    include_once  $_SERVER['DOCUMENT_ROOT'] . "/include/class/class." . $class_name . '.php';
});

// založenie novej triedy na stranku
$homepage = new Page('30_zoznam_oblast_auditu', 1);
$uri = "/audit/zoznam-oblasti-auditu";

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

    } else {
        // vyberovy dotaz na data
        $sql = "SELECT * FROM 30_zoznam_oblast_auditu WHERE ID30='" . $_POST['detail'] . "'; ";
        $data = dBzoznam($sql, $uri);
        $validation_values['oblast-auditu'] = $data[0]['OblastAuditovania'];
        //print_r($data);
    }
}


ob_start();  // Začiatok definície hlavného obsahu
?>

<div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-9 col-lg-7" style="max-width: 600px;">

        <form>
        <fieldset disabled>
            <div class="card" >
                <div class="card-header">
                    <?= $homepage->nadpis ?> - Vytvorenie novej položky
                </div>
                <div class="card-body register-card-body">

                <?php $meno_pola = 'oblast-auditu'; ?><!-- FORM - osobne cislo -->
                    <div class="form-group ">
                        <label>Názov oblasti</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="<?= $validation_values[$meno_pola]; ?>" placeholder="Položka">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-id-card"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php $meno_pola = 'oblast-auditu-poznamka'; ?><!-- FORM - osobne cislo -->
                    <div class="form-group ">
                        <label>Poznámka</label>
                        <textarea class="form-control"><?= $validation_values[$meno_pola]; ?> </textarea>
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
                <div class="row justify-content-center">
                    <a href="<?= $uri ?>" type="submit" name="vzad" class="btn btn-secondary mx-1">Späť</a>
                </div>
            </div>
            <!-- /.card -->
        </fieldset>            
        </form>
        </div>
    </div>
    <!-- /.register-box -->

<?php
$homepage->content = ob_get_clean();  // Koniec hlavného obsahu
$homepage->display();  // vykreslenie stranky
?>