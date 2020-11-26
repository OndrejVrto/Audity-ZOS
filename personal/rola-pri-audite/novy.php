<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Novy();
    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 600px;';
    
    $v = new \Validator\Validator();

    if (isset($_POST['submit'])) {
        
        // validačné podmienky jednotlivých polí
        $v->addValidation("rola-audit","minlen=5","Trochu krátky popis. Použi aspoň 5 znakov.");
        $v->addValidation("rola-audit","req","Prosím vyplň toto pole.");
        $custom_validator = new \Validator\UnikatneHodnoty();
        $v->AddCustomValidator($custom_validator);

        // ak validacia skonci TRUE --> vlož dáta do databázy
        if ($v->validateForm()) {
            $user = $page->userName;
            $RolaAudit = $_POST['rola-audit'];

            $db->query('INSERT INTO `36_zoznam_rola_pri_audite` (`RolaAudit`, `KtoVykonalZmenu`) 
                        VALUES (?,?)', $RolaAudit, $user);

            header("Location: $page->linkZoznam");
            exit();
        }
    }

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>

                        <!-- FORM - Oblasť - pôvodná hodnota - HIDDEN -->
                        <input type="hidden" name="valueOld" value="">

                        <?php $pole = 'rola-audit'; echo PHP_EOL; ?>
                        <!-- FORM - Oblasť -->
                        <div class="form-group ">
                            <label>Rola osoby pri audite</label>
                            <div class="input-group">
                                <input autofocus type="text" class="form-control<?= $v->getCLS($pole) ?>" value="<?= $v->getVAL($pole) ?>" name="<?= $pole ?>" placeholder="Položka">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-people-arrows"></span>
                                    </div>
                                </div>
                                <?= $v->getMSG($pole) . PHP_EOL ?>
                            </div>
                        </div>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu - načíta buffer do premennej

$page->display();  // vykreslenie stranky