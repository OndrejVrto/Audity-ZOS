<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/include/inc.require.php";

$homepage = new Page();

ob_start();  // Začiatok definície hlavného obsahu
?>

    <h1 class="display-3 text-center">Na tejto stránke pracujeme<br> pilne ako včielky.</h1>

<?php
$homepage->content = ob_get_clean();  // Koniec hlavného obsahu

$homepage->display();  // vykreslenie stranky