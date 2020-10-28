<?php
    // Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
    spl_autoload_register(function ($class_name) {
        include $_SERVER['DOCUMENT_ROOT'] . "/include/class/class.".$class_name.'.php';
    });
    
    // založenie novej triedy na stranku
    $homepage = new PageClear('index', 1);
    
    // prepísanie hodnôt stránky ručne. Štandardne sa hodnoty načítavajú z _variables.php
    // $homepage->nadpis = 'Nadpis';

    
    // inicializácia konštánt formulára v prípade volania metódou GET
    $validation_values['login-osobne-cislo'] = $validation_classes['login-osobne-cislo'] = $validation_feedback['login-osobne-cislo'] = '';
    $validation_values['login-pasword'] = $validation_classes['login-pasword'] = $validation_feedback['login-pasword'] = '';

    $request_method = strtoupper($_SERVER['REQUEST_METHOD']);

    if ($request_method === 'GET') {
        // spustí sa ak existuje GET, teda aj pri prvom spustení
        // program na vyplnenie formulára údajmi (napr:dotaz do databazy cez najaký class)
    } elseif ($request_method === 'POST') {
        // spustí sa ak existuje POST
        if (isset($_POST['submit'])) {
            // spustí sa ak bolo stlačené tlačítko ->  name="submit"

            // inicializácia class Validate
            $validation = new ValidatorLogin($_POST);


            $validation->odsadenie = 5;  // odsadzuje HTML kod o 5 tabulátorov
            $result = $validation->validateForm();  // validuje formulár - !! kľúče validovaných polí musia byť v zadefinované v triede
            $validation_values = $validation->validateFormGetValues();   // vracia hodnoty polí pre každý kľúč
            $validation_classes = $validation->validateFormGetClasses();  // vracia triedy:  is-valid / is-invalid pre každý kľúč
            $validation_feedback = $validation->validateFormGetFeedback();  // vracia správy pre každý kľúč

            // if result is TRUE (1) --> save data to db  OR  reditect page
            if ($result == 1) {
                header("Location: ../index.php");
            }
        }
    }





    ob_start();  // Začiatok definície hlavného obsahu
?>

    <!-- login-logo -->
    <div class="login-logo">
        <a href="/"><b>Audity</b>ŽOS</a>
    </div>
    <!-- /.login-logo -->

    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Prihlás sa na začiatku práce</p>

            <form action="/pages/login" method="POST">

                <!-- FORM - osobne cislo -->
                <div class="input-group mb-3">
                    <input type="text" class="form-control<?= $validation_classes['login-osobne-cislo']; ?>" value="<?= $validation_values['login-osobne-cislo']; ?>" name="login-osobne-cislo" placeholder="Osobné číslo">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-id-card"></span>
                        </div>
                    </div>
<?= $validation_feedback['login-osobne-cislo']; ?>
                </div>

                <!-- FORM - Pasword -->
                <div class="input-group mb-3">
                    <input type="password" class="form-control<?= $validation_classes['login-pasword']; ?>" value="<?= $validation_values['login-pasword']; ?>" name="login-pasword" placeholder="Heslo">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-key"></span>
                        </div>
                    </div>
<?= $validation_feedback['login-pasword']; ?>
                </div>

                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                Zapamätaj si ma
                            </label>
                        </div>
                    </div>

                    <div class="col-4">
                        <button type="submit" name="submit" class="btn btn-primary btn-block">Prihlásiť</button>
                    </div>
                </div>
                <!-- /.row -->

            </form>

            <p class="mb-1">
                <a href="/pages/signup" class="text-center">Zaregistrovať nový účet</a>
            </p>
        </div>
        <!-- /.login-card-body -->
    </div>

<?php
    $homepage->content = ob_get_clean();  // Koniec hlavného obsahu
    $homepage->display();  // vykreslenie stranky
?>