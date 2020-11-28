<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Page();
    $page->zobrazitBublinky = false;

ob_start();  // Začiatok definície hlavného obsahu
?>
        <div class="container">

            <!-- SEARCH FORM -->
            <form class="input-group input-group-lg m-auto p-4" action="/vyhladavanie" method="POST">
                <input class="form-control" type="search" name="hladanyRetazec" aria-label="Search" value="<?= htmlspecialchars($page->searchValue) ?>" placeholder="Hľadaj ...">
                <span class="input-group-append">
                    <button class="btn btn-warning" type="submit">
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </button>
                </span>
            </form>
<?php
    // je vyhľadávaný prázdny reťazec  ->  zobraziť len formulár vyhľadávania
    if ($page->searchValue == '') {
?>
            <p class="mx-5 mb-3 mt-n3">Ak chcete vyhľadávať, musíte niečo napísať do poľa.</p>
        </div>
<?php
    } else {
        // todo>  vyhľadávací dotaz do databázy

        $hladanyRetazec = gramatika($page->searchValue)['bezGramatiky'];
        $hladanyRetazec = $db->escapeString($hladanyRetazec);

        $sql = 'SELECT Hodnota_CISTA, 
                LOCATE("%s", Hodnota_CISTA) AS poloha,
                MATCH(Hodnota_CISTA) AGAINST("%s" IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION) as score
                FROM 70_search_search
                WHERE MATCH(Hodnota_CISTA) AGAINST("%s" IN NATURAL LANGUAGE MODE WITH QUERY EXPANSION)';
        $sql_1 = sprintf($sql, $hladanyRetazec, $hladanyRetazec, $hladanyRetazec);

        $sql = 'SELECT Hodnota_CISTA,
                LOCATE("%s", Hodnota_CISTA) AS poloha,
                0.1 as score
                FROM 70_search_search WHERE
                Hodnota_CISTA LIKE "%%%s%%"';
        $sql_2 = sprintf($sql, $hladanyRetazec, $hladanyRetazec);

        $sql = '(' . $sql_1 . ') UNION ALL (' . $sql_2 . ') ORDER BY score DESC';

        $db->query($sql);
        $pocet = $db->numRows();
        
        // ak hľadaný výraz vráti prázdne poľa výsledkov  ->  zobraziť len formulár vyhľadávania
        if ($pocet == 0) {
?>
            <p class="mx-5 mb-3 mt-n3">Hľadaný výraz nepriniesol žiadne výsledky ...</p>
        </div>
<?php
        } else {
            $data = $db->fetchAll();
            print_r($data);
?>
            <hr class="pt-2">
            <p class="mx-5 mb-3 mt-n3">Počet výsledkov: <span class="font-weight-bold"><?= $pocet ?></span></p>



<?php
        }
    }

$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky
