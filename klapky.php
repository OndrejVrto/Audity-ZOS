<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\ZoznamSkripty();
    $page->riadkov = 15;

    //!  Spustí aktualizáciu klapiek z VIS-u cez URL
    AktualizujKlapky();
    
    $row = $db->query("SELECT `PoslednaAktualizacia`
                        FROM `52_sys_cache_cron_and_clean` 
                        WHERE `NazovCACHE` = 'IMPORT Lotus Klapky';")->fetchArray();
    $verziaTime = date("d.m.Y \o H:i:s", strtotime($row['PoslednaAktualizacia']));
    $verziaDiff = $page->dateDiff($row['PoslednaAktualizacia']);

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

        $klapka = $id   = htmlspecialchars($value['Klapka']);
        $priezvisko     = htmlspecialchars($value['Priezvisko']);
        $meno           = htmlspecialchars($value['Meno']);
        $titul          = htmlspecialchars($value['Titul']);
        $kancelaria     = htmlspecialchars($value['Prevadzka']);
        $strediskoCislo = htmlspecialchars($value['Cislo_strediska']);
        $mobil          = htmlspecialchars($value['Mobil']);
        $poznamka       = htmlspecialchars($value['Poznamka']);
?>
                                <tr id='<?= $id ?>'>
                                    <td class="text-center text-bold"><?= $klapka ?></td>
                                    <td><?= $priezvisko ?></td>
                                    <td><?= $meno ?></td>
                                    <td><?= $titul ?></td>
                                    <td><?= $kancelaria ?></td>
                                    <td><?= $strediskoCislo ?></td>
                                    <td><?= $mobil ?></td>
                                    <td><?= $poznamka ?></td>
                                </tr>
<?php endforeach; ?>
                            </tbody>
                        </table>

                        <p class="small text-muted">
                            <span class="font-italic font-weight-bold">Aktualizácia z VISu:</span>
                            <?= $verziaTime ?> (<?= $verziaDiff ?>)
                        </p>
                    </div>
                </div>
<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky