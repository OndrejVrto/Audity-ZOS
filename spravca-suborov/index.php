<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

$page = new \Page\Page();
$page->zobrazitBublinky = false;

ob_start();
?>
            <iframe class="iframe-full-height w-100" src="/spravca-suborov/okno" class="w-100" frameborder="0" scrolling="no" style="min-height: 1000px !important;"></iframe>
<?php
$page->content = ob_get_clean();  // Koniec hlavného obsahu

ob_start();
?>
    <script language="javascript" type="text/javascript" nonce="<?= $GLOBALS["nonce"] ?>">
        
        $('.iframe-full-height').on('load', function(){
            this.style.height=this.contentWindow.document.body.scrollHeight + 'px'
        });

    </script>
<?php
$page->skriptySpecial = ob_get_clean();  // Koniec hlavného obsahu

$page->display();