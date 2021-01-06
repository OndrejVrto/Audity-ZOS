<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

$page = new \Page\Page();
$page->zobrazitBublinky = false;

$page->content = "\t\t".'<iframe src="/spravca-suborov/okno" class="w-100" frameborder="0" scrolling="no" onload="this.style.height = this.contentWindow.document.documentElement.scrollHeight + \'px\';" ></iframe>';

$page->display();
