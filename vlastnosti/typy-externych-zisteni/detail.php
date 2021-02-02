<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Detail();
    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 600px;';
    
    $id = (int)$_POST['detail'];

    $data = $db->query('SELECT * FROM `33_zoznam_typ_externych_zisteni` WHERE ID33 = ?', $id)->fetchArray();

    $NazovExternehoZistenia = vycistiText($data['NazovExternehoZistenia']);
    $poznamka = vycistiText($data['Poznamka']);

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>


                        <!-- FORM - Oblasť -->
                        <div class="form-group ">
                            <label>Názov externeho zistenia</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="<?= $NazovExternehoZistenia ?>" placeholder="Položka">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-exclamation-triangle"></span>
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