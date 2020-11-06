<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/inc.require.php";

    $page = new PageZoznamEdit();
    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 600px;';
    $page->linkCisty = "/vlastnosti/oblasti-auditov/";

    // inicializácia konštánt formulára v prípade volania metódou GET
    $mena_vsetkych_poli = array ('oblast-auditu', 'oblast-auditu-poznamka', 'oblast-auditu-old');
    foreach ($mena_vsetkych_poli as $key => $value) {
        $val_values[$value] = $val_classes[$value] = $val_feedback[$value] = '';
    }

    if (isset($_POST['submit'])) {

        // inicializácia class Validate
        $validation = new ValidatorOblastAuditu($_POST, $uri);
        $validation->odsadenie = 8;  // odsadzuje HTML kod o x tabulátorov
        $result = $validation->validateForm();  // validuje formulár - !! kľúče validovaných polí musia byť v zadefinované v triede
        $val_values = $validation->validateFormGetValues();   // vracia hodnoty polí pre každý kľúč
        $val_classes = $validation->validateFormGetClasses();  // vracia triedy:  is-valid / is-invalid pre každý kľúč
        $val_feedback = $validation->validateFormGetFeedback();  // vracia správy pre každý kľúč

        $id = (int)mysqli_real_escape_string($conn, $_POST['submit']);

        // if result is TRUE (1) --> save data to db  OR  reditect page
        if ($result == 1) {
            $oblast = mysqli_real_escape_string($conn, $val_values['oblast-auditu']);
            $poznamka = mysqli_real_escape_string($conn, $val_values['oblast-auditu-poznamka']);

            $sql = "UPDATE `30_zoznam_oblast_auditu` 
                    SET `OblastAuditovania`='".$oblast."', 
                        `Poznamka`='".$poznamka."' 
                    WHERE `ID30`=".$id.";";

            dBzoznam2($sql);
            $uri = upravLink($_SERVER['REQUEST_URI']);
            header("Location: $uri");
            exit();
        }
    }
    
    $pocet = 0;
    if (isset($_POST['edit'])) {
        // kontrola či je záznam použitý v iných tabuľkách. Ak áno, nedá editovať jeho názov.
        $id = (int)mysqli_real_escape_string($conn, $_POST['edit']);
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM `31_zoznam_typ_auditu` WHERE `ID30_zoznam_oblast_auditu`= ".$id.")
                    +
                    (SELECT COUNT(*) FROM `01_certifikaty` WHERE `ID30_zoznam_oblast_auditu`= ".$id.")
                AS Pocet;";
        $pocetArray = dBzoznam($sql, $uri);
        // vytiahnutie počtu z výsledku dotazu
        $pocet = (int)$pocetArray[0]['Pocet'];

        // načítanie dát
        $sql = "SELECT * FROM 30_zoznam_oblast_auditu WHERE ID30='".$id."'; ";
        $data = dBzoznam($sql);
        $val_values['oblast-auditu'] = $val_values['valueOld'] = $data[0]['OblastAuditovania'];
        $val_values['oblast-auditu-poznamka'] = $data[0]['Poznamka'];
    }

    $page->id = htmlspecialchars($id);

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>

                        <?php $pole = 'valueOld'; echo PHP_EOL; ?>
                        <!-- FORM - Oblasť - pôvodná hodnota - HIDDEN -->
                        <input type="hidden" name="valueOld" value="<?= $val_values[$pole] ?>">

                        <?php $pole = 'oblast-auditu'; echo PHP_EOL; ?>
                        <!-- FORM - Oblasť -->
                        <div class="form-group ">
                            <label>Názov oblasti</label>
                            <div class="input-group">
                                <input <?= $pocet > 0 ? 'readonly ' : '' ?>type="text" class="form-control<?= $val_classes[$pole] ?>" value="<?= $val_values[$pole] ?>" name="<?= $pole ?>" placeholder="Položka">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-id-card"></span>
                                    </div>
                                </div>
                                <?= ($pocet > 0) ? '<small class="d-block w-100 mb-n2 text-muted">Názov nieje možné editovať, pretože sa už používa v iných tabuľkách.</small>'.PHP_EOL : '' ?>
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