<?php

class ValidatorLogin extends Validator
{

    function DoValidate(&$formars, &$error_hash)
    {
        $vysledok = true;
        // prebratie pripojenia na databazu z globálnej premennej
        $db =& $GLOBALS['db'];
        foreach (array_keys($formars) as $value) {

            switch ($value) {
                
                case 'login-osobne-cislo': {
                    $user = $formars['login-osobne-cislo'];
                    $password = $formars['login-pasword'];
                    $row = $db->query('SELECT * FROM `50_sys_users` WHERE `OsobneCislo` = ?', $user )->fetchArray();
                    if ( empty($row) ) {
                        $error_hash['login-osobne-cislo']="Uživateľ s týmto menom nieje zaregistrovaný.";
                        $vysledok = false;
                    } else {
                        $pwdCheck = password_verify($password, $row['Password']);
                        if ($pwdCheck === false) {
                            $error_hash['login-pasword']="Zadali ste nesprávne heslo pre uživateľa " . $this->PurifiText($user);
                            $vysledok = false;
                        }
                    }
                    break;
                }

                case 'signup-osobne-cislo': {
                    $user = $formars['signup-osobne-cislo'];
                    $row = $db->query('SELECT * FROM `50_sys_users` WHERE `OsobneCislo` = ?', $user )->fetchArray();
                    if ( ! empty($row) ) {
                        $error_hash['signup-osobne-cislo']="Uživateľ s týmto osobným číslom už je zaregistrovaný.";
                        $vysledok = false;
                    } 
                    break;
                }


            }
        }
        return $vysledok;
    }
}
