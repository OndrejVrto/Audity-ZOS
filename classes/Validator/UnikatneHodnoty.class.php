<?php

namespace Validator;

//* trieda zoskupuje dotazy do databázy na kontrolu či záznam existuje v tabuľke. Ak áno, nedá sa vytvoriť.

class UnikatneHodnoty extends \Validator\Validator
{

    function DoValidate(&$formars, &$error_hash)
    {
        // prebratie pripojenia na databazu z globálnej premennej
        global $db;
        $vysledok = true;

        foreach (array_keys($formars) as $value) {
            
            $data['Pocet'] = 0;
            $valueNEW = strtolower($formars[$value]);
            $valueOLD = strtolower($formars['valueOld']);

            switch ($value) {
                case 'oblast-auditu': {
                    $data = $db->query('SELECT COUNT(*) AS Pocet 
                                        FROM `30_zoznam_oblast_auditu` 
                                        WHERE  LOWER(`OblastAuditovania`) = ? AND  LOWER(`OblastAuditovania`) <> ?',
                                        $valueNEW, $valueOLD )->fetchArray();
                break;
                }
                case 'typ-auditu--skratka': {
                    $data = $db->query('SELECT COUNT(*) AS Pocet 
                                        FROM `31_zoznam_typ_auditu` 
                                        WHERE LOWER(`Skratka`) = ? AND  LOWER(`Skratka`) <> ?', 
                                        $valueNEW, $valueOLD )->fetchArray();
                break;
                }
                case 'typ-externych-zisteni': {
                    $data = $db->query('SELECT COUNT(*) AS Pocet 
                                        FROM `33_zoznam_typ_externych_zisteni` 
                                        WHERE LOWER(`NazovExternehoZistenia`) = ? AND  LOWER(`NazovExternehoZistenia`) <> ?', 
                                        $valueNEW, $valueOLD )->fetchArray();
                break;
                }
                case 'privlastky-auditu': {
                    $data = $db->query('SELECT COUNT(*) AS Pocet 
                                        FROM `34_zoznam_privlastok_auditu` 
                                        WHERE LOWER(`PrivlastokAuditu`) = ? AND  LOWER(`PrivlastokAuditu`) <> ?', 
                                        $valueNEW, $valueOLD )->fetchArray();
                break;
                }
                case 'typ-prilohy': {
                    $data = $db->query('SELECT COUNT(*) AS Pocet 
                                        FROM `38_zoznam_typ_prilohy` 
                                        WHERE LOWER(`TypPrilohy`) = ? AND  LOWER(`TypPrilohy`) <> ?', 
                                        $valueNEW, $valueOLD )->fetchArray();
                break;
                }
                case 'rola-audit': {
                    $data = $db->query('SELECT COUNT(*) AS Pocet 
                                        FROM `36_zoznam_rola_pri_audite` 
                                        WHERE LOWER(`RolaAudit`) = ? AND  LOWER(`RolaAudit`) <> ?', 
                                        $valueNEW, $valueOLD )->fetchArray();
                break;
                }
                case 'rola-opatrenie': {
                    $data = $db->query('SELECT COUNT(*) AS Pocet 
                                        FROM `37_zoznam_rola_pri_opatreni` 
                                        WHERE LOWER(`RolaOpatrenie`) = ? AND  LOWER(`RolaOpatrenie`) <> ?', 
                                        $valueNEW, $valueOLD )->fetchArray();
                break;
                }

                
                
            }

            if ((int)$data['Pocet'] > 0) {
                $error_hash[$value]="Hodnota sa už v zozname nachadza.";
                $vysledok = false;
            }
        }
        return $vysledok;
    }
}
