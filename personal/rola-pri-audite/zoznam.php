<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Zoznam();
    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 600px;';

    // vyberovy dotaz na data
    //$data = $db->query('SELECT * FROM 30_zoznam_oblast_auditu ORDER BY LOWER(OblastAuditovania) ASC')->fetchAll();  // zotriedene vysledky
    $data = $db->query('SELECT * FROM 36_zoznam_rola_pri_audite')->fetchAll();

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>

                        <thead>

                            <tr>
                                <th style="width: 25px;" >P.č.</th>
                                <th>Rola pri audite</th>
                            </tr>

                        </thead>
                        <tbody>

<?php
    $poradie = 1;
    foreach ($data as $key => $value):
        $id = vycistiText($value['ID36']);
        $RolaAudit = vycistiText($value['RolaAudit']);
?>
                            <tr id='<?= $id ?>'>
                                <td class="text-center"><?= $poradie ?>.</td>
                                <td class="pl-3"><?= $RolaAudit ?></td>
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