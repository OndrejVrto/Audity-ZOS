<?php
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
/*     $validation_values['login-osobne-cislo'] = $validation_classes['login-osobne-cislo'] = $validation_feedback['login-osobne-cislo'] = '';
    $validation_values['login-pasword'] = $validation_classes['login-pasword'] = $validation_feedback['login-pasword'] = ''; */

$request_method = strtoupper($_SERVER['REQUEST_METHOD']);

if ($request_method === 'GET') {
    // spustí sa ak existuje GET, teda aj pri prvom spustení
    // program na vyplnenie formulára údajmi (napr:dotaz do databazy cez najaký class)
} elseif ($request_method === 'POST') {
    // spustí sa ak existuje POST
    if (isset($_POST['submit'])) {
        // spustí sa ak bolo stlačené tlačítko ->  name="submit"

        // inicializácia class Validate
        /*          $validation = new ValidatorSignup($_POST);

            $validation->odsadenie = 5;  // odsadzuje HTML kod o 5 tabulátorov
            $result = $validation->validateForm();  // validuje formulár - !! kľúče validovaných polí musia byť v zadefinované v triede
            $validation_values = $validation->validateFormGetValues();   // vracia hodnoty polí pre každý kľúč
            $validation_classes = $validation->validateFormGetClasses();  // vracia triedy:  is-valid / is-invalid pre každý kľúč
            $validation_feedback = $validation->validateFormGetFeedback();  // vracia správy pre každý kľúč

            // if result is TRUE (1) --> save data to db  OR  reditect page
            if ($result == 1) {
                header("Location: /index.php");
            } */
    }
}




ob_start();  // Začiatok definície CSS pre túto stránku 
?>
<!-- Font Awesome -->
<link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
<!-- Ionicons -->
<!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
<link rel="stylesheet" href="/dist/css/ionicons/css/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="/dist/css/adminlte.css">
<!-- Google Font: Source Sans Pro -->
<!-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> -->
<link rel="stylesheet" href="/dist/css/www/fonts.googleapis.css">
<?php
$homepage->styles = ob_get_clean();  // Koniec definícií CSS




ob_start();  // Začiatok definície SKRIPTov pre túto stránku
?>
<!-- START - skripty len pre túto podstránku -->

<!-- jQuery -->
<script src="/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/dist/js/demo.js"></script>

<!-- END - skripty len pre túto podstránku -->
<?php
$homepage->skripty = ob_get_clean();  // Koniec SKRIPTov


// vyberovy dotaz na data
$sql = "SELECT * FROM 30_zoznam_oblast_auditu ORDER BY LOWER(OblastAuditovania) ASC";
$data = dBzoznam($sql, $uri);

// print_r($data);

ob_start();  // Začiatok definície hlavného obsahu
?>
<div class="row justify-content-center pb-3">
        <a href="<?= $uri ?>-novy" type="submit" name="novy" class="btn btn-success">Pridať položku</a>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-9 col-lg-7" style="max-width: 600px;">

        <div class="card">
            <div class="card-body p-2">
                <table class="table table-sm">
                    <thead>

                        <tr>
                            <th style="width: 15px">P.č.</th>
                            <th>Zoznam oblastí</th>
                            <th style="width: 180px"></th>
                        </tr>

                    </thead>
                    <tbody>
<?php
    $poradie = 1;
    foreach ($data as $key => $value) {
        $id = htmlspecialchars($value['ID30']);
        $oblastAuditovania = htmlspecialchars($value['OblastAuditovania']);
?>
                        <tr id="<?= $id ?>">
                            <td><?= $poradie ?>.</td>
                            <td><?= $oblastAuditovania ?></td>
                            <td>
                                <div class="btn-group">
                                    <form action="<?= $uri ?>-detail" method="post">
                                        <button type="submit" name="detail" value="<?= $id ?>" class="btn btn-xs btn-outline-info">Detaily</button>
                                    </form>
                                    <form action="<?= $uri ?>-edit" method="post" class="mx-1">
                                        <button type="submit" name="edit" value="<?= $id ?>" class="btn btn-xs btn-outline-secondary">Editovať</button>
                                    </form>
                                    <form action="<?= $uri ?>-delete" method="post">
                                        <button type="submit" name="delete" value="<?= $id ?>" class="btn btn-xs btn-outline-danger">Zmazať</button>
                                    </form>
                                </div>
                            </td>
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
$homepage->display();  // vykreslenie stranky
?>