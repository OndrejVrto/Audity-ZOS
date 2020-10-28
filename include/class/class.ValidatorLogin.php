<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/class/class.Validator.php");

class ValidatorLogin extends Validator
{

    public function validateForm()
    {
        foreach ($this->privat_data as $key => $value) {

            $this->addValue($key, $value);
            
            switch ($key) {
                case "login-osobne-cislo":
                    $this->validateLoginOsobneCislo($key);
                    break;
                case "login-pasword":
                    $this->validateLoginPasword($key);
                    break;
            }
        }
        return $this->privat_succes;
    }


    // Validacne funkcie

    private function validateLoginOsobneCislo($name)
    {

        $val = trim($this->privat_data[$name]);

        if (empty($val)) {
            $this->addError($name, 'Poľe nesmie byť prázdne.');
            $this->addError($name, 'Druhý riadok');
            $this->addError($name, 'Tretí riadok');
        } else if (!preg_match('/^[0-9]{1,6}+[0-9a-zA-Z]{0,3}$/', $val)) {
            $this->addError($name, 'Osobné číslo je mimo vzor');
        } else {
            $this->addSucces($name, 'Správna hodnota. Super.');
        }
    }

    private function validateLoginPasword($name)
    {
        $val = trim($this->privat_data[$name]);

        if (empty($val)) {
            $this->addError($name, 'Poľe s heslom nesmie byť prázdne.');
        } else if (!preg_match('/^[0-9a-zA-Z\#\$\%\^\&]{1,6}$/', $val)) {
            $this->addError($name, 'Heslo je slabé');

        } else {
            $this->addSucces($name, 'Vcelku dobré heslo');
        }
    }
}
