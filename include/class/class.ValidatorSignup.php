<?php

// Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
spl_autoload_register(function ($class_name) {
    include_once $_SERVER['DOCUMENT_ROOT'] . "/include/class/class.".$class_name.'.php';
});

class ValidatorSignup extends Validator
{

    public function validateForm()
    {
        foreach ($this->privat_data as $key => $value) {

            $this->addValue($key, $value);
            
// 'signup-osobne-cislo', 'signup-titul', 'signup-meno', 'signup-priezvisko', 'signup-email', 'signup-pasword', 'signup-pasword-repeater'
            switch ($key) {
                case "signup-osobne-cislo":
                    $this->validateSignupOsobneCislo($key);
                    break;
                case "signup-meno":
                case "signup-priezvisko":                    
                    $this->validateSignupUsername($key);
                    break;
                case "signup-email":
                    $this->validateEmail($key);
                    break;
                case "signup-pasword":
                case "signup-pasword-repeater":                    
                    $this->validateSignupPasword($key);
                    break;
                case "signup-titul":    
                    $this->addSucces($key, 'OK. Titul nemusí byť vyplnený.');
                    break;                                       
            }
        }
        return $this->privat_succes;
    }


    // Validacne funkcie

    private function validateSignupUsername($name)
    {
        $val = trim($this->privat_data[$name]);

        if (empty($val)) {
            $this->addError($name, 'Poľe nesmie byť prázdne.');
        } else if (!$this->is_alphanum($val)) {
            $this->addError($name, 'Meno sa môže skladať len s číslic a písmen.');
        } else {
            $this->addSucces($name, 'Správna hodnota.');
        }
    }

    private function validateEmail($name)
    {
        $val = trim($this->privat_data[$name]);

        if (empty($val)) {
            $this->addError($name, 'Poľe nesmie byť prázdne.');
        } else if (!$this->is_email($val)) {
            $this->addError($name, 'Musí byť vložený e-mailová adresa v správnom formáte.');
        } else {
            $this->addSucces($name, 'Pochvala. Zvládol si to.');
        }
    }
    
    private function validateSignupOsobneCislo($name)
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

    private function validateSignupPasword($name)
    {
        $value = trim($this->privat_data[$name]);

        if ($this->is_required($value)) {
            $this->addError($name, 'Poľe s heslom nesmie byť prázdne.');
        } else {
            $this->addSucces($name, 'Vcelku dobré heslo');
        } 

    }

}
