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
        <p><?= $_POST['hladanyRetazec'] ?></p>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();  // vykreslenie stranky


