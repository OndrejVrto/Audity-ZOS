<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Detail();
    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 600px;';
    
    $id = (int)$_POST['detail'];

    $data = $db->query('SELECT * FROM `38_zoznam_typ_prilohy` WHERE `ID38` = ?', $id)->fetchArray();

    $TypPrilohy = vycistiText($data['TypPrilohy']);

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>


                        <!-- FORM - Oblasť -->
                        <div class="form-group ">
                            <label>Typ Prílohy</label>
                            <div class="input-group">
                                <input type="text" class="form-control" value="<?= $TypPrilohy ?>" placeholder="Položka">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-folder-open"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky