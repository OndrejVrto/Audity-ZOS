<?php

class ValidatorLogin extends Validator
{

    public function __construct($post_data)
    {
        $this->privat_data = $post_data;
        // prebratie pripojenia na databazu z globálnej premennej
        $this->db =& $GLOBALS['db'];
    }
    
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
        
        // TODO : Dorobiť validáciu s databázy
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

        // TODO : Dorobiť validáciu s databázy
        if ($this->is_required($value)) {
            $this->addError($name, 'Poľe s heslom nesmie byť prázdne.');
        } else {
            $this->addSucces($name, 'Vcelku dobré heslo');
        } 
    }

}
