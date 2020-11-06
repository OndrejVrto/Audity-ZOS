<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/inc.require.php";

    $page = new PageZoznamNovy();
    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 600px;';
    $page->linkCisty = "/vlastnosti/oblasti-auditov/";

    // inicializácia konštánt formulára v prípade volania metódou GET
    $mena_vsetkych_poli = array ('oblast-auditu', 'oblast-auditu-poznamka');
    foreach ($mena_vsetkych_poli as $key => $value) {
        $val_values[$value] = $val_classes[$value] = $val_feedback[$value] = '';
    }

    if (isset($_POST['submit'])) {
        // inicializácia class Validate
        $validation = new ValidatorOblastAuditu($_POST);
        $validation->odsadenie = 8;  // odsadzuje HTML kod o 5 tabulátorov
        $result = $validation->validateForm();  // validuje formulár - !! kľúče validovaných polí musia byť v zadefinované v triede
        $val_values = $validation->validateFormGetValues();   // vracia hodnoty polí pre každý kľúč
        $val_classes = $validation->validateFormGetClasses();  // vracia triedy:  is-valid / is-invalid pre každý kľúč
        $val_feedback = $validation->validateFormGetFeedback();  // vracia správy pre každý kľúč

        // if result is TRUE (1) --> save data to db  OR  reditect page
        if ($result == 1) {
            $oblast = mysqli_real_escape_string($conn, $val_values['oblast-auditu']);
            $poznamka = mysqli_real_escape_string($conn, $val_values['oblast-auditu-poznamka']);

            $sql = "INSERT INTO `30_zoznam_oblast_auditu` 
                    (`OblastAuditovania`, `Poznamka`) 
                    VALUES 
                    ('".$oblast."', '".$poznamka."' );";

            dBzoznam2($sql);
            $uri = upravLink($_SERVER['REQUEST_URI']);
            header("Location: $uri");
            exit();
        }
    }

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>

                        <!-- FORM - Oblasť - pôvodná hodnota - HIDDEN -->
                        <input type="hidden" name="valueOld" value="">

                        <?php $pole = 'oblast-auditu'; echo PHP_EOL; ?>
                        <!-- FORM - Oblasť -->
                        <div class="form-group ">
                            <label>Názov oblasti</label>
                            <div class="input-group">
                                <input type="text" class="form-control<?= $val_classes[$pole] ?>" value="<?= $val_values[$pole] ?>" name="<?= $pole ?>" placeholder="Položka">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-id-card"></span>
                                    </div>
                                </div>
                                <?= $val_feedback[$pole].PHP_EOL ?>
                            </div>
                        </div>

                        <?php $pole = 'oblast-auditu-poznamka'; echo PHP_EOL; ?>
                        <!-- FORM - Poznámka -->
                        <div class="form-group ">
                            <label>Poznámka</label>
                            <textarea class="form-control<?= $val_classes[$pole] ?>" name="<?= $pole ?>"><?= $val_values[$pole] ?></textarea>
                            <?= $val_feedback[$pole].PHP_EOL ?>
                        </div>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky