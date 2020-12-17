<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Page();
    $page->zobrazitBublinky = false;

    $casovaOS = new Timeline;

ob_start();  // Začiatok definície hlavného obsahu
?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
<?= $casovaOS->ZobrazTimeLine() ?>
                </div>
            </div>
        </div>
<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky