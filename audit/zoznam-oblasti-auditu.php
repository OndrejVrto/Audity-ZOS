<?php
    // Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
    spl_autoload_register(function ($class_name) {
        include_once  $_SERVER['DOCUMENT_ROOT'] . "/include/class/class.".$class_name.'.php';
    });
    
    // založenie novej triedy na stranku
    $homepage = new Page('30_zoznam_oblast_auditu', 1);
    
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








    ob_start();  // Začiatok definície hlavného obsahu
?>
        <div class="col-md-6">

            <div class="card">
            <div class="card-header">
                <h3 class="card-title">Condensed Full Width Table</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <table class="table table-sm">
                <thead>
                    <tr>
                    <th style="width: 10px">#</th>
                    <th>Task</th>
                    <th>Progress</th>
                    <th style="width: 40px">Label</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <td>1.</td>
                    <td>Update software</td>
                    <td>
                        <div class="progress progress-xs">
                        <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                        </div>
                    </td>
                    <td><span class="badge bg-danger">55%</span></td>
                    </tr>
                    <tr>
                    <td>2.</td>
                    <td>Clean database</td>
                    <td>
                        <div class="progress progress-xs">
                        <div class="progress-bar bg-warning" style="width: 70%"></div>
                        </div>
                    </td>
                    <td><span class="badge bg-warning">70%</span></td>
                    </tr>
                    <tr>
                    <td>3.</td>
                    <td>Cron job running</td>
                    <td>
                        <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar bg-primary" style="width: 30%"></div>
                        </div>
                    </td>
                    <td><span class="badge bg-primary">30%</span></td>
                    </tr>
                    <tr>
                    <td>4.</td>
                    <td>Fix and squish bugs</td>
                    <td>
                        <div class="progress progress-xs progress-striped active">
                        <div class="progress-bar bg-success" style="width: 90%"></div>
                        </div>
                    </td>
                    <td><span class="badge bg-success">90%</span></td>
                    </tr>
                </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>

<?php
    $homepage->content = ob_get_clean();  // Koniec hlavného obsahu
    $homepage->display();  // vykreslenie stranky
?>