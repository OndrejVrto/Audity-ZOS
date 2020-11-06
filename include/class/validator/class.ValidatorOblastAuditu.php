<?php

class ValidatorOblastAuditu extends Validator
{
    private $db;

    function __construct($post_data)
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
                case "oblast-auditu":
                    $this->validateOblast($key);
                    break;
                case "oblast-auditu-poznamka":
                    $this->addSucces($key, '');
                    break;
            } 
        }
        return $this->privat_succes;
    }

    public function validateOblast($name)
    {
        $valueNEW = $this->privat_data[$name];
        $valueOLD = $this->privat_data['valueOld'];

        if ($this->is_required($valueNEW)) {
            $this->addError($name, 'Poľe nesmie byť prázdne.');
        } else {
            // kontrola či je záznam použitý v iných tabuľkách. Ak áno, nedá editovať jeho názov.

            $data = $this->db->query('SELECT COUNT(*) AS Pocet FROM `30_zoznam_oblast_auditu` WHERE `OblastAuditovania`= ? AND  `OblastAuditovania` <> ?', $valueNEW, $valueOLD )->fetchArray();

            $pocet = (int)$data['Pocet'];

            if ($pocet > 0) {
                $this->addError($name, 'Zadaná hodnota sa už nachadza v zozname.');
            } else {
                $this->addSucces($name, '');
            }
        }
    }
}
