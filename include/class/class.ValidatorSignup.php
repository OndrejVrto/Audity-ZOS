<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/include/class/class.Validator.php");

class ValidatorSignup extends Validator
{

    public function validateForm()
    {
        foreach ($this->privat_data as $key => $value) {

            $this->addValue($key, $value);
            
            switch ($key) {
                case "username":
                    $this->validateUsername($key);
                    break;
                case "email":
                    $this->validateEmail($key);
                    break;
            }
        }
        return $this->privat_succes;
    }


    // Validacne funkcie

    private function validateUsername($name)
    {
        $val = trim($this->privat_data[$name]);

        if (empty($val)) {
            $this->addError($name, 'username cannot be empty');
        } else if (!preg_match('/^[a-zA-Z0-9]{6,12}$/', $val)) {
            $this->addError($name, 'username must be 6-12 chars & alphanumeric');
        } else {
            $this->addSucces($name, 'Pochvala');
        }
    }

    private function validateEmail($name)
    {
        $val = trim($this->privat_data[$name]);

        if (empty($val)) {
            $this->addError($name, 'email cannot be empty');
        } else if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
            $this->addError($name, 'email must be a valid email address');
        } else {
            $this->addSucces($name, 'Pochvala');
        }
    }
}
