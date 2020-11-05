<?php

class ValidatorOblastAuditu extends Validator
{
    private $connDb;
    private $link;

    function __construct($post_data, $link)
    {
            $this->privat_data = $post_data;
            $this->link = $link;
            $this->connDb =& $GLOBALS['conn'];
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


    // Validacne funkcie

    public function validateOblast($name)
    {
        $valueNEW = $this->privat_data[$name];
        $valueOLD = $this->privat_data['valueOld'];

        if ($this->is_required($valueNEW)) {
            $this->addError($name, 'Poľe nesmie byť prázdne.');
        } else {
            // kontrola či je záznam použitý v iných tabuľkách. Ak áno, nedá editovať jeho názov.
            $hodnota = mysqli_real_escape_string($this->connDb, $valueNEW);
            $hodnotaOLD = mysqli_real_escape_string($this->connDb, $valueOLD);

            $sql = "SELECT COUNT(*) AS Pocet FROM `30_zoznam_oblast_auditu` WHERE `OblastAuditovania`= '".$hodnota."' AND  `OblastAuditovania` <> '".$hodnotaOLD."';";
            
            $pocetArray = dBzoznam($sql, $this->link);
            // vytiahnutie počtu z výsledku dotazu
            $pocet = (int)$pocetArray[0]['Pocet'];

            if ($pocet > 0) {
                $this->addError($name, 'Zadaná hodnota sa už nachadza v zozname.');
            } else {
                $this->addSucces($name, '');
            }
        }
    }
}
