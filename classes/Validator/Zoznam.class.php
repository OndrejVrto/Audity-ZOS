<?php

namespace Validator;

class Zoznam extends \Validator\Validator
{

    function DoValidate(&$formars, &$error_hash)
    {
        $vysledok = true;
        // prebratie pripojenia na databazu z globálnej premennej
        $db =& $GLOBALS['db'];
        foreach (array_keys($formars) as $value) {

            switch ($value) {
                case 'oblast-auditu': {

                    $valueNEW = $formars['oblast-auditu'];
                    $valueOLD = $formars['valueOld'];
            
                    // kontrola či záznam už existuje v tabuľke. Ak áno, nedá sa vytvoriť.
                    $data = $db->query('SELECT COUNT(*) AS Pocet FROM `30_zoznam_oblast_auditu` WHERE `OblastAuditovania`= ? AND  `OblastAuditovania` <> ?', $valueNEW, $valueOLD )->fetchArray();
            
                    $pocet = (int)$data['Pocet'];
                    
                    if ($pocet > 0) {
                        $error_hash['oblast-auditu']="Hodnota sa už v zozname nachadza.";
                        $vysledok = false;
                    }
                    break;
                }

/*                 case 'oblast-auditu-poznamka': {

                    $valueNEW = $formars['oblast-auditu'];
                    $valueOLD = $formars['valueOld'];
            
                    // kontrola či záznam už existuje v tabuľke. Ak áno, nedá sa vytvoriť.
                    $data = $db->query('SELECT COUNT(*) AS Pocet FROM `30_zoznam_oblast_auditu` WHERE `OblastAuditovania`= ? AND  `OblastAuditovania` <> ?', $valueNEW, $valueOLD )->fetchArray();
            
                    $pocet = (int)$data['Pocet'];
                    
                    if ($pocet > 0) {
                        $error_hash['oblast-auditu-poznamka']="Hodnota sa už v zozname nachadza cdfdd.";
                        $vysledok = false;
                    } 
                    break;
                }   */          

            }
        }
        return $vysledok;
    }
}
