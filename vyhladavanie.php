<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    if ( !isset($_POST) OR $_POST['hladanyRetazec'] == '' ) {
        header("Location: /");
        exit();
    }

    $page = new \Page\Page();
    $page->zobrazitBublinky = false;
    $page->todo = true;

ob_start();  // Začiatok definície hlavného obsahu
?>

        <p><u><strong>Hľadaný výraz:</strong></u></p>
        <p class="h3 ml-5 mb-4 text-danger"><?= $_POST['hladanyRetazec'] ?></p>
        <p><u><strong>Nájdené výsledky:</strong></u></p>
        <p class="ml-5 mb-4 text-secondary">Zatiaľ vyhľadávanie nefunguje !</p>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky


