<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\PageClear();
    $page->classBodySpecial = "hold-transition lockscreen";

    $v = new \Validator\Validator();

    if (isset($_POST['submit'])) {

        // validačné podmienky jednotlivých polí
        $v->addValidation("login-pasword","req","");
        $custom_validator = new \Validator\Login();
        $v->AddCustomValidator($custom_validator);

        // TODO   Kontrola hesiel nefunguje

        // ak validacia skonci TRUE (1) --> reditect page to Index
        if ($v->validateForm()) {

            $user =  $page->LoginUser;

            $row = $db->query('SELECT * FROM `50_sys_users` WHERE `OsobneCislo` = ?', $user)->fetchArray();
            $userID = $row['ID50'];
            $_SESSION['Login'] = 'true';
            $_SESSION['LoginUser'] = $user;
            $_SESSION['userNameShort'] = (isset($row['Titul']) ? $row['Titul'] . " " : "") . $row['Meno'] . " " . $row['Priezvisko'];
            $_SESSION['userName'] = "[" . $row['OsobneCislo'] . "] " . $_SESSION['userNameShort'];
            $_SESSION['LEVEL'] = $row['ID53_sys_levels'];

            // konto nieje aktivované - presmeruje sa na stránku aktivácie
            if (is_null($row['Datum_Inicializacie_Konta'])) {
                header("Location: /user/signup");
                exit;
            }

            // konto je aktivované - pokračuj v prihlásení
            // ochrana pred útokom Session Fixation (tip č. 825 z knihy 1001 tipu a triku pro PHP)
            // todo vypol som to, lebo mi vytvára TMP súbory a nemaže ich - treba urobiť cleaner a potom to zapnúť.
            //session_regenerate_id();

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

            // konto nemá prideleného avatara - presmeruje sa na stránku avatar
            if (is_null($row['AvatarFILE'])) {
                header("Location: /user/avatar");
                exit;
            }

            header("Location: /");
            exit;
        }
    }

ob_start();  // Začiatok definície hlavného obsahu
?>

    <div class="lockscreen-wrapper">

        <div class="lockscreen-logo">
            <a href="/"><b>Audity</b>ŽOS</a>
        </div>

        <div class="lockscreen-name"><?= $page->userNameShort ?></div>

        <!-- START LOCK SCREEN ITEM -->
        <div class="lockscreen-item">
            <!-- lockscreen image -->
            <div class="lockscreen-image">
                <img src="<?= $page->suborAvatara ?>" alt="User Image Avatar">
            </div>
            <!-- /.lockscreen-image -->

            <!-- lockscreen credentials (contains the form) -->
            <form class="lockscreen-credentials" action="<?= $page->link ?>" method="POST">
                <div class="input-group">
                    <input type="hidden" name="login-osobne-cislo" value="<?= $page->LoginUser ?>">
                    <input type="password" name="login-pasword" class="form-control" placeholder="Heslo">

                    <div class="input-group-append">
                        <button type="submit" name="submit" class="btn"><i class="fas fa-arrow-right text-muted"></i></button>
                    </div>
                </div>
            </form>
            <!-- /.lockscreen credentials -->

        </div>
        <!-- /.lockscreen-item -->
        <div class="help-block text-center">
            Po <?= TIME_OUT ?> minútach nečinnosti ste boli odhlásený.<br>
            Vložte svoje heslo pre pokračovanie v práci<br>
            <a href="/user/login">alebo sa prihláste ako iný uživateľ.</a>
        </div>
        <div class="lockscreen-footer text-center">
            Copyright &copy; 2020-2021 <b><a href="#" class="text-black">Ing. Ondrej VRŤO, IWE</a></b><br>
            All rights reserved
        </div>
    </div>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky