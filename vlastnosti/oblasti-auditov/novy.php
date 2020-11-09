<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/inc.require.php";

    $page = new PageZoznamNovy();
    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 600px;';

    $v = new Validator();

    if (isset($_POST['submit'])) {
        
        // validačné podmienky jednotlivých polí
        $v->addValidation("oblast-auditu","minlen=3","Trochu krátky názov. Použi aspoň 3 znaky.");
        $v->addValidation("oblast-auditu","req","Prosím vyplň toto pole.");
        $custom_validator = new ValidatorZoznamy();
        $v->AddCustomValidator($custom_validator);

        // ak validacia skonci TRUE --> vlož dáta do databázy
        if ($v->validateForm()) {
            $oblast = $_POST['oblast-auditu'];
            $poznamka = $_POST['oblast-auditu-poznamka'];

            $db->query('INSERT INTO `30_zoznam_oblast_auditu` (`OblastAuditovania`, `Poznamka`) VALUES (?,?)', $oblast, $poznamka);

            header("Location: $page->linkZoznam");
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
                                <input type="text" class="form-control<?= $v->getCLS($pole) ?>" value="<?= $v->getVAL($pole) ?>" name="<?= $pole ?>" placeholder="Položka">
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
$page->content = ob_get_clean();  // Koniec hlavného obsahu - načíta buffer do premennej

$page->display();  // vykreslenie stranky