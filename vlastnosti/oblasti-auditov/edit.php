<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Edit();
    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 600px;';

    $v = new \Validator\Validator();

    if (isset($_POST['submit'])) {

        // validačné podmienky jednotlivých polí
        $v->addValidation("oblast-auditu","minlen=3","Trochu krátky názov. Použi aspoň 3 znaky.");
        $v->addValidation("oblast-auditu","req","Prosím vyplň toto pole.");
        $custom_validator = new \Validator\UnikatneHodnoty();
        $v->AddCustomValidator($custom_validator);

        $id = (int)$_POST['submit'];

        // ak validacia skonci TRUE (1) --> zktualizuj dáta v databáze
        if ($v->validateForm()) {
            $user = $page->userName;
            $oblast = $_POST['oblast-auditu'];
            $poznamka = $_POST['oblast-auditu-poznamka'];

            $db->query('UPDATE `30_zoznam_oblast_auditu` 
                        SET `OblastAuditovania` = ?, `Poznamka` = ? , `KtoVykonalZmenu` = ? 
                        WHERE `ID30` = ?', $oblast, $poznamka, $user, $id);

            header("Location: $page->linkZoznam");
            exit();
        }
    }
    
    $pocet = 0;
    if (isset($_POST['edit'])) {
        // kontrola či je záznam použitý v iných tabuľkách. Ak áno, nedá sa editovať jeho názov.
        $id = (int)$_POST['edit'];

        $dataA = $db->query(
            'SELECT 
                (SELECT COUNT(*) FROM `31_zoznam_typ_auditu` WHERE `ID30_zoznam_oblast_auditu` = ?)
                +
                (SELECT COUNT(*) FROM `01_certifikaty` WHERE `ID30_zoznam_oblast_auditu` = ?)
            AS Pocet', $id, $id )->fetchArray();
        $pocet = (int)$dataA['Pocet'];  // premenná $pocet sa použije v ternálnom operatore tých poli ktorým chcem nastaviť readonly hodnotu

        // načítanie dát o položke
        $data = $db->query('SELECT * FROM `30_zoznam_oblast_auditu` WHERE ID30 = ?', $id)->fetchArray();
        $v->form_variables['oblast-auditu'] = $v->form_variables['valueOld'] = $data['OblastAuditovania'];
        $v->form_variables['oblast-auditu-poznamka'] = $data['Poznamka'];

    }

    $page->id = vycistiText($id);

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>
                        <div class="row mb-3">
                            <?= ($pocet > 0) ? '<p class="w-100 text-center text-danger h5">Niektoré polia položky nie je možné editovať,<br> pretože sa položka používa v prepojených tabuľkách.</p>'.PHP_EOL : '' ?>  
                        </div>

                        <?php $pole = 'valueOld'; echo PHP_EOL; ?>
                        <!-- FORM - Oblasť - pôvodná hodnota - HIDDEN -->
                        <input type="hidden" name="valueOld" value="<?= $v->getVAL($pole) ?>">

                        <?php $pole = 'oblast-auditu'; echo PHP_EOL; ?>
                        <!-- FORM - Oblasť -->
                        <div class="form-group ">
                            <label>Názov oblasti</label>
                            <div class="input-group">
                                <input <?= $pocet > 0 ? 'readonly ' : 'autofocus ' ?>type="text" class="form-control<?= $v->getCLS($pole) ?>" value="<?= $v->getVAL($pole) ?>" name="<?= $pole ?>" placeholder="Položka">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-id-card"></span>
                                    </div>
                                </div>
                                <?= $v->getMSG($pole) . PHP_EOL ?>
                            </div>
                        </div>

                        <?php $pole = 'oblast-auditu-poznamka'; echo PHP_EOL; ?>
                        <!-- FORM - Poznámka -->
                        <div class="form-group ">
                            <label>Poznámka</label>
                            <textarea class="form-control<?= $v->getCLS($pole) ?>" name="<?= $pole ?>"><?= $v->getVAL($pole) ?></textarea>
                            <?= $v->getMSG($pole) . PHP_EOL ?>
                        </div>

<?php
    $page->content = ob_get_clean();  // Koniec hlavného obsahu

    $page->display();  // vykreslenie stranky