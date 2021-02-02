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
                                <th>P.č.</thle=>
                                <th>Os.č.</th>
                                <th>Meno</th>
                                <th>Priezvisko</th>
                                <th>Titul</th;>
                                <th>Stredisko</th>
                                <th>Názov strediska</th>
                                <th>Nástup</th>
                                <th>Heslo prvotné</th>
                            </tr>

                        </thead>
                        <tbody>

<?php
    $poradie = 1;
    
    $data = $db->query("SELECT *
                        FROM `50_sys_users`
                        WHERE `ID53_sys_levels` >= 3 AND `ID50` > 4
                        ORDER BY Zamestnany_OD ASC;")->fetchAll();

    foreach ($data as $key => $value)
    {
        $osCislo = $id  = vycistiText($value['OsobneCislo']);
        $meno           = vycistiText($value['Meno']);
        $priezvisko     = vycistiText($value['Priezvisko']);
        $titul          = vycistiText($value['Titul']);
        $strediskoCislo = vycistiText($value['Stredisko']);
        $stredisko      = vycistiText($value['NazovStrediska']);
        $nastup         = vycistiText($value['Zamestnany_OD']);
        $hesloStare     = vycistiText($value['Password_OLD']);
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
                                <td><?= $hesloStare ?></td>                                
                            </tr>
<?php
        $poradie += 1;
    }
?>

                        </tbody>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky