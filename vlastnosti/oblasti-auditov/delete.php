<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Zoznam\Delete();
    $page->bodyClassExtended = 'col-12 col-sm-10 col-md-9 col-lg-7';
    $page->bodyWidthExtended = 'max-width: 600px;';

    // vymazanie záznamu z databazy
    // POZOR tento blok kodu musi byt na zaciatku aby ukončil zvyšný skript včas
    if (isset($_POST['submit'])) {
        $user = $page->userName;
        $id = (int)$_POST['submit'];
        
        $db->query('UPDATE `30_zoznam_oblast_auditu` SET `KtoVykonalZmenu` = ? WHERE `ID30` = ?', $user, $id);
        $db->query('DELETE FROM `30_zoznam_oblast_auditu` WHERE `ID30` = ?', $id);
        
        header("Location: $page->linkZoznam");  
        exit();
    }

    $pocet = 0;
    if (isset($_POST['delete'])) {
        // kontrola či je záznam použitý v iných tabuľkách. Ak áno, nedá sa zmazať.
        $id = (int)$_POST['delete'];

        $data = $db->query(
            'SELECT 
                (SELECT COUNT(*) FROM `31_zoznam_typ_auditu` WHERE `ID30_zoznam_oblast_auditu` = ?)
                +
                (SELECT COUNT(*) FROM `01_certifikaty` WHERE `ID30_zoznam_oblast_auditu` = ?)
            AS Pocet', $id, $id )->fetchArray();
        $pocet = (int)$data['Pocet'];
    }

    $page->id = vycistiText($id);
    $page->pocet = $pocet;


ob_start();  // Začiatok definície hlavného obsahu -> 5x a 6x tabulátor    

    if ($pocet > 0):
    // tento blok kodu sa spusti ak sa zmazavana je polozka pouzita v prepojenych tabulkach
    // dotaz na vymazanie by sa nevykonal, pretože mám v databáze nastavené cudzie kľúče
?>

                    <div class="h5 text-center">
                        Záznam nieje možné zmazať
                        <br>pretože sa používa
                        <br>v iných tabuľkách.
                        <br>
                        <br>Celkom: <strong><?= $pocet ?>x</strong>
                    </div>

<?php
    elseif ($pocet <= 0) :
    // potvrdzovací dialóg
    // tento blok kodu sa spusti ak NIE je zmazavana polozka pouzita v iných, prepojenych, tabulkach
        
        $data = $db->query('SELECT * FROM `30_zoznam_oblast_auditu` WHERE ID30 = ?', $id)->fetchArray();

        $oblast = vycistiText($data['OblastAuditovania']);
        $poznamka = vycistiText($data['Poznamka']);
?>

                        <div>
                            Si si istý, že chceš zmazať položku 
                            <span class="h5 text-danger"><?= $oblast ?></span>
                            ?
                            <div class="mt-3"><u>Bližšie informácie o položke:</u></div>
                            <p class="pl-4"><strong>Poznámka:</strong> <?= $poznamka ?></p>
                        </div>

<?php
    endif;

$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky