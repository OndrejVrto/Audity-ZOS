<?php
    // tento skript slúži pri vývoji na ručné spustenie niektorých funkcií

    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    // spustenie synchronizácie dát v tabuľke USERS.
    // Spúšťa sa v rámci procesu LOGIN 1x za deň
    AktualizujUSERS();
    
    $_SESSION['ALERT'] = ' "Aktualizácia prebehla" ';

    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        header("Location: /");
    }
    exit;