<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Edit();
    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 600px;';
    
    $v = new \Validator\Validator();

    if (isset($_POST['submit'])) {

        // validačné podmienky jednotlivých polí
        $v->addValidation("typ-externych-zisteni","minlen=5","Trochu krátky popis. Použi aspoň 5 znakov.");
        $v->addValidation("typ-externych-zisteni","req","Prosím vyplň toto pole.");
        $custom_validator = new \Validator\UnikatneHodnoty();
        $v->AddCustomValidator($custom_validator);

        $id = (int)$_POST['submit'];

        // ak validacia skonci TRUE (1) --> zktualizuj dáta v databáze
        if ($v->validateForm()) {
            $user = $page->userName;
            $NazovExternehoZistenia = $_POST['typ-externych-zisteni'];
            $Poznamka = $_POST['typ-externych-zisteni--poznamka'];

            $db->query('UPDATE `33_zoznam_typ_externych_zisteni` 
                        SET `NazovExternehoZistenia` = ?, `Poznamka` = ? , `KtoVykonalZmenu` = ? 
                        WHERE `ID33` = ?', $NazovExternehoZistenia, $Poznamka, $user, $id);
            
            $search = new Vyhladavanie($page->userName);
            $search->Tabulka_Cislo = 33;
            $search->Tabulka_ID = $id;
            $search->Link = $page->linkCisty . "detail";
            $search->url = TRUE;
            $search->Titulok = "Typ externého zistenia";

            $search->Tabulka_Stlpec = 'NazovExternehoZistenia';
            $search->Hodnota = $NazovExternehoZistenia;
            $search->updateSearch();
            $search->Tabulka_Stlpec = 'Poznamka';
            $search->Hodnota = $Poznamka;
            $search->Nasobitel = 9;
            $search->updateSearch();

            header("Location: $page->linkZoznam");
            exit();
        }
    }
    
    $pocet = 0;
    if (isset($_POST['edit'])) {
        // kontrola či je záznam použitý v iných tabuľkách. Ak áno, nedá sa editovať jeho názov.
        $id = (int)$_POST['edit'];

        $dataA = $db->query('SELECT COUNT(*) AS Pocet FROM `04_zistenia` WHERE `ID33_zoznam_typ_externych_zisteni` = ?', $id )->fetchArray();
        $pocet = (int)$dataA['Pocet'];  // premenná $pocet sa použije v ternálnom operatore tých poli ktorým chcem nastaviť readonly hodnotu

        // načítanie dát o položke
        $data = $db->query('SELECT * FROM `33_zoznam_typ_externych_zisteni` WHERE ID33 = ?', $id)->fetchArray();
        $v->form_variables['typ-externych-zisteni'] = $v->form_variables['valueOld'] = $data['NazovExternehoZistenia'];
        $v->form_variables['typ-externych-zisteni--poznamka'] = $data['Poznamka'];

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

                        <?php $pole = 'typ-externych-zisteni'; echo PHP_EOL; ?>
                        <!-- FORM - Oblasť -->
                        <div class="form-group ">
                            <label>Názov externeho zistenia</label>
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

                        <?php $pole = 'typ-externych-zisteni--poznamka'; echo PHP_EOL; ?>
                        <!-- FORM - Poznámka -->
                        <div class="form-group ">
                            <label>Poznámka</label>
                            <textarea class="form-control<?= $v->getCLS($pole) ?>" name="<?= $pole ?>"><?= $v->getVAL($pole) ?></textarea>
                            <?= $v->getMSG($pole) . PHP_EOL ?>
                        </div>

<?php
    $page->content = ob_get_clean();  // Koniec hlavného obsahu

    $page->display();  // vykreslenie stranky