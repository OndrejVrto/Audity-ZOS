<?php
//  print_r($_POST);

// Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
spl_autoload_register(function ($class_name) {
    include_once  $_SERVER['DOCUMENT_ROOT'] . "/include/class/class." . $class_name . '.php';
});

// založenie novej triedy na stranku
$homepage = new Page('/audit/zoznam-oblasti-auditu-ajax', 1);
$uri = "/audit/zoznam-oblasti-auditu";

// prepísanie hodnôt stránky ručne. Štandardne sa hodnoty načítavajú z _variables.php
// $homepage->nadpis = 'Nadpis';


// inicializácia konštánt formulára v prípade volania metódou GET
/* $mena_vsetkych_poli = array ('oblast-auditu', 'oblast-auditu-poznamka');
foreach ($mena_vsetkych_poli as $key => $value) {
    $validation_values[$value] = $validation_classes[$value] = $validation_feedback[$value] = '';
} */

$request_method = strtoupper($_SERVER['REQUEST_METHOD']);

if ($request_method === 'GET') {
    // spustí sa ak existuje GET, teda aj pri prvom spustení
    // program na vyplnenie formulára údajmi (napr:dotaz do databazy cez najaký class)
} elseif ($request_method === 'POST') {
    // spustí sa ak existuje POST
    if (isset($_POST['submit'])) {
        // spustí sa ak bolo stlačené tlačítko ->  name="submit"

/*         // inicializácia class Validate
        $validation = new ValidatorOblastAuditu($_POST);

        $validation->odsadenie = 5;  // odsadzuje HTML kod o 5 tabulátorov
        $result = $validation->validateForm();  // validuje formulár - !! kľúče validovaných polí musia byť v zadefinované v triede
        $validation_values = $validation->validateFormGetValues();   // vracia hodnoty polí pre každý kľúč
        $validation_classes = $validation->validateFormGetClasses();  // vracia triedy:  is-valid / is-invalid pre každý kľúč
        $validation_feedback = $validation->validateFormGetFeedback();  // vracia správy pre každý kľúč

        // if result is TRUE (1) --> save data to db  OR  reditect page
        if ($result == 1) {

            
            //$sql = "INSERT INTO `30_zoznam_oblast_auditu` (`OblastAuditovania`) VALUES ('" . $validation_values['oblast-auditu'] . "');";
            
            $sql = "UPDATE `30_zoznam_oblast_auditu` SET `OblastAuditovania`='" . $validation_values['oblast-auditu'] . "' WHERE `ID30`=" . $_POST['submit'] . ";";
            dBzoznam2($sql, $uri);

            header("Location: $uri"); 
        } */
        
        $sql = "DELETE FROM `30_zoznam_oblast_auditu` WHERE `ID30`=" . $_POST['submit'] . ";";
        echo $sql;
        dBzoznam2($sql, $uri);

        header("Location: $uri");       
        exit();
    } else {

    }
}


ob_start();  // Začiatok definície hlavného obsahu
?>

<div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-9 col-lg-7" style="max-width: 600px;">

        <form action="<?= $uri ?>-delete" method="post">
            <div class="card" >
                <div class="card-header">
                    <?= $homepage->nadpis ?> - Zmazanie položky
                </div>
                <div class="card-body register-card-body">

                <?php $meno_pola = 'oblast-auditu'; ?><!-- FORM - osobne cislo -->
                    <div class="form-group ">
                        <label>Názov oblasti</label>
                        <div class="input-group">
                            Zmazať položku <?= htmlspecialchars($_POST['delete']); ?> ?
                        </div>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
            <div class="row justify-content-center">
                    <a href="<?= $uri ?>" type="submit" name="vzad" class="btn btn-secondary mx-1">Späť</a>
                    <button type="submit" name="submit" value="<?= htmlspecialchars($_POST['delete']); ?>" class="btn btn-primary mx-1">Zmazať</button>
                </div>
            <!-- /.card -->
        </form>
    </div>
</div>
    <!-- /.register-box -->

<?php
$homepage->content = ob_get_clean();  // Koniec hlavného obsahu
$homepage->display();  // vykreslenie stranky
?>