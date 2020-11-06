<?php
    // Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
    spl_autoload_register(function ($class_name) {
        include_once  $_SERVER['DOCUMENT_ROOT'] . "/include/class/class." . $class_name . '.php';
    });

    $uri = "/vlastnosti/oblasti-auditov/zoznam";

    $page = new PageZoznam($uri);

    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 600px; width:100%;';
    $page->linkCisty = "/vlastnosti/oblasti-auditov/";

    // vyberovy dotaz na data
    $sql = "SELECT * FROM 30_zoznam_oblast_auditu ORDER BY LOWER(OblastAuditovania) ASC";
    $data = dBzoznam($sql, $uri);

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>

                        <thead>

                            <tr>
                                <th>P.č.</th>
                                <th>Zoznam oblastí</th>
                            </tr>

                        </thead>
                        <tbody>

<?php
    $poradie = 1;
    foreach ($data as $key => $value) {
        $id = htmlspecialchars($value['ID30']);
        $oblastAuditovania = htmlspecialchars($value['OblastAuditovania']);
?>
                            <tr id='<?= $id ?>'>
                                <td><?= $poradie ?>.</td>
                                <td><?= $oblastAuditovania ?></td>
                            </tr>
<?php
        $poradie += 1;
    }
?>

                        </tbody>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

// vykreslenie stranky
$page->display();