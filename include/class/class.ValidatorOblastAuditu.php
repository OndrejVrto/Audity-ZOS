<?php

// Automatické nahrávanie všetkých CLASS pri ich prvom zavolaní
spl_autoload_register(function ($class_name) {
    include_once $_SERVER['DOCUMENT_ROOT'] . "/include/class/class.".$class_name.'.php';
});

class ValidatorOblastAuditu extends Validator
{

    public function validateForm()
    {
        foreach ($this->privat_data as $key => $value) {

            $this->addValue($key, $value);
            
            switch ($key) {
                case "oblast-auditu":
                    $this->validateOblast($key);
                    break;
                case "oblast-auditu-poznamka":
                    $this->validatePoznamka($key);
                    break;
            }
        }
        return $this->privat_succes;
    }


    // Validacne funkcie

    private function validateOblast($name)
    {
        $value = $this->privat_data[$name];

        if ($this->is_required($value)) {
            $this->addError($name, 'Poľe nesmie byť prázdne.');
        } else {
            $this->addSucces($name, 'Správna hodnota. Super.');
        }
    }

    private function validatePoznamka($name)
    {
        $this->addSucces($name, 'Správna hodnota. Super.');

    }
}
