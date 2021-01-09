<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

$page = new \Page\Page();
$page->zobrazitBublinky = false;

ob_start();
?>
            <script language="javascript" type="text/javascript">
                function resizeIframe(obj) {
                    obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
                }
            </script>

            <iframe src="/spravca-suborov/okno" class="w-100" frameborder="0" scrolling="no" onload='resizeIframe(this);' style="min-height: 1000px !important;"></iframe>
<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

$page->display();