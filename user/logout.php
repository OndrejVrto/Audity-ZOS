<?php

if (isset($_SESSION['RefererURL'])) {
    $navrat = $_SESSION['RefererURL'];
} elseif (isset($_SERVER["HTTP_REFERER"])) {
    $navrat = $_SERVER["HTTP_REFERER"];
} else {
    $navrat = "/";
}

session_start();
session_unset();
session_destroy();

header("Location: " . $navrat);
exit;