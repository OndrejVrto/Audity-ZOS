<?php

    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    $page = new \Page\Page();
    $page->zobrazitBublinky = false;
    $page->nadpis = "Kalendár na rok " . date ("Y");

    $page->stylySpecial = PHP_EOL.TAB1.'<link rel="stylesheet" href="dist/calendarize.css">';

ob_start();
?>
            <div class="row">
                <div id="calendar"></div>
            </div>

            <script type="text/javascript" src="dist/calendarize.js"></script>
            <script type="text/javascript">
                var $calendar = document.getElementById("calendar");
                var currentYear = new Date().getFullYear();
                var calendarize = new Calendarize();
                calendarize.buildYearCalendar($calendar, currentYear);
            </script>

<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();