<?php

// Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
spl_autoload_register(function ($class_name) {
    include_once $_SERVER['DOCUMENT_ROOT'] . "/include/class/class.".$class_name.'.php';
});

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
        $value = $this->privat_data[$name];

        if ($this->is_required($value)) {
            $this->addError($name, 'Poľe nesmie byť prázdne.');
        } else if (!$this->is_alphanum($value)) {
            $this->addError($name, 'Osobné číslo môže byť zložené len s číslic a písmen.');
        } else {
            $this->addSucces($name, 'Správna hodnota. Super.');
        }
    }

    private function validateLoginPasword($name)
    {
        $value = trim($this->privat_data[$name]);

        if ($this->is_required($value)) {
            $this->addError($name, 'Poľe s heslom nesmie byť prázdne.');
        } else {
            $this->addSucces($name, 'Vcelku dobré heslo');
        } 

    }
}
