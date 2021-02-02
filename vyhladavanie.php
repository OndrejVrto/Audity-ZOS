<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Page();
    $page->zobrazitBublinky = false;

    $search = new Vyhladavanie($page->userName);
    $search->setHladanaHodnota($page->searchValue);
    $search->zaznamov = 3;
    $search->Hladat();

ob_start();  // Začiatok definície hlavného obsahu
?>
            <div class="container">

                <!-- SEARCH FORM -->
                <form class="input-group input-group-lg m-auto p-4" action="/vyhladavanie" method="GET">
                    <input class="form-control" type="search" name="search" aria-label="Search" value="<?= vycistiText($page->searchValue) ?>" placeholder="Hľadaj ...">
                    <span class="input-group-append">
                        <button class="btn btn-warning" type="submit">
                            <i class="fas fa-search" aria-hidden="true"></i>
                        </button>
                    </span>
                </form>
                <!-- END - SEARCH FORM -->                
                <?= $search->zobrazVysledok() ?>
            </div>
            <?= ( VYVOJ OR $page->levelUser >= 20 ) ? $search->zobrazVyvoj() : '' ?>
<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky
