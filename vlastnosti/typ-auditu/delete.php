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
        
        $db->query('UPDATE `31_zoznam_typ_auditu` SET `KtoVykonalZmenu` = ? WHERE `ID31` = ?', $user, $id);
        $db->query('DELETE FROM `31_zoznam_typ_auditu` WHERE `ID31` = ?', $id);
        
        header("Location: $page->linkZoznam");  
        exit();
    }

    $pocet = 0;
    if (isset($_POST['delete'])) {
        // kontrola či je záznam použitý v iných tabuľkách. Ak áno, nedá sa zmazať.
        $id = (int)$_POST['delete'];

        $data = $db->query(
            'SELECT 
                (SELECT COUNT(*) FROM `02_audity` WHERE `ID31_zoznam_typ_auditu` = ?)
                +
                (SELECT COUNT(*) FROM `35_zoznam_oblast_zistenia` WHERE `ID31_zoznam_typ_auditu` = ?)
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
        
        $data = $db->query('SELECT * FROM `31_zoznam_typ_auditu`, `30_zoznam_oblast_auditu` WHERE ID30_zoznam_oblast_auditu = ID30 AND ID31 = ?', $id)->fetchArray();
    
        $nazov      = vycistiText($data['Nazov_Norma']);
        $rok        = vycistiText($data['RokVydaniaNormy']);
        $skratka    = vycistiText($data['Skratka']);
        $farba      = vycistiText($data['Farba']);
        $referencny = vycistiText($data['ReferencnyDokument']);
        $checkbox   = vycistiText($data['PovinnostMatPlatny']); 
        $poznamka   = vycistiText($data['Poznamka']);
        $oblast     = vycistiText($data['OblastAuditovania']);

?>

                        <div>
                            Si si istý, že chceš zmazať položku 
                            <span class="h5 text-danger"><?= $nazov ?></span>
                            ?
                            <div class="mt-3"><u>Bližšie informácie o položke:</u></div>
                            <p class="pl-4"><strong>Oblasť:</strong> <?= $oblast ?></p>
                            <p class="pl-4"><strong>Rok vydania normy:</strong> <?= $rok ?></p>
                            <p class="pl-4"><strong>Skratka:</strong> <?= $skratka ?></p>
                            <p class="pl-4"><strong>Farba:</strong> <?= $farba ?></p>
                            <p class="pl-4"><strong>Referenčný dokument:</strong> <?= $referencny ?></p>
                            <p class="pl-4"><strong>Je požadovaný:</strong> <?= $checkbox ?></p>
                            <p class="pl-4"><strong>Poznámka:</strong> <?= $poznamka ?></p>
                        </div>

<?php
    endif;

$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky