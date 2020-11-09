<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/inc.require.php";
    
    $page = new PageClear();

    $v = new Validator();

    if (isset($_POST['submit'])) {

        // validačné podmienky jednotlivých polí
        $v->addValidation("login-osobne-cislo","req","Prosím vyplň svoje osobné číslo zamestnanca.");
        $custom_validator = new ValidatorLogin();
        $v->AddCustomValidator($custom_validator);

        // ak validacia skonci TRUE (1) --> reditect page to Index
        if ($v->validateForm()) {
            header("Location: /");
            exit();
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

                <div class="row">
                    <div class="col-7">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                Zapamätaj si ma
                            </label>
                        </div>
                    </div>

                    <div class="col-5">
                        <button type="submit" name="submit" class="btn btn-block bg-gradient-primary btn-lg">Prihlásiť</button>
                    </div>
                </div>

            </form>

            <p class="mb-1">
                <a href="/signup" class="btn btn-outline-secondary d-block mt-3">Zaregistrovať nový účet</a>
            </p>
        </div>

    </div>
</div>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky