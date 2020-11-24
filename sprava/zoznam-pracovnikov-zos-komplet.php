<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Zoznam();
    $page->bodyClassExtended = 'col-12';
    $page->bodyWidthExtended = 'max-width: 1400px;';
    $page->zobrazitTlacitka = false;
    $page->pagination = true;
    $page->info = true;
    $page->riadkov = 50;

    $data = $db->query('SELECT * FROM `51_sys_users_maxmast_uoscis` ORDER BY ondate ASC')->fetchAll();

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
    foreach ($data as $key => $value):
        $osCislo = $id  = htmlspecialchars($value['ucislo']);
        $meno           = htmlspecialchars($value['umeno']);
        $priezvisko     = htmlspecialchars($value['upriezv']);
        $titul          = htmlspecialchars($value['utitul']);
        $strediskoCislo = htmlspecialchars($value['ustred']);
        $stredisko      = htmlspecialchars($value['nazstred']);
        $firma          = htmlspecialchars($value['firma']);
        $nastup         = htmlspecialchars($value['ondate']);
        $vystup         = htmlspecialchars($value['offdate']);
?>
                            <tr id='<?= $id ?>'>
                                <td><?= $poradie ?>.</td>
                                <td><?= $osCislo ?></td>
                                <td><?= $meno ?></td>
                                <td><?= $priezvisko ?></td>
                                <td><?= $titul ?></td>
                                <td><?= $strediskoCislo ?></td>
                                <td><?= $stredisko ?></td>
                                <td><?= $firma ?></td>
                                <td><?= $nastup ?></td>
                                <td><?= $vystup ?></td>
                            </tr>
<?php
        $poradie += 1;
    endforeach;
?>

                        </tbody>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>
    <script>

    </script>
<?php
$page->skriptySpecial = ob_get_clean();

$page->display();  // vykreslenie stranky