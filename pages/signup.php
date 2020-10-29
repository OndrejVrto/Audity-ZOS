<?php
    // Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
    spl_autoload_register(function ($class_name) {
        include_once  $_SERVER['DOCUMENT_ROOT'] . "/include/class/class.".$class_name.'.php';
    });
    //print_r($_POST);
    // založenie novej triedy na stranku
    $homepage = new PageClear('index', 1);

    $request_method = strtoupper($_SERVER['REQUEST_METHOD']);
/* 
    print_r($_FILES);
    print_r($_POST); */

    if ($request_method === 'GET') {
        // spustí sa ak existuje GET, teda aj pri prvom spustení

        // inicializácia konštánt formulára v prípade volania metódou GET
        $mena_vsetkych_poli = array ('signup-osobne-cislo', 'signup-avatar',  'signup-telefon', 'signup-titul', 'signup-meno', 'signup-priezvisko', 'signup-email', 'signup-pasword', 'signup-pasword-repeater', 'signup-checkbox');
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

            $validation->odsadenie = 7;  // odsadzuje HTML kod o 5 tabulátorov
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
<div class="register-box" style="width: 60rem;">
    <div class="register-logo">
        <a href="/"><b>Audity</b>ŽOS</a>
    </div>

    <div class="card" >
        <div class="card-body register-card-body">
            <p class="login-box-msg">Registrácia nového uživateľa</p>

            <form action="/pages/signup" enctype="multipart/form-data" action="__URL__" method="POST">
                <div class="form-row">

                    <?php $meno_pola = 'signup-osobne-cislo'; ?><!-- FORM - osobne cislo -->
                    <div class="form-group col-md-4">
                        <label>Osobné číslo uživateľa</label>
                        <div class="input-group">
                            <input type="text" class="form-control<?= $validation_classes[$meno_pola]; ?>" value="<?= $validation_values[$meno_pola]; ?>" name="<?= $meno_pola; ?>" placeholder="Osobné číslo">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-id-card"></span>
                                </div>
                            </div>
                            <!-- <small class="d-block w-100 mb-n2 text-muted">Osobné číslo zamestnanca</small> -->
<?= $validation_feedback[$meno_pola]; ?>
                        </div>
                    </div>

                    <?php $meno_pola = 'signup-avatar'; ?><!-- FORM - Avatar -->
                    <div class="form-group col-md-8">
                        <label>Obrázok alebo fotka</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input<?= $validation_classes[$meno_pola]; ?>" id="inputFileAvatar" value="<?= $validation_values[$meno_pola]; ?>" name="<?= $meno_pola; ?>">
                                <label class="custom-file-label text-secondary" for="inputFileAvatar" data-browse="Vložiť obrázok" value="<?= $validation_values[$meno_pola]; ?>">Vlož si obrázok avatara</label>
                            </div>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button">Vyber</button>
                            </div>
<?= $validation_feedback[$meno_pola]; ?>
                        </div>
                    </div>

                </div>

                <div class="form-row">

                    <?php $meno_pola = 'signup-titul'; ?><!-- FORM - Titul -->
                    <div class="form-group col-md-2">        
                        <label>Titul</label>
                        <div class="input-group">
                            <input type="text" class="form-control<?= $validation_classes[$meno_pola]; ?>" value="<?= $validation_values[$meno_pola]; ?>" name="<?= $meno_pola; ?>" placeholder="Titul">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-graduation-cap"></span>
                                </div>
                            </div>
<?= $validation_feedback[$meno_pola]; ?>
                        </div>
                    </div>

                    <?php $meno_pola = 'signup-meno'; ?><!-- FORM - Meno -->
                    <div class="form-group col-md-5">  
                        <label>Krstné meno</label>
                        <div class="input-group">
                            <input type="text" class="form-control<?= $validation_classes[$meno_pola]; ?>" value="<?= $validation_values[$meno_pola]; ?>" name="<?= $meno_pola; ?>" placeholder="Meno">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
<?= $validation_feedback[$meno_pola]; ?>
                        </div>
                    </div>

                    <?php $meno_pola = 'signup-priezvisko'; ?><!-- FORM - Priezvisko -->
                    <div class="form-group col-md-5"> 
                        <label>Priezvisko</label>
                        <div class="input-group">
                            <input type="text" class="form-control<?= $validation_classes[$meno_pola]; ?>" value="<?= $validation_values[$meno_pola]; ?>" name="<?= $meno_pola; ?>" placeholder="Priezvisko">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user-tie"></span>
                                </div>
                            </div>
                        </div>
<?= $validation_feedback[$meno_pola]; ?>
                    </div>

                </div>

                <div class="form-row">

                    <?php $meno_pola = 'signup-email'; ?><!-- FORM - E-mail -->
                    <div class="form-group col-md-6">
                        <label>E-mail</label>
                        <div class="input-group">
                            <input type="text" class="form-control<?= $validation_classes[$meno_pola]; ?>" value="<?= $validation_values[$meno_pola]; ?>" name="<?= $meno_pola; ?>" placeholder="meno@zoszv.sk">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
<?= $validation_feedback[$meno_pola]; ?>
                        </div>
                    </div>

                    <?php $meno_pola = 'signup-telefon'; ?><!-- FORM - telefon -->
                    <div class="form-group col-md-6">
                        <label>Mobilné telefónne číslo</label>
                        <div class="input-group">
                            <input type="text" class="form-control<?= $validation_classes[$meno_pola]; ?>" value="<?= $validation_values[$meno_pola]; ?>" name="<?= $meno_pola; ?>" placeholder="00421 915 123 456">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-mobile-alt"></span>
                                </div>
                            </div>
                            <!-- <small class="d-block w-100 mb-n2 text-muted">Osobné číslo zamestnanca</small> -->
<?= $validation_feedback[$meno_pola]; ?>
                        </div>
                    </div>                     
                
                </div>

                <div class="form-row">

                    <?php $meno_pola = 'signup-pasword'; ?><!-- FORM - Heslo -->
                    <div class="form-group col-md-6">
                        <label>Heslo</label>
                        <div class="input-group">
                            <input type="password" class="form-control<?= $validation_classes[$meno_pola]; ?>" value="<?= $validation_values[$meno_pola]; ?>" name="<?= $meno_pola; ?>" placeholder="Heslo">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
<?= $validation_feedback[$meno_pola]; ?>
                        </div>
                    </div>

                    <?php $meno_pola = 'signup-pasword-repeater'; ?><!-- FORM - Heslo opakované -->
                    <div class="form-group col-md-6">
                        <label>Kontrolné heslo</label>
                        <div class="input-group">
                            <input type="password" class="form-control<?= $validation_classes[$meno_pola]; ?>" value="<?= $validation_values[$meno_pola]; ?>" name="<?= $meno_pola; ?>" placeholder="Opakovať heslo">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
<?= $validation_feedback[$meno_pola]; ?>
                        </div>
                    </div> 

                </div>
                
                <div class="form-row mt-4">

                    <?php $meno_pola = 'signup-checkbox'; ?><!-- FORM - CheckBox podmienky -->
                    <div class="col-md-3">
                        <div class="input-group mb-4 icheck-primary">
                            <input type="checkbox" class="form-check-input<?= $validation_classes[$meno_pola]; ?>" value="Súhlasím s podmienkami." id="agreeTerms" name="<?= $meno_pola; ?>" <?php if (!empty($validation_values[$meno_pola])) { echo 'checked';} ?> >
                            <label class="" for="agreeTerms">
                                Súhlasím s <a href="#">podmienkami</a>
                            </label>
<?= $validation_feedback[$meno_pola]; ?>
                        </div>
                    </div>

                    <!-- FORM - Tlacitko Submit -->
                    <div class="col-md-6">                    
                        <button name="submit" type="submit" class="btn btn-primary btn-lg btn-block">Registrovať</button>
                    </div>
                    <!-- FORM - Tlacitko Login -->                
                    <div class="col-md-3">  
                        <a href="/pages/login" class="btn btn-secondary btn-lg btn-block">Už som zaregistrovaný</a>
                    </div>
                
                </div>

            </form>

        </div>
        <!-- /.form-box -->
    </div>
    <!-- /.card -->
</div>
<!-- /.register-box -->
<?php
    $homepage->content = ob_get_clean();  // Koniec hlavného obsahu
    $homepage->display();  // vykreslenie stranky
?>