    <?php
    require_once $_SERVER['DOCUMENT_ROOT'] . "/include/inc.require.php";
    
    $homepage = new PageZoznam();

    ob_start();  // Začiatok definície hlavného obsahu

    $conn = odbc_connect('MAXDATA', '', '');

    $nrows = 0;

    if ($conn) {
        $sql =  "SELECT * FROM maxmast.uoscis";
        //this function will execute the sql satament
        
        $result = odbc_exec($conn, $sql);

        echo "<thead><tr> ";
        // -- print field name
        $colName = odbc_num_fields($result);
        for ($j = 1; $j <= $colName; $j++) {
            echo "<th>";

            $text =  odbc_field_name($result, $j);
            echo iconv(mb_detect_encoding($text, mb_detect_order(), true), "UTF-8", $text);
            echo "</th>";
        }
        echo "</thead><tbody>";
        $j = $j - 1;
        $c = 0;
        // end of field names
        while (odbc_fetch_row($result)) // getting data
        {
            $c = $c + 1;
            if ($c % 2 == 0)
                echo "<tr>\n";
            else
                echo "<tr>\n";
            for ($i = 1; $i <= odbc_num_fields($result); $i++) {
                echo "<td>";
                $text = utf8_encode( odbc_result($result, $i));
                echo iconv(mb_detect_encoding($text, mb_detect_order(), true), "UTF-8", $text);
                echo "</td>";
                if ($i % $j == 0) {
                    $nrows += 1; // counting no of rows   
                }
            }
            echo "</tr></tbody>";
        }

        //echo "</td> </tr>\n";

        // --end of table 
/*         if ($nrows == 0) echo "<br/><center> Nothing for $month yet! Try back later</center>  <br/>";
        else echo "<br/><center> Total Records:  $nrows </center>  <br/>"; */
        odbc_close($conn);
    } else echo "odbc not connected <br>";

$homepage->content = ob_get_clean();  // Koniec hlavného obsahu

$homepage->display();  // vykreslenie stranky