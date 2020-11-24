<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Novy();
    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 1100px;';

    $v = new \Validator\Validator();

    if (isset($_POST['submit'])) {
        
        // validačné podmienky jednotlivých polí
        $v->addValidation("typ-auditu--nazov","minlen=5","Trochu krátky názov. Použi aspoň 5 znakov.");
        $v->addValidation("typ-auditu--nazov","req","Nejaký názov je nutné uviesť.");

        $v->addValidation("typ-auditu--rok","lessthan=2200","Rok menší ako 2200.");
        $v->addValidation("typ-auditu--rok","greaterthan=1900","Rok väčší ako 1900.");
        $v->addValidation("typ-auditu--rok","regexp=/^([0-9]{4}+)$/","Musí byť uvedené 4 ciferné číslo.");

        $v->addValidation("typ-auditu--skratka","regexp=/^[0-9a-zA-Z-]{1,12}$/","Maximálne 12 znakov. Len písmená bez diakritiky, číslice a pomlčka.");
        $v->addValidation("typ-auditu--skratka","req","Skratka sa používa často. Niečo krátke vymysli.");

        $v->addValidation("typ-auditu--oblast","req","Prosím vyber niektorú hodnotu so zoznamu.");

        $custom_validator = new \Validator\Zoznam();
        $v->AddCustomValidator($custom_validator);

        // ak validacia skonci TRUE --> vlož dáta do databázy
        if ($v->validateForm()) {
            $user = $_SESSION['userName'];
            
            $oblast     = $_POST['typ-auditu--oblast'];
            $nazov      = $_POST['typ-auditu--nazov'];
            $skratka    = $_POST['typ-auditu--skratka'];
            
            if ($_POST['typ-auditu--rok']) { $rok = $_POST['typ-auditu--rok']; } else { $rok = null; }
            if ($_POST['typ-auditu--farba']) { $farba = $_POST['typ-auditu--farba']; } else { $farba = null; }
            if ($_POST['typ-auditu--ReferencnyDokument']) { $referencny = $_POST['typ-auditu--ReferencnyDokument']; } else { $referencny = null; }
            if ($_POST['typ-auditu--poznamka']) { $poznamka = $_POST['typ-auditu--poznamka']; } else { $poznamka = null; }

            if ($_POST['typ-auditu--checkbox']) { $checkbox = 1; } else { $checkbox = 0; } ;
            
            //print_r($_POST);
            $db->query('INSERT INTO `31_zoznam_typ_auditu` 
                        (`ID30_zoznam_oblast_auditu`, `Nazov_Norma`, `RokVydaniaNormy`,
                        `Skratka`, `Farba`, `ReferencnyDokument`,
                        `PovinnostMatPlatny`, `Poznamka`, `KtoVykonalZmenu`)
                        VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)', 
                        $oblast, $nazov, $rok,
                        $skratka, $farba, $referencny,
                        $checkbox, $poznamka, $user);

            header("Location: $page->linkZoznam");
            exit();
        }
    }

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>

                        <!-- FORM - Typ auditu - pôvodná hodnota - HIDDEN -->
                        <input type="hidden" name="valueOld" value="">

                        <div class="row">

                            <?php $pole = 'typ-auditu--nazov'; echo PHP_EOL; ?>
                            <!-- FORM - Typ auditu - názov typu auditu alebo názov normy -->
                            <div class="col-xl-6">
                                <div class="form-group ">
                                    <label>Názov auditu alebo norma</label>
                                    <div class="input-group">
                                        <input autofocus type="text" class="form-control<?=  $v->getCLS($pole) ?>" value="<?= $v->getVAL($pole) ?>" name="<?= $pole ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-book"></span>
                                            </div>
                                        </div>
                                        <?= $v->getMSG($pole) . PHP_EOL ?>
                                    </div>
                                </div>
                            </div>

                            <?php $pole = 'typ-auditu--rok'; echo PHP_EOL; ?>
                            <!-- FORM - Typ auditu - Rok vydania normy -->
                            <div class="col-xl-3">
                                <div class="form-group ">
                                    <label>Rok vydania</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control<?=  $v->getCLS($pole) ?>" value="<?= $v->getVAL($pole) ?>" name="<?= $pole ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-birthday-cake"></span>
                                            </div>
                                        </div>
                                        <?= $v->getMSG($pole) . PHP_EOL ?>
                                    </div>
                                </div>
                            </div>

                            <?php $pole = 'typ-auditu--skratka'; echo PHP_EOL; ?>
                            <!-- FORM - Typ auditu - skratka -->
                            <div class="col-xl-3">
                                <div class="form-group ">
                                    <label>Skratka auditu</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control<?=  $v->getCLS($pole) ?>" value="<?= $v->getVAL($pole) ?>" name="<?= $pole ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-sort-amount-down"></span>
                                            </div>
                                        </div>
                                        <?= $v->getMSG($pole) . PHP_EOL ?>
                                    </div>
                                </div>
                            </div>

                        </div> <!-- /row  -->

                        <div class="row">

                            <?php $pole = 'typ-auditu--oblast'; echo PHP_EOL; ?>
                            <!-- FORM - Typ auditu - select -->
                            <div class="col-xl-4">
                                <div class="form-group ">                            
                                    <label for="<?= $pole ?>">Typ auditu</label>
                                    <select class="form-control<?=  $v->getCLS($pole) ?>" name="<?= $pole ?>" style="background-position: right 2rem center;" required>
                                        <option selected disabled></option><?php

                                        $data = $db->query('SELECT ID30, OblastAuditovania FROM `30_zoznam_oblast_auditu` ORDER BY LOWER(`OblastAuditovania`) ASC')->fetchAll();
                                        $selected = $v->getVAL($pole);

                                        echo PHP_EOL;
                                        foreach ($data as $hodnota) {
                                            $id = $hodnota['ID30'];
                                            $val = htmlspecialchars($hodnota['OblastAuditovania']);
                                            echo TAB10 . '<option value="' . $id . '"' . (($id == $selected) ? ' selected' : '') . '>' . $val . '</option>' . PHP_EOL;
                                        }

                                        ?>
                                    </select>
                                    <?= $v->getMSG($pole) . PHP_EOL ?>
                                </div>
                            </div>

                            <?php $pole = 'typ-auditu--ReferencnyDokument'; echo PHP_EOL; ?>
                            <!-- FORM - Typ auditu - Referencny-->
                            <div class="col-xl-5">
                                <div class="form-group ">
                                    <label>Referenčný dokument</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control<?=  $v->getCLS($pole) ?>" value="<?= $v->getVAL($pole) ?>" name="<?= $pole ?>">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <span class="fas fa-file-prescription"></span>
                                            </div>
                                        </div>
                                        <?= $v->getMSG($pole) . PHP_EOL ?>
                                    </div>
                                </div>
                            </div>

                            <?php $pole = 'typ-auditu--farba'; 
                                // konvertuje farbu v hex zápise do RGB
                                list($r, $g, $b) = sscanf($v->getVAL($pole), "#%02x%02x%02x");
                                echo PHP_EOL;
                            ?>
                            <!-- FORM - Typ auditu - farba -->
                            <div class="col-xl-3">
                                <div class="form-group">
                                    <label>Farba</label>
                                    <div class="input-group my-colorpicker2">
                                        <input type="text" class="form-control<?=  $v->getCLS($pole) ?>" value="<?= $v->getVAL($pole) ?>" name="<?= $pole ?>">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-square-full" style="color: rgb(<?= $r ?>, <?= $g ?>, <?= $b ?>);"></i></span>
                                        </div>
                                        <?= $v->getMSG($pole) . PHP_EOL ?>                                        
                                    </div>
                                </div>
                            </div>

                        </div> <!-- /row  -->

                        <div class="row">

                            <?php $pole = 'typ-auditu--checkbox'; echo PHP_EOL; ?>
                            <!-- FORM - Typ auditu - Checkbox  -->
                            <div class="col-xl-12">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="<?= $pole ?>" name="<?= $pole ?>" <?php if ($v->getVAL($pole)){ echo ' checked';} ?>>
                                    <label for="<?= $pole ?>">
                                        Audit musí byť udržiavaný !
                                    </label>
                                </div>
                            </div>

                            <?php $pole = 'typ-auditu--poznamka'; echo PHP_EOL; ?>
                            <!-- FORM - Typ auditu - Poznámka -->
                            <div class="col-xl-12">                        
                                <div class="form-group ">
                                    <label>Poznámka</label>
                                    <textarea class="form-control<?=  $v->getCLS($pole) ?>" name="<?= $pole ?>"><?= $v->getVAL($pole) ?></textarea>
                                    <?= $v->getMSG($pole) . PHP_EOL ?>
                                </div>
                            </div>

                        </div> <!-- /row  -->

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>
    <!-- bootstrap color picker -->
    <script src="/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <script>
        //color picker with addon
        $('.my-colorpicker2').colorpicker()

        $('.my-colorpicker2').on('colorpickerChange', function(event) {
        $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
        });
    </script>

<?php
$page->skriptySpecial = ob_get_clean();  // Koniec hlavného obsahu

$page->stylySpecial = '<link rel="stylesheet" href="/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">';

$page->display();  // vykreslenie stranky