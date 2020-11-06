<?php
    // Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
    spl_autoload_register(function ($class_name) {
        include_once  $_SERVER['DOCUMENT_ROOT'] . "/include/class/class." . $class_name . '.php';
    });
    
    require $_SERVER['DOCUMENT_ROOT'] . "/include/inc.dBconnect.php";

    $uri = "/vlastnosti/oblasti-auditov/zoznam";

    $page = new PageZoznamDelete($uri);

    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 600px;';
    $page->linkCisty = "/vlastnosti/oblasti-auditov/";

    $pocet = 0;

    // vymazanie záznamu z databazy
    // POZOR tento blok kodu musi byt na zaciatku aby ukončil zvyšný skript včas
    if (isset($_POST['submit'])) {
        $id = (int)mysqli_real_escape_string($conn, $_POST['submit']);

        $sql = "DELETE FROM `30_zoznam_oblast_auditu` WHERE `ID30`=".$id.";";

        dBzoznam2($sql, $uri);
        header("Location: $uri");   
        exit();
    }

    if (isset($_POST['delete'])) {
        // kontrola či je záznam použitý v iných tabuľkách. Ak áno, nedá sa zmazať.
        $id = (int)mysqli_real_escape_string($conn, $_POST['delete']);

        $sql = "SELECT 
                    (SELECT COUNT(*) FROM `31_zoznam_typ_auditu` WHERE `ID30_zoznam_oblast_auditu`= ".$id.")
                    +
                    (SELECT COUNT(*) FROM `01_certifikaty` WHERE `ID30_zoznam_oblast_auditu`= ".$id.")
                AS Pocet;";

        $pocetArray = dBzoznam($sql, $uri);
        // vytiahnutie počtu z výsledku dotazu
        $pocet = (int)$pocetArray[0]['Pocet'];
    }

    $page->id = htmlspecialchars($id);
    $page->pocet = $pocet;

    if ($pocet > 0) {
    // tento blok kodu sa spusti ak sa zmazavana je polozka pouzita v prepojenych tabulkach
    // dotaz by sa nevykonal a vrátil by chybu

ob_start();  // Začiatok definície hlavného obsahu -> 6x tabulátor
?>

                    <div class="h5 text-center">
                        Záznam nieje možné zmazať
                        <br>pretože sa používa
                        <br>v iných tabuľkách.
                        <br>
                        <br>Celkom: <strong><?= $pocet ?>x</strong>
                    </div>

<?php

    } elseif ($pocet <= 0) {
    // tento blok kodu sa spusti ak sa zmazavana polozka nenachádza pouzita v prepojenych tabulkach
    
    // detaily k položke
    $sql = "SELECT * FROM 30_zoznam_oblast_auditu WHERE ID30='".$id."'; ";
    $data = dBzoznam($sql, $uri);
    
    $id = htmlspecialchars($id);
    $oblast = htmlspecialchars($data[0]['OblastAuditovania']);
    $poznamka = htmlspecialchars($data[0]['Poznamka']);

?>

                        <div>
                            Si si istý, že chceš zmazať položku 
                            <span class="h5 text-danger"><?= $oblast ?></span>
                            ?
                            <div class="mt-3"><u>Bližšie informácie o položke:</u></div>
                            <p class="small-2"><?= $poznamka ?></p>
                        </div>

<?php
    }
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky