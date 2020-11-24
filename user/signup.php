<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";
    
    $page = new \Page\PageClear();

    $v = new \Validator\Validator();
    
    if (isset($_POST['submit'])) {

        // validačné podmienky jednotlivých polí
        $v->addValidation("signup-email","req","Prosím vyplň mailovú adresu.");
        $v->addValidation("signup-email","email","Prosím vyplň správnu mailovú adresu.");

        $v->addValidation("signup-telefon","req","Prosím vyplň telefonický kontakt.");
        $v->addValidation("signup-pasword","req","Prosím vyplň nové heslo.");
        $v->addValidation("signup-pasword-repeater","req","Prosím vyplň kontrolné heslo.");

        $custom_validator = new \Validator\Login();
        $v->AddCustomValidator($custom_validator);

        // ak validacia skonci TRUE (1) --> reditect page to Index
        if ($v->validateForm()) {

            $user = $_POST['signup-osobne-cislo'];
            $Email = $_POST['signup-email'];
            $TelefonneCislo = $_POST['signup-telefon'];
            $Password_NEW = $_POST['signup-pasword'];

            $db->query('UPDATE `50_sys_users` 
                        SET `TelefonneCislo` = ?, `Email` = ? , `Password_NEW` = ? , `Datum_Inicializacie_Konta` = NOW()
                        WHERE `OsobneCislo` = ?', $TelefonneCislo, $Email, $Password_NEW, $user);

            $row = $db->query('SELECT * FROM `50_sys_users` WHERE `OsobneCislo` = ?', $user)->fetchArray();
            
            $_SESSION['userId'] = $row['OsobneCislo'];
            $_SESSION['userNameShort'] = (isset($row['Titul']) ? $row['Titul']." " : "" ) . $row['Meno'] . " " . $row['Priezvisko'];
            $_SESSION['userName'] = "[" . $row['OsobneCislo'] . "] " . $_SESSION['userNameShort'];
            $_SESSION['LEVEL'] = $row['LEVEL'];

            if ( is_null($row['AvatarFILE']) ) {
                // konto nieje aktivované kým nieje zvolený avatar - presmeruje sa na stránku avatara
                $_SESSION['LoginUser'] = $user;
                header("Location: /user/avatar");
                exit;
            } else {
                // avatar je zvolený, a už si aj prihlásený
                header("Location: /");
                exit();
            }
        }
    }

    if (isset($_SESSION['LoginUser'])) {
        $user = $_SESSION['LoginUser'];
        unset($_SESSION['LoginUser']);

        $row = $db->query('SELECT * FROM `50_sys_users` WHERE `OsobneCislo` = ?', $user )->fetchArray();
        $v->form_variables['signup-osobne-cislo'] = $user;
        $v->form_variables['signup-titul'] = $row['Titul'];
        $v->form_variables['signup-meno'] = $row['Meno'];
        $v->form_variables['signup-priezvisko'] = $row['Priezvisko'];
        $v->form_variables['signup-email'] = $row['Email'];
        $v->form_variables['signup-telefon'] = $row['TelefonneCislo'];
        $v->form_variables['signup-pasword'] = $row['Password_NEW'];
        $v->form_variables['signup-pasword-repeater'] = $row['Password_NEW'];

    } else {
        if (!isset($_POST['submit'])) {
            header("Location: /user/login");
        }
    }

ob_start();  // Začiatok definície hlavného obsahu
//print_r($_POST);
?>

<div class="register-box" style="max-width: 750px;">

    <div class="register-logo">
        <a href="/"><b>Audity</b>ŽOS</a>
    </div>

    <div class="card" >
        <div class="card-body register-card-body">
            <p class="login-box-msg">Prvotná inicializácia konta</p>

            <form action="<?= $page->link ?>" method="POST">

                <div class="form-row">

                    <!-- FORM - osobne cislo -->
                    <div class="form-group col-md-2">
                        <label>Osobné číslo</label>
                        <div class="input-group">
                            <input readonly type="text" class="form-control" value="<?= $v->getVAL('signup-osobne-cislo') ?>" name="signup-osobne-cislo">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-id-card"></span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- FORM - Titul -->
                    <div class="form-group col-md-2">        
                        <label>Titul</label>
                        <div class="input-group">
                            <input readonly type="text" class="form-control" value="<?= $v->getVAL('signup-titul') ?>" name="signup-titul">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-graduation-cap"></span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- FORM - Meno -->
                    <div class="form-group col-md-4">  
                        <label>Krstné meno</label>
                        <div class="input-group">
                            <input readonly type="text" class="form-control" value="<?= $v->getVAL('signup-meno') ?>" name="signup-meno">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- FORM - Priezvisko -->
                    <div class="form-group col-md-4"> 
                        <label>Priezvisko</label>
                        <div class="input-group">
                            <input readonly type="text" class="form-control" value="<?= $v->getVAL('signup-priezvisko') ?>" name="signup-priezvisko">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user-tie"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="form-row">

                    <?php $pole = 'signup-email'; echo PHP_EOL; ?>
                    <!-- FORM - E-mail -->
                    <div class="form-group col-md-6">
                        <label>E-mail</label>
                        <div class="input-group">
                            <input type="text" class="form-control<?= $v->getCLS($pole) ?>" value="<?= $v->getVAL($pole) ?>" name="<?= $pole; ?>" placeholder="meno@zoszv.sk">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            <?= $v->getMSG($pole) . PHP_EOL ?>
                        </div>
                    </div>

                    <?php $pole = 'signup-telefon'; echo PHP_EOL; ?>
                    <!-- FORM - telefon -->
                    <div class="form-group col-md-6">
                        <label>Mobilné telefónne číslo</label>
                        <div class="input-group">
                            <input type="text" class="form-control<?= $v->getCLS($pole) ?>" value="<?= $v->getVAL($pole) ?>" name="<?= $pole; ?>" placeholder="00421 915 123 456">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-mobile-alt"></span>
                                </div>
                            </div>
                            <!-- <small class="d-block w-100 mb-n2 text-muted">Osobné číslo zamestnanca</small> -->
                            <?= $v->getMSG($pole) . PHP_EOL ?>
                        </div>
                    </div>                     
                
                </div>

                <div class="form-row">

                    <?php $pole = 'signup-pasword'; echo PHP_EOL; ?>
                    <!-- FORM - Heslo -->
                    <div class="form-group col-md-6">
                        <label>Nové heslo</label>
                        <div class="input-group">
                            <input type="password" class="form-control<?= $v->getCLS($pole) ?>" value="<?= $v->getVAL($pole) ?>" name="<?= $pole; ?>" placeholder="Heslo">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <?= $v->getMSG($pole) . PHP_EOL ?>
                        </div>
                    </div>

                    <?php $pole = 'signup-pasword-repeater'; echo PHP_EOL;?>
                    <!-- FORM - Heslo opakované -->
                    <div class="form-group col-md-6">
                        <label>Nové heslo - kontrola</label>
                        <div class="input-group">
                            <input type="password" class="form-control<?= $v->getCLS($pole) ?>" value="<?= $v->getVAL($pole) ?>" name="<?= $pole; ?>" placeholder="Opakovať heslo">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            <?= $v->getMSG($pole) . PHP_EOL ?>
                        </div>
                    </div> 

                </div>
                
                <div class="form-row mt-4">


                    <!-- FORM - Tlacitko Submit -->
                    <div class="col-md-4 offset-md-2">                    
                        <button name="submit" type="submit" class="btn btn-block bg-gradient-warning btn-lg">Aktivovať</button>
                    </div>


                    <!-- FORM - Tlacitko Login -->                
                    <div class="col-md-4">  
                        <a href="/user/login" class="btn btn-outline-secondary btn-lg btn-block mt-2 mt-md-0">Späť na prihlásenie</a>
                    </div>
                
                </div>

            </form>

        </div>
    </div>
</div>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky