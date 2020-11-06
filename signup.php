<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/inc.require.php";
    
    $page = new PageClear();

        if (isset($_POST['submit'])) {
            // spustí sa ak bolo stlačené tlačítko ->  name="submit"

            // inicializácia class Validate
            $validation = new ValidatorSignup($_POST);

            $validation->odsadenie = 7;  // odsadzuje HTML kod o 5 tabulátorov
            $result = $validation->validateForm();  // validuje formulár - !! kľúče validovaných polí musia byť v zadefinované v triede
            $val_values = $validation->validateFormGetValues();   // vracia hodnoty polí pre každý kľúč
            $val_classes = $validation->validateFormGetClasses();  // vracia triedy:  is-valid / is-invalid pre každý kľúč
            $val_feedback = $validation->validateFormGetFeedback();  // vracia správy pre každý kľúč

            // ak validacia skonci TRUE (1) --> reditect page to Login
            // TODO: rovno po zaregistrovaní ostať prihlásený a presun na index.php ?
            if ($result == 1) {
                header("Location: /login");
                exit();
            }
        } else {
            // inicializácia konštánt formulára v prípade volania metódou GET
            $mena_vsetkych_poli = array ('signup-osobne-cislo', 'signup-avatar',  'signup-telefon', 'signup-titul', 'signup-meno', 'signup-priezvisko', 'signup-email', 'signup-pasword', 'signup-pasword-repeater', 'signup-checkbox');
            foreach ($mena_vsetkych_poli as $key => $value) {
                $val_values[$value] = $val_classes[$value] = $val_feedback[$value] = '';
            }
        }


ob_start();  // Začiatok definície hlavného obsahu
?>

