<?php
    // Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
    spl_autoload_register(function ($class_name) {
        include  $_SERVER['DOCUMENT_ROOT'] . "/include/class/class.".$class_name.'.php';
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
            $validation = new ValidatorSignup($_POST);

            $validation->odsadenie = 5;  // odsadzuje HTML kod o 5 tabulátorov
            $result = $validation->validateForm();  // validuje formulár - !! kľúče validovaných polí musia byť v zadefinované v triede
            $validation_values = $validation->validateFormGetValues();   // vracia hodnoty polí pre každý kľúč
            $validation_classes = $validation->validateFormGetClasses();  // vracia triedy:  is-valid / is-invalid pre každý kľúč
            $validation_feedback = $validation->validateFormGetFeedback();  // vracia správy pre každý kľúč

            // if result is TRUE (1) --> save data to db  OR  reditect page
            if ($result == 1) {
                header("Location: ../pages/login.php");
            }
        }
    }



    ob_start();  // Začiatok definície hlavného obsahu
?>

    <div class="register-logo">
        <a href="/"><b>Audity</b>ŽOS</a>
    </div>

    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">Registrácia nového uživateľa</p>

            <form action="/pages/signup" method="POST">
                <div class="input-group mb-5">
                    <input type="text" class="form-control" placeholder="Osobné číslo">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-id-card"></span>
                        </div>
                    </div>
                </div>  
                
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Titul">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-graduation-cap"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Meno">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Priezvisko">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user-tie"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-5">
                    <input type="text" class="form-control" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Heslo">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-5">
                    <input type="password" class="form-control" placeholder="Opakovať heslo">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-7">
                    <div class="icheck-primary">
                            <input type="checkbox" id="agreeTerms" name="terms" value="suhlas">
                            <label for="agreeTerms">
                            Súhlasím s <a href="#">podmienkami</a>
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-5">
                        <button type="submit" class="btn btn-primary btn-block">Registrovať</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <a href="/pages/login" class="text-center">Už som zaregistrovaný</a>
        </div>
        <!-- /.form-box -->
    </div>
    <!-- /.card -->

<?php
    $homepage->content = ob_get_clean();  // Koniec hlavného obsahu
    $homepage->display();  // vykreslenie stranky
?>