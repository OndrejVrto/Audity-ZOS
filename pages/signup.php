<?php
    // Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
    spl_autoload_register(function ($class_name) {
        include_once  $_SERVER['DOCUMENT_ROOT'] . "/include/class/class.".$class_name.'.php';
    });
    
    // založenie novej triedy na stranku
    $homepage = new PageClear('index', 1);

    $request_method = strtoupper($_SERVER['REQUEST_METHOD']);

    if ($request_method === 'GET') {
        // spustí sa ak existuje GET, teda aj pri prvom spustení

        // inicializácia konštánt formulára v prípade volania metódou GET
        $mena_vsetkych_poli = array ('signup-osobne-cislo', 'signup-titul', 'signup-meno', 'signup-priezvisko', 'signup-email', 'signup-pasword', 'signup-pasword-repeater');
        foreach ($mena_vsetkych_poli as $key => $value) {
            $validation_values[$value] = $validation_classes[$value] = $validation_feedback[$value] = '';
        }

        // program na vyplnenie formulára údajmi z databazy ak je potrebne ...

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

                <?php $meno_pola = 'signup-osobne-cislo'; ?><!-- FORM - osobne cislo -->
                <label>Osobné číslo uživateľa</label>
                <div class="input-group form-group mb-4">
                    <input type="text" class="form-control<?= $validation_classes[$meno_pola]; ?>" value="<?= $validation_values[$meno_pola]; ?>" name="<?= $meno_pola; ?>" placeholder="Osobné číslo">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-id-card"></span>
                        </div>
                    </div>
                    <small class="d-block w-100 text-muted">Osobné číslo zamestnanca</small>
<?= $validation_feedback[$meno_pola]; ?>
                </div>
                
                <?php $meno_pola = 'signup-titul'; ?><!-- FORM - Titul -->
                <div class="input-group mb-3">
                    <input type="text" class="form-control<?= $validation_classes[$meno_pola]; ?>" value="<?= $validation_values[$meno_pola]; ?>" name="<?= $meno_pola; ?>" placeholder="Titul">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-graduation-cap"></span>
                        </div>
                    </div>
<?= $validation_feedback[$meno_pola]; ?>
                </div>

                <?php $meno_pola = 'signup-meno'; ?><!-- FORM - Meno -->
                <div class="input-group mb-3">
                    <input type="text" class="form-control<?= $validation_classes[$meno_pola]; ?>" value="<?= $validation_values[$meno_pola]; ?>" name="<?= $meno_pola; ?>" placeholder="Meno">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
<?= $validation_feedback[$meno_pola]; ?>
                </div>

                <?php $meno_pola = 'signup-priezvisko'; ?><!-- FORM - Priezvisko -->
                <div class="input-group mb-3">
                    <input type="text" class="form-control<?= $validation_classes[$meno_pola]; ?>" value="<?= $validation_values[$meno_pola]; ?>" name="<?= $meno_pola; ?>" placeholder="Priezvisko">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user-tie"></span>
                        </div>
                    </div>
<?= $validation_feedback[$meno_pola]; ?>
                </div>

                <?php $meno_pola = 'signup-email'; ?><!-- FORM - E-mail -->
                <div class="input-group mb-4">
                    <input type="text" class="form-control<?= $validation_classes[$meno_pola]; ?>" value="<?= $validation_values[$meno_pola]; ?>" name="<?= $meno_pola; ?>" placeholder="Email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
<?= $validation_feedback[$meno_pola]; ?>
                </div>

                <?php $meno_pola = 'signup-pasword'; ?><!-- FORM - Heslo -->
                <div class="input-group mb-3">
                    <input type="password" class="form-control<?= $validation_classes[$meno_pola]; ?>" value="<?= $validation_values[$meno_pola]; ?>" name="<?= $meno_pola; ?>" placeholder="Heslo">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
<?= $validation_feedback[$meno_pola]; ?>
                </div>

                <?php $meno_pola = 'signup-pasword-repeater'; ?><!-- FORM - Heslo opakované -->
                <div class="input-group mb-5">
                    <input type="password" class="form-control<?= $validation_classes[$meno_pola]; ?>" value="<?= $validation_values[$meno_pola]; ?>" name="<?= $meno_pola; ?>" placeholder="Opakovať heslo">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
<?= $validation_feedback[$meno_pola]; ?>
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

                    <div class="col-5">
                        <button name="submit" type="submit" class="btn btn-primary btn-block">Registrovať</button>
                    </div>
                </div>
            </form>

            <a href="/pages/login" class="btn btn-secondary d-block mt-3">Už som zaregistrovaný</a>
        </div>
        <!-- /.form-box -->
    </div>
    <!-- /.card -->

<?php
    $homepage->content = ob_get_clean();  // Koniec hlavného obsahu
    $homepage->display();  // vykreslenie stranky
?>