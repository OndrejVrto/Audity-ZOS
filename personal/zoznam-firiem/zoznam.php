<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Zoznam();
    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 900px;';

    // vyberovy dotaz na data
    $data = $db->query('SELECT * FROM `20_vstup_firma_auditora`')->fetchAll();

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>

                        <thead>

                            <tr>
                                <th style="width: 25px;" >P.č.</th>
                                <th>Názov firmy</th>
                                <th>AdresaFirmy</th>
                                <th>Telefón</th>
                                <th>E-mail</th>
                                <th>WWW</th>
                            </tr>

                        </thead>
                        <tbody>

<?php
    $poradie = 1;
    foreach ($data as $key => $value):
        $id         = vycistiText($value['ID20']);
        $nazov      = vycistiText($value['NazovFirmy']);
        $adresa     = vycistiText($value['AdresaFirmy']);
        $telefon    = vycistiText($value['OficialnyTelKontakt']);
        $mail       = vycistiText($value['OficialnyEmail']);
        $www        = vycistiText($value['wwwStranka']);
?>
                            <tr id='<?= $id ?>'>
                                <td class="text-center"><?= $poradie ?>.</td>
                                <td class="pl-3"><?= $nazov ?></td>
                                <td class="pl-3"><?= $adresa ?></td>
                                <td class="pl-3"><?= $telefon ?></td>
                                <td class="pl-3"><?= $mail ?></td>
                                <td class="pl-3"><?= $www ?></td>
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