<div class="register-box" style="max-width: 950px;">

    <div class="register-logo">
        <a href="/"><b>Audity</b>ŽOS</a>
    </div>

    <div class="card" >
        <div class="card-body register-card-body">
            <p class="login-box-msg">Registrácia nového uživateľa</p>

            <form action="<?= $page->link ?>" enctype="multipart/form-data" method="POST">

            <div class="form-row">

                    <?php $pole = 'signup-osobne-cislo'; echo PHP_EOL; ?>
                    <!-- FORM - osobne cislo -->
                    <div class="form-group col-md-4">
                        <label>Osobné číslo uživateľa</label>
                        <div class="input-group">
                            <input type="text" class="form-control<?= $val_classes[$pole]; ?>" value="<?= $val_values[$pole]; ?>" name="<?= $pole; ?>" placeholder="Osobné číslo">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-id-card"></span>
                                </div>
                            </div>
                            <!-- <small class="d-block w-100 mb-n2 text-muted">Osobné číslo zamestnanca</small> -->
                            <?= $val_feedback[$pole].PHP_EOL ?>
                        </div>
                    </div>

                    <?php $pole = 'signup-avatar'; echo PHP_EOL; ?>
                    <!-- FORM - Avatar -->
                    <div class="form-group col-md-8">
                        <label>Obrázok alebo fotka</label>
                        <div class="input-group">
                            <input type="file" class="custom-file-input<?= $val_classes[$pole]; ?>" id="inputFileAvatar" value="<?= $val_values[$pole]; ?>" name="<?= $pole; ?>">
                            <label class="custom-file-label text-secondary" for="inputFileAvatar" data-browse="Vložiť obrázok" value="<?= $val_values[$pole]; ?>">Vlož si obrázok avatara</label>
                            <?= $val_feedback[$pole].PHP_EOL ?>
                        </div>
                    </div>

                </div>

                <div class="form-row">

                    <?php $pole = 'signup-titul'; echo PHP_EOL; ?>
                    <!-- FORM - Titul -->
                    <div class="form-group col-md-2">        
                        <label>Titul</label>
                        <div class="input-group">
                            <input type="text" class="form-control<?= $val_classes[$pole]; ?>" value="<?= $val_values[$pole]; ?>" name="<?= $pole; ?>" placeholder="Titul">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-graduation-cap"></span>
                                </div>
                            </div>
                            <?= $val_feedback[$pole].PHP_EOL ?>
                        </div>
                    </div>

                    <?php $pole = 'signup-meno'; echo PHP_EOL; ?>
                    <!-- FORM - Meno -->
                    <div class="form-group col-md-5">  
                        <label>Krstné meno</label>
                        <div class="input-group">
                            <input type="text" class="form-control<?= $val_classes[$pole]; ?>" value="<?= $val_values[$pole]; ?>" name="<?= $pole; ?>" placeholder="Meno">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                            <?= $val_feedback[$pole].PHP_EOL ?>
                        </div>
                    </div>

                    <?php $pole = 'signup-priezvisko'; echo PHP_EOL; ?>
                    <!-- FORM - Priezvisko -->
                    <div class="form-group col-md-5"> 
                        <label>Priezvisko</label>
                        <div class="input-group">
                            <input type="text" class="form-control<?= $val_classes[$pole]; ?>" value="<?= $val_values[$pole]; ?>" name="<?= $pole; ?>" placeholder="Priezvisko">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user-tie"></span>
                                </div>
                            </div>
                        </div>
                        <?= $val_feedback[$pole].PHP_EOL ?>
                    </div>

                </div>

                <div class="form-row">

                    <?php $pole = 'signup-email'; echo PHP_EOL; ?>
                    <!-- FORM - E-mail -->
                    <div class="form-group col-md-6">
                        <label>E-mail</label>
                        <div class="input-group">
                            <input type="text" class="form-control<?= $val_classes[$pole]; ?>" value="<?= $val_values[$pole]; ?>" name="<?= $pole; ?>" placeholder="meno@zoszv.sk">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            <?= $val_feedback[$pole].PHP_EOL ?>
                        </div>
                    </div>

                    <?php $pole = 'signup-telefon'; echo PHP_EOL; ?>
                    <!-- FORM - telefon -->
                    <div class="form-group col-md-6">
                        <label>Mobilné telefónne číslo</label>
                        <div class="input-group">
                            <input type="text" class="form-control<?= $val_classes[$pole]; ?>" value="<?= $val_values[$pole]; ?>" name="<?= $pole; ?>" placeholder="00421 915 123 456">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-mobile-alt"></span>
                                </div>
                            </div>
                            <!-- <small class="d-block w-100 mb-n2 text-muted">Osobné číslo zamestnanca</small> -->
                            <?= $val_feedback[$pole].PHP_EOL ?>
                        </div>
                    </div>                     
                
                </div>

                <div class="form-row">

                    <?php $pole = 'signup-pasword'; echo PHP_EOL; ?>
                    <!-- FORM - Heslo -->
                    <div class="form-group col-md-6">
                        <label>Heslo</label>
                        <div class="input-group">
                            <input type="password" class="form-control<?= $val_classes[$pole]; ?>" value="<?= $val_values[$pole]; ?>" name="<?= $pole; ?>" placeholder="Heslo">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <?= $val_feedback[$pole].PHP_EOL ?>
                        </div>
                    </div>

                    <?php $pole = 'signup-pasword-repeater'; echo PHP_EOL;?>
                    <!-- FORM - Heslo opakované -->
                    <div class="form-group col-md-6">
                        <label>Kontrolné heslo</label>
                        <div class="input-group">
                            <input type="password" class="form-control<?= $val_classes[$pole]; ?>" value="<?= $val_values[$pole]; ?>" name="<?= $pole; ?>" placeholder="Opakovať heslo">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <?= $val_feedback[$pole].PHP_EOL ?>
                        </div>
                    </div> 

                </div>
                
                <div class="form-row mt-4">

                    <?php $pole = 'signup-checkbox'; echo PHP_EOL;?>
                    <!-- FORM - CheckBox podmienky -->
                    <div class="col-xl-3">
                        <div class="input-group mb-4 icheck-primary">
                            <input type="checkbox" class="form-check-input<?= $val_classes[$pole]; ?>" value="Súhlasím s podmienkami." id="agreeTerms" name="<?= $pole; ?>" <?php if (!empty($val_values[$pole])) { echo 'checked';} ?> >
                            <label class="" for="agreeTerms">
                                Súhlasím s <a href="#">podmienkami</a>
                            </label>
                            <?= $val_feedback[$pole].PHP_EOL ?>
                        </div>
                    </div>


                    <!-- FORM - Tlacitko Submit -->
                    <div class="col-xl-5">                    
                        <button name="submit" type="submit" class="btn btn-block bg-gradient-primary btn-lg">Registrovať</button>
                    </div>


                    <!-- FORM - Tlacitko Login -->                
                    <div class="col-xl-4">  
                        <a href="/login" class="btn btn-outline-secondary btn-lg btn-block mt-2 mt-xl-0">Už som zaregistrovaný</a>
                    </div>
                
                </div>

            </form>

        </div>
    </div>
</div>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky