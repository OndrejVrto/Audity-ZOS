<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\ZoznamSkripty();
    $page->zobrazitBublinky = false;
    $page->riadkov = 15;

    //!  Spustí aktualizáciu klapiek z VIS-u cez URL
    AktualizujKlapky();
    
    $row = $db->query("SELECT `PoslednaAktualizacia`
                        FROM `52_sys_cache_cron_and_clean` 
                        WHERE `NazovCACHE` = 'IMPORT Lotus Klapky';")->fetchArray();
    $verziaTime = date("d.m.Y \o H:i:s", strtotime($row['PoslednaAktualizacia']));

ob_start();  // Začiatok definície hlavného obsahu
?>
                <div class="row justify-content-center">
                    <div>

                        <table class='table table-sm hover compact' id='tabulka'>
                            <thead>
                                <tr>
                                    <th>Klapka</th>
                                    <th>Priezvisko</th>
                                    <th>Meno</th>
                                    <th>Titul</th>
                                    <th>Kancelária</th>
                                    <th>Stredisko</th>
                                    <th>Mobil</th>
                                    <th>Poznámka</th>
                                </tr>
                            </thead>
                            <tbody>
<?php
    $data = $db->query("SELECT *
                        FROM `54_sys_klapky`
                        WHERE `Tarif` IS NULL 
                        ORDER BY `Klapka` ASC;")->fetchAll();

    foreach ($data as $key => $value):

        $klapka         = vycistiText($value['Klapka']);
        $priezvisko     = vycistiText($value['Priezvisko']);
        $meno           = vycistiText($value['Meno']);
        $titul          = vycistiText($value['Titul']);
        $kancelaria     = vycistiText($value['Prevadzka']);
        $strediskoCislo = vycistiText($value['Cislo_strediska']);
        $mobil          = vycistiText($value['Mobil']);
        $poznamka       = vycistiText($value['Poznamka']);

        if (!empty($strediskoCislo)) {
            $strediskoCislo_Cast1 = substr($strediskoCislo, 0, 3);
            $strediskoCislo_Cast2 = substr($strediskoCislo, 3);
            $strediskoCisloTEXT = $strediskoCislo_Cast1 . '<span class="ml-1">' . $strediskoCislo_Cast2 . '</span>';
        }
        
        $mobilTEXT = "";
        if (!empty($mobil)) {
            $mobil_Cast1 = substr($mobil,-50,-6);
            $mobil_Cast2 = substr($mobil,-6, -3);
            $mobil_Cast3 = substr($mobil,-3);
            if (strlen($mobil_Cast1) > 4) {
                $mobil_Cast1 = substr($mobil,-9,-6);
                $mobil_Cast0 = substr($mobil,-50,-9);
                $mobilTEXT = $mobil_Cast0 . '<span class="ml-1">';
            }
            $mobilTEXT .= $mobil_Cast1 . '<span class="ml-1">' . $mobil_Cast2 . '</span><span class="ml-1">' . $mobil_Cast3 . '</span>';
        }
?>
                                <tr>
                                    <td class="text-center text-bold"><?= $klapka ?></td>
                                    <td><?= $priezvisko ?></td>
                                    <td><?= $meno ?></td>
                                    <td><?= $titul ?></td>
                                    <td><?= $kancelaria ?></td>
                                    <td><?= $strediskoCisloTEXT  ?></td>
                                    <td><?= $mobilTEXT ?></td>
                                    <td><?= $poznamka ?></td>
                                </tr>
<?php endforeach; ?>
                            </tbody>
                        </table>

                        <p class="small text-muted">
                            <span class="font-italic font-weight-bold">Aktualizácia z VISu:</span>
                            <?= $verziaTime ?>
                        </p>
                    </div>
                </div>
<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky