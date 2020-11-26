<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Zoznam();
    $page->bodyClassExtended = 'col-12 col-lg-10';
    //$page->bodyWidthExtended = 'max-width: 1500px;';
    $page->zobrazitTlacitka = false;

    // vyberovy dotaz na data
    //$data = $db->query('SELECT * FROM 30_zoznam_oblast_auditu ORDER BY LOWER(OblastAuditovania) ASC')->fetchAll();  // zotriedene vysledky
    $data = $db->query('SELECT * FROM 32_zoznam_typ_zisteni')->fetchAll();

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>

                        <thead>

                            <tr>
                                <th style="width: 25px;">P.č.</th>
                                <th style="width: 150px;">Typ zistenia</th>
                                <th>Poznámka</th>
                                <th style="width: 150px;">Potreba opatrenia</th>
                            </tr>

                        </thead>
                        <tbody>

<?php
    $poradie = 1;
    foreach ($data as $key => $value):
        $id = htmlspecialchars($value['ID32']);
        $NazovZistenia = htmlspecialchars($value['NazovZistenia']);
        $Poznamka = htmlspecialchars($value['Poznamka']);
        $PotrebaNapravnehoOpatrenia = ($value['JePovinneNapravneOpatrenie'] == 1) ? 'Áno' : 'Nie';
?>
                            <tr id='<?= $id ?>'>
                                <td class="text-center"><?= $poradie ?>.</td>
                                <td class="pl-3"><?= $NazovZistenia ?></td>
                                <td class="pl-3"><?= $Poznamka ?></td>
                                <td class="text-center"><?= $PotrebaNapravnehoOpatrenia ?></td>
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