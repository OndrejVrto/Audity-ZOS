<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";
    
    // Include calendar helper functions 
    include_once 'functions.php'; 

    $page = new \Page\Page();
    $page->zobrazitBublinky = false;
    $page->nadpis = "Kalendár s udalosťami";

    $page->stylySpecial = PHP_EOL.TAB1.'<link rel="stylesheet" href="style.css">';

ob_start();
?>

<!-- START - Display event calendar -->
<div id="calendar_div">
<?php echo getCalender(); ?>
</div>
<!-- END - Display event calendar -->

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();
