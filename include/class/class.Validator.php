<?php

class Validator 
{

    protected  $privat_data;
    
    protected  $privat_values;
    protected  $privat_classes;
    protected  $privat_errors;
    protected  $privat_succes = 1;    
    protected  $privat_feedback;
    public $odsadenie = 5;


    public function __construct($post_data)
    {
        $this->privat_data = $post_data;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function validateForm()
    {
        foreach ($this->privat_data as $key => $value) {

            $this->addValue($key, $value);

        }
        return $this->privat_succes;
    }

    public function validateFormGetValues()
    {
        return $this->privat_values;
    }

    public function validateFormGetClasses(){
        return $this->privat_classes;
    }

    public function validateFormGetFeedback(){
        return $this->privat_feedback;
    }


    // NEVEREJNE funkcie

    // funkcie na priradovanie hodnôt do polí
    protected function addValue($key, $val)
    {
        $this->privat_values[$key] = htmlspecialchars($val);
    }

    protected function addClass($key, $bool)
    {
        if ($bool){
            $this->privat_classes[$key] = ' is-valid';
        } else {
            $this->privat_classes[$key] = ' is-invalid';
        }
    }

    protected function addError($key, $val)
    {
        $this->privat_errors[$key][] = htmlspecialchars($val);
        $this->addClass($key, FALSE);
        $this->addFeedback($key, FALSE);
        $this->privat_succes = 0;
    }

    protected function addSucces($key, $val)
    {
        $this->privat_errors[$key][] = htmlspecialchars($val);
        $this->addClass($key, TRUE);
        $this->addFeedback($key, TRUE);
    }

    protected function addFeedback($key, $bool)
    {
        $countErrors = count($this->privat_errors[$key]);
        $odsad = str_repeat("\t", $this->odsadenie );

        if ($countErrors == 1) {
            $list = $this->privat_errors[$key][0];
        } elseif ($countErrors > 1) {
            $list = '<ul class="list-unstyled">';
            foreach ($this->privat_errors[$key] as $kluc => $value) {
                $list .= "\n\t\t".$odsad. '<li>'. $value .'</li>';
            }
            $list .= "\n\t".$odsad.'</ul>';
        }

        $this->privat_feedback[$key] = $odsad.'<div class="'.($bool ? 'valid' : 'invalid').'-feedback">'."\n\t".$odsad.$list."\n".$odsad.'</div>'."\n";
    }


    // Validacne funkcie sú definované v rozsirených triedach pre každý formulár zvlášť

}
