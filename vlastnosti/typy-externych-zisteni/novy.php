<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Novy();
    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 600px;';
    
    $v = new \Validator\Validator();

    if (isset($_POST['submit'])) {
        
        // validačné podmienky jednotlivých polí
        $v->addValidation("typ-externych-zisteni","minlen=5","Trochu krátky popis. Použi aspoň 5 znakov.");
        $v->addValidation("typ-externych-zisteni","req","Prosím vyplň toto pole.");
        $custom_validator = new \Validator\UnikatneHodnoty();
        $v->AddCustomValidator($custom_validator);

        // ak validacia skonci TRUE --> vlož dáta do databázy
        if ($v->validateForm()) {
            $user = $page->userName;
            $NazovExternehoZistenia = $_POST['typ-externych-zisteni'];
            $Poznamka = $_POST['typ-externych-zisteni--poznamka'];

            $db->query('INSERT INTO `33_zoznam_typ_externych_zisteni` (`NazovExternehoZistenia`, `Poznamka`, `KtoVykonalZmenu`) 
                        VALUES (?,?,?)', $NazovExternehoZistenia, $Poznamka, $user);

            $id = $db->lastInsertID();

            $search = new Vyhladavanie($page->userName);
            $search->Tabulka_Cislo = 33;
            $search->Tabulka_ID = $id;
            $search->Link = $page->linkCisty . "detail";
            $search->url = TRUE;
            $search->Titulok = "Typ externého zistenia";

            $search->Tabulka_Stlpec = 'NazovExternehoZistenia';
            $search->Hodnota = $NazovExternehoZistenia;
            $search->insertSearch();
            $search->Tabulka_Stlpec = 'Poznamka';
            $search->Hodnota = $Poznamka;
            $search->Nasobitel = 9;
            $search->insertSearch();

            header("Location: $page->linkZoznam");
            exit();
        }
    }

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>

                        <!-- FORM - Oblasť - pôvodná hodnota - HIDDEN -->
                        <input type="hidden" name="valueOld" value="">

                        <?php $pole = 'typ-externych-zisteni'; echo PHP_EOL; ?>
                        <!-- FORM - Oblasť -->
                        <div class="form-group ">
                            <label>Názov externeho zistenia</label>
                            <div class="input-group">
                                <input autofocus type="text" class="form-control<?= $v->getCLS($pole) ?>" value="<?= $v->getVAL($pole) ?>" name="<?= $pole ?>" placeholder="Položka">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-exclamation-triangle"></span>
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
$page->content = ob_get_clean();  // Koniec hlavného obsahu - načíta buffer do premennej

$page->display();  // vykreslenie stranky