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
                            <tr id='<?= htmlspecialchars($value['ucislo']) ?>'>
                                <td><?= $poradie ?>.</td>
                                <td><?= htmlspecialchars($value['ucislo']) ?></td>
                                <td><?= htmlspecialchars($value['umeno']) ?></td>
                                <td><?= htmlspecialchars($value['upriezv']) ?></td>
                                <td><?= htmlspecialchars($value['utitul']) ?></td>
                                <td><?= htmlspecialchars($value['ustred']) ?></td>
                                <td><?= htmlspecialchars($value['nazstred']) ?></td>
                                <td><?= htmlspecialchars($value['firma']) ?></td>
                                <td><?= htmlspecialchars($value['ondate']) ?></td>
                                <td><?= htmlspecialchars($value['offdate']) ?></td>
                            </tr>
<?php
        $poradie += 1;
    }
?>

                        </tbody>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky