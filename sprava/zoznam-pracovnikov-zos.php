    <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Zoznam();
    $page->bodyClassExtended = 'col-12';
    $page->bodyWidthExtended = 'max-width: 1200px;';
    $page->zobrazitTlacitka = false;

    try {
        $pdo = new PDO('odbc:MAXDATA', '', '');
        // set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
    } catch(PDOException $e) {
        echo "Connection failed: " . trim(iconv('Windows-1250', 'UTF-8', $e->getMessage()));
    }

    if ($pdo) {

        $stmt = $pdo->prepare("SELECT * FROM maxmast.uoscis WHERE offdate > :datum ORDER BY ondate ASC");
        $stmt->execute(['datum' => date("Y-m-d")]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        array_convert_MAX($data);

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
                                <th >Názov strediska</th>
                                <th style="width: 60px;" >Nástup do ŽOS</th>
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
        $nastup         = htmlspecialchars($value['ondate']);
?>
                            <tr id='<?= $id ?>'>
                                <td class="text-center"><?= $poradie ?>.</td>
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
    endforeach;
?>

                        </tbody>

<?php
}
$page->content = ob_get_clean();  // Koniec hlavného obsahu

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>
    <script>

    </script>
<?php
$page->skriptySpecial = ob_get_clean();

$page->display();  // vykreslenie stranky