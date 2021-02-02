<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Zoznam();
    $page->bodyClassExtended = 'col-12';
    $page->bodyWidthExtended = 'max-width: 1400px;';
    $page->zobrazitTlacitka = false;
    $page->pagination = true;
    $page->info = true;
    $page->riadkov = 25;

    ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>

                        <thead>

                            <tr>
                                <th style="width: 30px;" >P.č.</th>
                                <th style="width: 40px;" >Os.č.</th>
                                <th style="width: 100px;" >Meno</th>
                                <th style="width: 130px;" >Priezvisko</th>
                                <th style="width: 30px;" >Titul</th>
                                <th style="width: 80px;" >Stredisko</th>
                                <th>Názov strediska</th>
                                <th style="width: 110px;" >Firma</th>
                                <th style="width: 60px;" >Nástup</th>
                                <th style="width: 60px;" >Výstup</th>                                
                            </tr>

                        </thead>
                        <tbody>

<?php
    $poradie = 1;
    $data = $db->query('SELECT * FROM `51_sys_users_maxmast_uoscis` ORDER BY ondate ASC')->fetchAll();
    foreach ($data as $key => $value) 
    {
?>
                            <tr id='<?= vycistiText($value['ucislo']) ?>'>
                                <td><?= $poradie ?>.</td>
                                <td><?= vycistiText($value['ucislo']) ?></td>
                                <td><?= vycistiText($value['umeno']) ?></td>
                                <td><?= vycistiText($value['upriezv']) ?></td>
                                <td><?= vycistiText($value['utitul']) ?></td>
                                <td><?= vycistiText($value['ustred']) ?></td>
                                <td><?= vycistiText($value['nazstred']) ?></td>
                                <td><?= vycistiText($value['firma']) ?></td>
                                <td><?= vycistiText($value['ondate']) ?></td>
                                <td><?= vycistiText($value['offdate']) ?></td>
                            </tr>
<?php
        $poradie += 1;
    }
?>

                        </tbody>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky