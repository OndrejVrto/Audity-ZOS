<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";
    
    $page = new \Page\PageClear();
    
    $v = new \Validator\Validator();

    if (isset($_POST['submit'])) {

        // validačné podmienky jednotlivých polí
        $v->addValidation("login-osobne-cislo","req","Prosím vyplň svoje osobné číslo zamestnanca.");
        $custom_validator = new \Validator\Login();
        $v->AddCustomValidator($custom_validator);

        // ak validacia skonci TRUE (1) --> reditect page to Index
        if ($v->validateForm()) {
            
            $user = $_POST['login-osobne-cislo'];
            $row = $db->query('SELECT * FROM `50_sys_users` WHERE `OsobneCislo` = ?', $user )->fetchArray();
            $userID = $row['ID50'];
            $_SESSION['LoginUser'] = $user;

            // konto nieje aktivované - presmeruje sa na stránku aktivácie
            if (is_null($row['Datum_Inicializacie_Konta'])) {
                header("Location: /signup");
                exit;
            }

            // konto je aktivované - pokračuj v prihlásení
            session_regenerate_id(); // ochrana pred útokom Session Fixation (tip č. 825 z knihy 1001 tipu a triku pro PHP)
            
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip = $_SERVER['REMOTE_ADDR'];
            }

            $prehliadac = $_SERVER['HTTP_USER_AGENT'];

            // logovanie prihlásenia
            $db->query('INSERT INTO `60_log_prihlasenie` (`ID50_sys_users`, `DatumCas`, `IP`, `Prehliadac`) VALUES (?, now(), ?, ? )', $userID, $ip, $prehliadac);

            $_SESSION['userId'] = $row['OsobneCislo'];
            $_SESSION['userNameShort'] = (isset($row['Titul_OLD']) ? $row['Titul_OLD']." " : "" ) . $row['Meno_OLD'] . " " . $row['Priezvisko_OLD'];
            $_SESSION['userName'] = "[" . $row['OsobneCislo'] . "] " . $_SESSION['userNameShort'];
            $_SESSION['RolaREAD'] = $row['RolaREAD'];
            $_SESSION['RolaEDIT'] = $row['RolaEDIT'];
            $_SESSION['RolaADMIN'] = $row['RolaADMIN'];


            // konto nemá prideleného avatara - presmeruje sa na stránku avatar
            if ( $row['Avatar'] === 0 ) {
                header("Location: /avatar");
                exit;
            }

            header("Location: /");
            exit;
        }
    }


ob_start();  // Začiatok definície hlavného obsahu
?>

<div class="login-box">

    <div class="login-logo">
        <a href="/"><b>Audity</b>ŽOS</a>
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Prihlás sa na začiatku práce</p>

            <form action="<?= $page->link ?>" method="POST">

                <?php $pole = 'login-osobne-cislo'; echo PHP_EOL; ?>
                <!-- FORM - osobne cislo -->
                <div class="input-group mb-3">
                    <input type="text" class="form-control<?= $v->getCLS($pole) ?>" value="<?= $v->getVAL($pole) ?>" name="login-osobne-cislo" placeholder="Osobné číslo">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-id-card"></span>
                        </div>
                    </div>
                    <?= $v->getMSG($pole) . PHP_EOL ?>
                </div>

                <?php $pole = 'login-pasword'; echo PHP_EOL; ?>
                <!-- FORM - Pasword -->
                <div class="input-group mb-3">
                    <input type="password" class="form-control<?= $v->getCLS($pole) ?>" value="" name="login-pasword" placeholder="Heslo">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-key"></span>
                        </div>
                    </div>
                    <?= $v->getMSG($pole) . PHP_EOL ?>
                </div>

                <button type="submit" name="submit" class="btn btn-block bg-gradient-warning btn-lg">Prihlásiť</button>

            </form>

            <p class="mb-1">
                <a href="/password-reset" class="d-block text-center mt-2 text-muted">Obnoviť heslo</a>
            </p>
        </div>

    </div>
</div>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky