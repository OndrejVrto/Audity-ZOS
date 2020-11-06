<?php

// Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
spl_autoload_register(function ($class_name) {
    include_once $_SERVER['DOCUMENT_ROOT'] . "/include/class/class.".$class_name.'.php';
});

class ValidatorSignup extends Validator
{

    public function __construct($post_data)
    {
        $this->privat_data = $post_data;
        
        // ak nieje zaskrtnute tlacitko checkbox tak v POST metode chyba kluc aj hodnota
        // preto nastavím priamo chybu
        $this->addError('signup-checkbox', 'Súhlas je nevyhnutný.');
        if (isset($_FILES)) {
            $this->privat_data['signup-avatar'] = $_FILES['signup-avatar']['name'];
        }
        
        //print_r($this->privat_data);
        //print_r($_FILES);

    }

    public function validateForm()
    {
        foreach ($this->privat_data as $key => $value) {

            $this->addValue($key, $value);
            
            switch ($key) 
                {
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
                    $this->addSucces($key, '');
                    break;
                case "signup-checkbox":
                    $this->validateSignupCheckbox($key);
                    break;
                case "signup-telefon":
                    $this->validateSignupTelefon($key);
                    break;
                case "signup-avatar":
                    $this->validateSignupAvatar($key);
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

    private function validateSignupCheckbox($name)
    {
        $value = trim($this->privat_data[$name]);
        if (!$this->is_required($value)) {
            // vymazanie chyby s construktora
            $this->privat_errors[$name] = '';
            // nastavenie prazdnej validnej hodnoty
            $this->addSucces($name, '');
        }
    }

    private function validateSignupTelefon($name)
    {
        $value = trim($this->privat_data[$name]);
        if ($this->is_required($value)) {
            $this->addError($name, 'Telefónny kontakt je dôležitý. Vyplňte ho.');
        } else {
            if ($this->is_numeric($value)) {
                $this->addError($name, 'Telefónne číslo sa skladá iba z číslic.');
            } else {
                if ($this->is_minLength($value, 10)) {
                    $this->addError($name, 'Telefónne číslo musí mať minimálne 10 číslic');
                } else {
                    if ($this->is_maxLength($value, 14)) {
                        $this->addError($name, 'Telefónne číslo môže mať maximálne 14 číslic');
                    }else {
                        $this->addSucces($name, 'Telefónne číslo je v poriadku');
                    }   
                } 
            } 
        }
    }

    private function validateSignupAvatar($name)
    {
        $value = trim($this->privat_data[$name]);
        if ($this->is_required($value)) {
            $this->addError($name, 'Súbor nieje vybratý');
        } else {
            $this->addSucces($name, 'Súbor si si vybral, ale nevidíš ho lebo je to pokazenuo.');
        } 
    }
    
}