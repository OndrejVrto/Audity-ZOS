<?php

// rekurzívna funkcia na komnverziu celého poľa vrátených dát
// z kódovania Windows-1250 do UTF-8
function array_convert_MAX(&$items){
    foreach ($items as &$item) {
        if(is_array($item))
        array_convert_MAX($item);
        else
        $item = trim(iconv('Windows-1250', 'UTF-8', $item));
    }
}

// funkcia pre debugging
// vypíše do konzoly prehliadača obsah premennej
function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
}

function gramatika($vstup){
	
    $prevodni_tabulka = Array(
        'ä'=>'a', 'Ä'=>'A', 'á'=>'a', 'Á'=>'A', 'a'=>'a', 'A'=>'A', 'a'=>'a', 'A'=>'A', 'â'=>'a', 'Â'=>'A',
        'č'=>'c', 'Č'=>'C', 'ć'=>'c', 'Ć'=>'C', 'ď'=>'d', 'Ď'=>'D', 'ě'=>'e', 'Ě'=>'E', 'é'=>'e', 'É'=>'E',
        'ë'=>'e', 'Ë'=>'E', 'e'=>'e', 'E'=>'E', 'e'=>'e', 'E'=>'E', 'í'=>'i', 'Í'=>'I', 'i'=>'i', 'I'=>'I',
        'i'=>'i', 'I'=>'I', 'î'=>'i', 'Î'=>'I', 'ľ'=>'l', 'Ľ'=>'L', 'ĺ'=>'l', 'Ĺ'=>'L', 'ń'=>'n', 'Ń'=>'N',
        'ň'=>'n', 'Ň'=>'N', 'n'=>'n', 'N'=>'N', 'ó'=>'o', 'Ó'=>'O', 'ö'=>'o', 'Ö'=>'O', 'ô'=>'o', 'Ô'=>'O',
        'o'=>'o', 'O'=>'O', 'o'=>'o', 'O'=>'O', 'ő'=>'o', 'Ő'=>'O', 'ř'=>'r', 'Ř'=>'R', 'ŕ'=>'r', 'Ŕ'=>'R',
        'š'=>'s', 'Š'=>'S', 'ś'=>'s', 'Ś'=>'S', 'ť'=>'t', 'Ť'=>'T', 'ú'=>'u', 'Ú'=>'U', 'ů'=>'u', 'Ů'=>'U',
        'ü'=>'u', 'Ü'=>'U', 'u'=>'u', 'U'=>'U', 'u'=>'u', 'U'=>'U', 'u'=>'u', 'U'=>'U', 'ý'=>'y', 'Ý'=>'Y',
        'ž'=>'z', 'Ž'=>'Z', 'ź'=>'z', 'Ź'=>'Z', ','=>'',  '.'=>'-',
        );

	if ( $vstup == '' or $vstup == NULL ){
		return array( 'sGramatikou'=>NULL , 'bezGramatiky'=>NULL );
	} else {
		// odstráni všetky tabulátory a konce riadkov
		$vystup_gramatika = str_replace(array("\n\r", "\n", "\r", "\t"), " ", $vstup);
		//od-eskejpuje uvodzovky
		$vystup_gramatika = str_replace(array("'", '"'), array("\\'", "\\\""), $vystup_gramatika);
		// dve a viac medzier nahradí jednou medzerou
		$vystup_gramatika = preg_replace("/( ){2,}/", " ", $vystup_gramatika);
		// nahradí pevnú medzeru za normálnu
		$vystup_gramatika = preg_replace("/\xC2\xA0/", " ", $vystup_gramatika);
		// odstráni prázdne miesto na konci a na začiatku
		$vystup_gramatika = trim($vystup_gramatika);
		// odstráni diakritiku
		$vystup_bez_gramatiky = strtr($vystup_gramatika, $prevodni_tabulka);
		// všetky znaky malé
		$vystup_bez_gramatiky = strtolower($vystup_bez_gramatiky);
		//navratova hodnota v poli
		return array( 'sGramatikou'=>(string)$vystup_gramatika , 'bezGramatiky'=>(string)$vystup_bez_gramatiky );
	}

}

// funkcia vkladá a aktualizuje záznamy v databázovej tabuľke Search
function SetSearchData ($typ, $Tabulka_Cislo, $Tabulka_ID, $Tabulka_Stlpec, 
                        $Hodnota_ORGINAL = NULL, $Link = NULL, $user = NULL, 
                        $ID72_Nasobitel = 7, $url = FALSE, $PriponaSuboru = NULL)
{

    global $db;
    $Hodnota = gramatika($Hodnota_ORGINAL);
    $Hodnota_ORGINAL_gramatika = $Hodnota['sGramatikou'];
    $Hodnota_CISTA_gramatika = $Hodnota['bezGramatiky'];

    if ($url == TRUE) {
        $Link = $Link . "?id=" . $Tabulka_ID;
    }

    switch ($typ) {
        case 'INSERT': {
            $db->query('INSERT INTO `70_search_search` (`Tabulka_Cislo`, `Tabulka_ID`, `Tabulka_Stlpec`, 
                                    `Hodnota_ORGINAL`, `Hodnota_CISTA`, `ID72_search_nasobitel`, 
                                    `PriponaSuboru`, `Link`, `KtoVykonalZmenu`) 
                        VALUES (?,?,?,?,?,?,?,?,?)',
                        $Tabulka_Cislo, $Tabulka_ID, $Tabulka_Stlpec, 
                        $Hodnota_ORGINAL_gramatika, $Hodnota_CISTA_gramatika, $ID72_Nasobitel,
                        $PriponaSuboru, $Link, $user);
        break;
        }
        case 'UPDATE': {
            $db->query("UPDATE `70_search_search` 
                        SET `Hodnota_ORGINAL` = ?, `Hodnota_CISTA` = ?, `ID72_search_nasobitel` = ?, 
                            `PriponaSuboru` = ?, `Link` = ?, `KtoVykonalZmenu` = ? 
                        WHERE `Tabulka_Cislo` = ? AND `Tabulka_ID` = ? AND `Tabulka_Stlpec` = ?",
                        $Hodnota_ORGINAL_gramatika, $Hodnota_CISTA_gramatika, $ID72_Nasobitel,
                        $PriponaSuboru, $Link, $user,
                        $Tabulka_Cislo, $Tabulka_ID, $Tabulka_Stlpec);
        break;
        }
        case 'DELETE': {
            $db->query("DELETE FROM `70_search_search` 
                        WHERE `Tabulka_Cislo` = ? AND `Tabulka_ID` = ? AND `Tabulka_Stlpec` = ?",
                        $Tabulka_Cislo, $Tabulka_ID, $Tabulka_Stlpec);
        break;
        }
    }
}