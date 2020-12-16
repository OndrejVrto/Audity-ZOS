<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Zoznam();
    $page->bodyClassExtended = 'col-12';
    $page->bodyWidthExtended = 'max-width: 1200px;';
    $page->zobrazitTlacitka = false;
    $page->pagination = true;
    $page->info = false;
    $page->riadkov = 10;

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>

                        <thead>

                            <tr>
                                <th>P.č.</th>
                                <th>Avatar</th>
                                <th>Meno</th>
                                <th>Stredisko</th>
                                <th>Posledné prihlásenie</th>
                                <th>E-mail</th>
                                <th>Telefón</th>
                            </tr>

                        </thead>
                        <tbody>

<?php
    $poradie = 1;
    
    $data = $db->query("SELECT *
                        FROM `50_sys_users`
                        LEFT JOIN 
                            (SELECT `ID50_sys_users`, MAX(`DatumCas`) AS PoslednePrihlasenie 
                            FROM `60_log_prihlasenie` 
                            GROUP BY `ID50_sys_users`) AS X 
                        ON ID50 = X.`ID50_sys_users`
                        WHERE `ID53_sys_levels` >= 3 
                            AND `ID50` > 4
                            AND `Datum_Inicializacie_Konta` IS NOT NULL
                        ORDER BY PoslednePrihlasenie DESC;")->fetchAll();

    foreach ($data as $key => $value)
    {
        $osCislo = $id  = htmlspecialchars($value['OsobneCislo']);
        
        $meno           = htmlspecialchars($value['Meno']);
        $priezvisko     = htmlspecialchars($value['Priezvisko']);
        $titul          = htmlspecialchars($value['Titul']);
        $avatar         = htmlspecialchars($value['AvatarFILE']);
        $strediskoCislo = htmlspecialchars($value['Stredisko']);
        $stredisko      = htmlspecialchars($value['NazovStrediska']);
        $aktivacia      = htmlspecialchars($value['PoslednePrihlasenie']);
        $email          = htmlspecialchars($value['Email']);
        $telefon        = htmlspecialchars($value['TelefonneCislo']);

?>
                            <tr id='<?= $id ?>'>
                                <td class="text-center"><?= $poradie ?>.</td>
                                <td class="text-center"><img alt="Avatar" class="img-circle elevation-2" width="30" src="/dist/avatar/<?= $avatar ?>"></td>
                                <td><?= "[".$osCislo."] ".(isset($titul) ? $titul." " : "" ) . $meno . " " . $priezvisko; ?></td>
                                <td><?= $strediskoCislo . ' ' . $stredisko ?></td>
                                <td><?= $aktivacia ?></td>
                                <td><?= $email ?></td>
                                <td><?= $telefon ?></td>
                            </tr>
<?php
        $poradie += 1;
    }
?>

                        </tbody>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky