<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Edit();
    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 600px;';
    
    $v = new \Validator\Validator();

    if (isset($_POST['submit'])) {

        // validačné podmienky jednotlivých polí
        $v->addValidation("rola-audit","minlen=5","Trochu krátky popis. Použi aspoň 5 znakov.");
        $v->addValidation("rola-audit","req","Prosím vyplň toto pole.");
        $custom_validator = new \Validator\UnikatneHodnoty();
        $v->AddCustomValidator($custom_validator);

        $id = (int)$_POST['submit'];

        // ak validacia skonci TRUE (1) --> zktualizuj dáta v databáze
        if ($v->validateForm()) {
            $user = $page->userName;
            $RolaAudit = $_POST['rola-audit'];

            $db->query('UPDATE `36_zoznam_rola_pri_audite` 
                        SET `RolaAudit` = ?, `KtoVykonalZmenu` = ? 
                        WHERE `ID36` = ?', $RolaAudit, $user, $id);

            header("Location: $page->linkZoznam");
            exit();
        }
    }
    
    $pocet = 0;
    if (isset($_POST['edit'])) {
        // kontrola či je záznam použitý v iných tabuľkách. Ak áno, nedá sa editovať jeho názov.
        $id = (int)$_POST['edit'];

        $dataA = $db->query('SELECT COUNT(*) AS Pocet FROM `03_osoby_pri_audite` WHERE `ID36_zoznam_rola_pri_audite` = ?', $id )->fetchArray();
        $pocet = (int)$dataA['Pocet'];  // premenná $pocet sa použije v ternálnom operatore tých poli ktorým chcem nastaviť readonly hodnotu

        // načítanie dát o položke
        $data = $db->query('SELECT * FROM `36_zoznam_rola_pri_audite` WHERE ID36 = ?', $id)->fetchArray();
        $v->form_variables['rola-audit'] = $v->form_variables['valueOld'] = $data['RolaAudit'];

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

                        <?php $pole = 'rola-audit'; echo PHP_EOL; ?>
                        <!-- FORM - Oblasť -->
                        <div class="form-group ">
                            <label>Rola osoby pri audite</label>
                            <div class="input-group">
                                <input <?= $pocet > 0 ? 'readonly ' : 'autofocus ' ?>type="text" class="form-control<?= $v->getCLS($pole) ?>" value="<?= $v->getVAL($pole) ?>" name="<?= $pole ?>" placeholder="Položka">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-people-arrows"></span>
                                    </div>
                                </div>
                                <?= $v->getMSG($pole) . PHP_EOL ?>
                            </div>
                        </div>

<?php
    $page->content = ob_get_clean();  // Koniec hlavného obsahu

    $page->display();  // vykreslenie stranky