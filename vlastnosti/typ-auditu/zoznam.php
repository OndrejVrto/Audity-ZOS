<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Zoznam();
    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 900px;';

    // vyberovy dotaz na data
    $data = $db->query('SELECT * FROM `31_zoznam_typ_auditu`, `30_zoznam_oblast_auditu` WHERE ID30_zoznam_oblast_auditu = ID30')->fetchAll();


ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>

                        <thead>

                            <tr>
                                <th style="width: 25px;" >P.č.</th>
                                <th>Norma</th>
                                <th>Rok</th>
                                <th>Skratka</th>
                                <th>Oblasť</th>
                                <th>Farba</th>
                            </tr>

                        </thead>
                        <tbody>

<?php
    $poradie = 1;
    foreach ($data as $key => $value):
        $id         = vycistiText($value['ID31']);
        $nazov      = vycistiText($value['Nazov_Norma']);
        $rok        = vycistiText($value['RokVydaniaNormy']);
        $skratka    = vycistiText($value['Skratka']);
        $farba      = vycistiText($value['Farba']);
        $referencny = vycistiText($value['ReferencnyDokument']);
        $checkbox   = vycistiText($value['PovinnostMatPlatny']); 
        $poznamka   = vycistiText($value['Poznamka']);
        $oblast     = vycistiText($value['OblastAuditovania']);

        list($r, $g, $b) = sscanf($value['Farba'], "#%02x%02x%02x");
?>
                            <tr id='<?= $id ?>'>
                                <td class="text-center"><?= $poradie ?>.</td>
                                <td class="pl-3"><?= $nazov ?></td>
                                <td class="pl-3"><?= $rok ?></td>
                                <td class="pl-3"><?= $skratka ?></td>
                                <td class="pl-3"><?= $oblast ?></td>
                                <td class="pl-3"><i class="fas fa-square-full mr-3" style="color: rgb(<?= $r ?>, <?= $g ?>, <?= $b ?>);"></i><?= $farba ?></td>
                            </tr>
<?php
        $poradie += 1;
    endforeach;
?>

                        </tbody>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

// vykreslenie stranky
$page->display();