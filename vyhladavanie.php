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


            $pages = new Paginator;

            $pages->default_ipp = 1;
            $pages->items_total = $pocet;
            $pages->mid_range = 4;
            $pages->paginate();


?>
            <hr class="pt-2">
            <p class="mx-5 mb-3 mt-n3">Počet výsledkov: <span class="font-weight-bold"><?= $pocet ?></span></p>


    <div class="card py-2 px-3 mb-2">
        <a class="h5" href="/farnost/historia-kostola-sv-frantiska-v-detve" title="Farnosť Detva - história kostola">História kostola</a>
        <a class="text-decoration-none text-success" href="/farnost/historia-kostola-sv-frantiska-v-detve">
            farnost/historia-kostola-sv-frantiska-v-detve</a>
        <span class="text-break">Farský kostol Pôvod mestečka Detva tesne súvisí s Vígľašským hradom, s ktorým spolu znášali všetky utrpenia, o ktorých súčas&nbsp;...</span>
    </div>


    <div class="container-fluid pt-4">
        <nav class="justify-content-center m-0 px-0" aria-label="Page navigation">
            <ul class="pagination justify-content-center m-0 p-0">
                <li class="page-item disabled"><a class="page-link" href="#" aria-label="Previous"><i class="fas fa-chevron-left" aria-hidden="true"></i></a></li>
                <li class="page-item active"><a class="page-link" href="/vyhladavanie/1/fara">1<span class="sr-only">(aktívna)</span></a></li>
                <li class="page-item"><a class="page-link" href="/vyhladavanie/2/fara">2</a></li>
                <li class="page-item"><a class="page-link" href="/vyhladavanie/3/fara">3</a></li>
                <li class="page-item"><a class="page-link" href="/vyhladavanie/4/fara">4</a></li>
                <li class="page-item"><a class="page-link" href="/vyhladavanie/5/fara">5</a></li>
                <li class="page-item disabled"><a class="page-link" href="">...</a></li>
                <li class="page-item"><a class="page-link" href="/vyhladavanie/14/fara">14</a></li>
                <li class="page-item"><a class="page-link" href="/vyhladavanie/2/fara" aria-label="Next"><i class="fas fa-chevron-right" aria-hidden="true"></i></a></li>
            </ul>
        </nav>
    </div>
    </div>


    <div class="container">
        <div class="col-sm-12 paddingLeft pagerfwt">
            <?php if ($pages->items_total > 0) { ?>
                <?php echo $pages->display_pages(); ?>
                <?php echo $pages->display_items_per_page(); ?>
                <?php echo $pages->display_jump_menu(); ?>
            <?php } ?>
        </div>
        <div class="clearfix"></div>
    </div>

    <style>
        .pagination {
            clear: both;
            padding: 0;
        }

        .pagination li {
            display: inline;
        }

        .pagination a {
            border: 1px solid #D5D5D5;
            color: #666666;
            font-size: 11px;
            font-weight: bold;
            height: 25px;
            padding: 4px 8px;
            text-decoration: none;
            margin: 2px;
        }

        .pagination a:hover,
        .pagination a:active {
            background: #efefef;
        }

        .pagination span.current {
            background-color: #687282;
            border: 1px solid #D5D5D5;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            height: 25px;
            padding: 4px 8px;
            text-decoration: none;
            margin: 2px;
        }

        .pagination span.disabled {
            border: 1px solid #EEEEEE;
            color: #DDDDDD;
            margin: 2px;
            padding: 2px 5px;
        }
    </style>
    <?php

            $pages2 = new Pagination('10', 'p');
            $pages2->set_total('100'); //or a number of records
            //display the records here
            echo $pages2->page_links();

    ?>


<?php
        }
    }

$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky
