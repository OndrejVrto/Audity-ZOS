<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Detail();
    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 1100px;';

    $id = (int)$_POST['detail'];

    $data = $db->query('SELECT * FROM `31_zoznam_typ_auditu` WHERE ID31 = ?', $id)->fetchArray();
    
    $nazov      = htmlspecialchars($data['Nazov_Norma']);
    $rok        = htmlspecialchars($data['RokVydaniaNormy']);
    $skratka    = htmlspecialchars($data['Skratka']);
    $farba      = htmlspecialchars($data['Farba']);
    $referencny = htmlspecialchars($data['ReferencnyDokument']);
    $checkbox   = htmlspecialchars($data['PovinnostMatPlatny']); 
    $poznamka   = htmlspecialchars($data['Poznamka']);

    $dataOblast = $db->query('SELECT OblastAuditovania FROM `30_zoznam_oblast_auditu` WHERE ID30 = ?', $data['ID30_zoznam_oblast_auditu'])->fetchArray();
    $oblast     = htmlspecialchars($dataOblast['OblastAuditovania']);

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>

        <div class="row">

        <!-- FORM - Typ auditu - názov typu auditu alebo názov normy -->
        <div class="col-xl-6">
            <div class="form-group ">
                <label>Názov auditu alebo norma</label>
                <div class="input-group">
                    <input type="text" class="form-control" value="<?= $nazov ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-book"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FORM - Typ auditu - Rok vydania normy -->
        <div class="col-xl-3">
            <div class="form-group ">
                <label>Rok vydania</label>
                <div class="input-group">
                    <input type="text" class="form-control" value="<?= $rok ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-birthday-cake"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FORM - Typ auditu - skratka -->
        <div class="col-xl-3">
            <div class="form-group ">
                <label>Skratka auditu</label>
                <div class="input-group">
                    <input type="text" class="form-control" value="<?= $skratka ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-sort-amount-down"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div> <!-- /row  -->

        <div class="row">

        <!-- FORM - Typ auditu - select -->
        <div class="col-xl-4">
            <div class="form-group ">                            
                <label>Typ auditu</label>
                <div class="input-group">
                    <input type="text" class="form-control" value="<?= $oblast ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-stream"></span>
                        </div>
                    </div>                
                </div>
            </div>
        </div>

        <!-- FORM - Typ auditu - Referencny-->
        <div class="col-xl-5">
            <div class="form-group ">
                <label>Referenčný dokument</label>
                <div class="input-group">
                    <input type="text" class="form-control" value="<?= $referencny ?>">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-file-prescription"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
            // konvertuje farbu v hex zápise do RGB
            list($r, $g, $b) = sscanf($farba, "#%02x%02x%02x");
            echo PHP_EOL;
        ?>
        <!-- FORM - Typ auditu - farba -->
        <div class="col-xl-3">
            <div class="form-group">
                <label>Farba</label>
                <div class="input-group my-colorpicker2">
                    <input type="text" class="form-control" value="<?= $farba ?>">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="fas fa-square-full" style="color: rgb(<?= $r ?>, <?= $g ?>, <?= $b ?>);"></i></span>
                    </div>                                     
                </div>
            </div>
        </div>

        </div> <!-- /row  -->

        <div class="row">

        <!-- FORM - Typ auditu - Checkbox  -->
        <div class="col-xl-12">
            <div class="icheck-secondary">
                <input type="checkbox" <?php if ($checkbox == 1){ echo ' checked';} ?>>
                <label>
                    Audit musí byť udržiavaný !
                </label>
            </div>
        </div>

        <!-- FORM - Typ auditu - Poznámka -->
        <div class="col-xl-12">                        
            <div class="form-group ">
                <label>Poznámka</label>
                <textarea class="form-control"><?= $poznamka ?></textarea>
            </div>
        </div>

        </div> <!-- /row  -->

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky