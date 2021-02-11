<?php

namespace Validator;

class Login extends \Validator\Validator
{

    function DoValidate(&$formars, &$error_hash)
    {
        $vysledok = true;

        // prebratie pripojenia na databazu z globálnej premennej
        global $db;

        foreach (array_keys($formars) as $value) {

            switch ($value) {
                
                case 'login-osobne-cislo': {
                    $user = $formars['login-osobne-cislo'];
                    $password = $formars['login-pasword'];

                    $pocetUtokov = testSqlInjestion($user);
                    if ($pocetUtokov > 0) {
                        $error_hash['login-osobne-cislo']="Pokúšate sa o SQL injection? O vašom pokuse bol oboznámený administrátor.";
                        $vysledok = false;
                        $_SESSION['ALERT'] = ' "Pokúšate sa o SQL injection?" + "\n" + "O vašom pokuse bol oboznámený administrátor!" ';
                        break;
                    }

                    $row = $db->query('SELECT * FROM `50_sys_users` WHERE `OsobneCislo` = ?', $user )->fetchArray();
                    
                    // záznam neexistuje v tabuľke
                    if ( empty($row) ) {
                        $error_hash['login-osobne-cislo']="Uživateľ s týmto osobným číslom neexistuje.";
                        $vysledok = false;
                        break;
                    }

                    // uživateľovi bolo odobraté právo sa prihlásiť - záznam sa aktualizuje automaticky s MAXu
                    if ($row['ID53_sys_levels'] === 2 OR $row['ID53_sys_levels'] === 1) {
                        $error_hash['login-osobne-cislo'] = vycistiText($row['Meno']." ".$row['Priezvisko']) ." už v ŽOS nepracuje.";
                        $vysledok = false;
                        break;
                    }

                    // nesprávne heslo
                    if (is_null($row['Datum_Inicializacie_Konta'])) {
                        $pwdCheck = strcmp($password, $row['Password_OLD']) === 0;
                    } else {
                        $pwdCheck = strcmp($password, $row['Password_NEW']) === 0;
                    }
                    if ($pwdCheck === false) {
                        $error_hash['login-pasword']="Zadali ste nesprávne heslo pre uživateľa " . vycistiText($user);
                        $vysledok = false;
                        break;
                    }

                }
                case 'login-pasword': {
                    // Parser na zistenie či sa nejedná o hackerský pokus o SQL injection
                    $pocetUtokov = testSqlInjestion($password);
                    if ($pocetUtokov > 0) {
                        $error_hash['login-pasword']="Pokúšate sa o SQL injection? O vašom pokuse bol oboznámený administrátor.";
                        $vysledok = false;
                        $_SESSION['ALERT'] = ' "Pokúšate sa o SQL injection?" + "\n" + "O vašom pokuse bol oboznámený administrátor!" ';
                        break;
                    }
                }

            /*      case 'signup-osobne-cislo': {
                    $user = $formars['signup-osobne-cislo'];
                    $row = $db->query('SELECT * FROM `50_sys_users` WHERE `OsobneCislo` = ?', $user )->fetchArray();
                    if ( ! empty($row) ) {
                        $error_hash['signup-osobne-cislo']="Uživateľ s týmto osobným číslom už je zaregistrovaný.";
                        $vysledok = false;
                    } 
                    break;
                } */


            }
        }
        return $vysledok;
    }
}
