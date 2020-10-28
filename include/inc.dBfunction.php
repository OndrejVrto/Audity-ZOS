<?php

function dBzoznam($sql, $volaciaStranka = "/index.php")
{

    require 'inc.dBconnect.php';

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        // kontrola či je relácia v poriadku
        header("Location: " . $volaciaStranka . "?error=sqlerror");
        exit();
    } else {
        // spustenie dotazu s parametrom
        mysqli_stmt_execute($stmt);
        // zvratenie hodnoty do premennej
        $result = mysqli_stmt_get_result($stmt);
        $data = array();
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        } else {
            // v databaze je prazdna tabulka
            header("Location: " . $volaciaStranka . "?error=prazdnatabulkavdatabaze");
            exit();
        }
    }

    return $data;
}
