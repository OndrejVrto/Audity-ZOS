<?php
    // tento skript slúži pri vývoji na ručné spustenie niektorých funkcií

    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/_autoload.php";

    // spustenie synchronizácie dát v tabuľke USERS.
    // Spúšťa sa v rámci procesu LOGIN 1x za deň
    try {
        
        AktualizujMAX();
        AktualizujUSERS();
        //!  Spustí aktualizáciu klapiek z VIS-u cez URL
        AktualizujKlapky(TRUE);

    } catch (Exception $e) {
        //echo 'Caught exception: ',  $e->getMessage(), "\n";
        $_SESSION['ALERT'] = ' "!!!  POZOR  !!!  Niečo sa pokazilo. '.PHP_EOL. $e->getMessage() .'" ';
        header("Location: /");
        exit;
    }
    
    $_SESSION['ALERT'] = ' "Aktualizácia prebehla" ';

    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        header("Location: /");
    }
    exit;