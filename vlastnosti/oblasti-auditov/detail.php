<?php
    // Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
    spl_autoload_register(function ($class_name) {
        include_once  $_SERVER['DOCUMENT_ROOT'] . "/include/class/class." . $class_name . '.php';
    });
    require $_SERVER['DOCUMENT_ROOT'] . "/include/inc.dBconnect.php";

    $uri = "/vlastnosti/oblasti-auditov/zoznam";

    $page = new PageZoznamDetail($uri);

    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 600px;';
    $page->linkCisty = "/vlastnosti/oblasti-auditov/";

    $id = (int)mysqli_real_escape_string($conn, $_POST['detail']);

    // vyberovy dotaz na data
    $sql = "SELECT * FROM 30_zoznam_oblast_auditu WHERE ID30='".$id."'; ";
    $data = dBzoznam($sql, $uri);
    
    $oblast = htmlspecialchars($data[0]['OblastAuditovania']);
    $poznamka = htmlspecialchars($data[0]['Poznamka']);

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>


                        <!-- FORM - Oblasť -->
                        <div class="form-group ">
                            <label>Názov oblasti</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="<?= $oblast ?>" placeholder="Položka">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-id-card"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                        <!-- FORM - Poznámka -->
                        <div class="form-group ">
                            <label>Poznámka</label>
                            <textarea class="form-control"><?= $poznamka ?></textarea>
                        </div>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky