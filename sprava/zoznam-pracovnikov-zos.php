<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Zoznam();
    $page->bodyClassExtended = 'col-12';
    $page->bodyWidthExtended = 'max-width: 1200px;';
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
                                <th style="width: 120px;" >Meno</th>
                                <th style="width: 160px;" >Priezvisko</th>
                                <th style="width: 30px;" >Titul</th>
                                <th style="width: 100px;" >Stredisko</th>
                                <th>Názov strediska</th>
                                <th style="width: 60px;" >Nástup</th>
                            </tr>

                        </thead>
                        <tbody>

<?php
    $poradie = 1;
    
    $data = $db->query("SELECT `ucislo`, `utitul`, `umeno`, `upriezv`, `ustred`, `nazstred`, `ondate`
    FROM `51_sys_users_maxmast_uoscis`
    WHERE `offdate` > NOW() AND `firma` LIKE '%ŽOS%' AND `ucislo` REGEXP '^[0-9]{4,5}$' AND `umeno` IS NOT NULL 
    ORDER BY ondate ASC;")->fetchAll();

    foreach ($data as $key => $value)
    {
        $osCislo = $id  = htmlspecialchars($value['ucislo']);
        $meno           = htmlspecialchars($value['umeno']);
        $priezvisko     = htmlspecialchars($value['upriezv']);
        $titul          = htmlspecialchars($value['utitul']);
        $strediskoCislo = htmlspecialchars($value['ustred']);
        $stredisko      = htmlspecialchars($value['nazstred']);
        $nastup         = htmlspecialchars($value['ondate']);
?>
                            <tr id='<?= $id ?>'>
                                <td><?= $poradie ?>.</td>
                                <td><?= $osCislo ?></td>
                                <td><?= $meno ?></td>
                                <td><?= $priezvisko ?></td>
                                <td><?= $titul ?></td>
                                <td><?= $strediskoCislo ?></td>
                                <td><?= $stredisko ?></td>
                                <td><?= $nastup ?></td>
                            </tr>
<?php
        $poradie += 1;
    }
?>

                        </tbody>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky