<?php

class Validator 
{

    protected $privat_data;
    
    protected $privat_values;
    protected $privat_classes;
    protected $privat_errors;
    protected $privat_succes = 1;    
    protected $privat_feedback;
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
        $this->privat_values[$key] = $this->purify($val);
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
        $this->privat_errors[$key][] = $this->purify($val);
        $this->addClass($key, FALSE);
        $this->addFeedback($key, FALSE);
        $this->privat_succes = 0;
    }

    protected function addSucces($key, $val)
    {
        $this->privat_errors[$key][] = $this->purify($val);
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

        $this->privat_feedback[$key] = $odsad.'<div class="mb-n2 d-block '.($bool ? 'valid' : 'invalid').'-feedback">'."\n\t".$odsad.$list."\n".$odsad.'</div>'."\n";
    }
    // fynkcia čistí text pre výstup do HTML
    protected function purify($string){
        return htmlspecialchars($string, ENT_HTML5, 'UTF-8');
    }

    // Validacne funkcie hodnôt

    //  Kontrola či je vyplnená hodnota
    protected static function is_required($value){
        if(empty(trim($value))) return true;
    }
    //  Kontrola či hodnota celé číslo
    protected static function is_int($value){
        if(filter_var(trim($value), FILTER_VALIDATE_INT)) return true;
    }
    //  Kontrola či hodnota číslo
    protected static function is_float($value){
        if(filter_var(trim($value), FILTER_VALIDATE_FLOAT)) return true;
    }
    //  Kontrola či hodnota zložená len s písmen
    protected static function is_alpha($value){
        if(filter_var(trim($value), FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z]+$/")))) return true;
        //if(!preg_match('/^([a-zA-Z])+$/i', $value)) return true;  // iná verzia
    }
    //  Kontrola či hodnota zložená s písmen a číslic
    protected static function is_alphanum($value){
        if(filter_var(trim($value), FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")))) return true;
        //if(!preg_match('/^([a-zA-Z0-9])+$/i', $value)) return true;  // iná verzia
    }
    //  Kontrola či hodnota zložená z číslic
    protected static function is_numeric($value){
        if(!preg_match('/^[\-+]?[0-9]*\.?[0-9]+$/', trim($value))) return true;
    }    
    //  Kontrola či hodnota url adresa v správnom formáte
    protected static function is_url($value){
        if(filter_var(trim($value), FILTER_VALIDATE_URL)) return true;
        // if(!preg_match('/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/', $value)) return true;  // iná verzia
    }
    //  Kontrola či hodnota uri adresa v správnom formáte
    protected static function is_uri($value){
        if(filter_var(trim($value), FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[A-Za-z0-9-\/_]+$/")))) return true;
    }
    //  Kontrola či hodnota TRUE alebo FALSE
    protected static function is_bool($value){
        if(is_bool(filter_var(trim($value), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) return true;
    }
    //  Kontrola či hodnota v správnom formáte: e-mail
    protected static function is_email($value){
        if(filter_var(trim($value), FILTER_VALIDATE_EMAIL)) return true;
    }
    //  Kontrola či sú hodnoty rovnaké
    protected static function is_match($value, $valuematch){
        if(trim($value) != trim($valuematch)) return true;
    }
    //  Kontrola či je hodnota dlkšia ako maximálna hodnota
    protected static function is_maxLength($value, $maximum){
        if(strlen(trim($value)) > $maximum) return true;
    }
    //  Kontrola či je hodnota kratšia ako minimálna hodnota
    protected static function is_minLength($value, $minimum){
        if(strlen(trim($value)) < $minimum) return true;
    }
    //  Kontrola či je hodnota dlhá ako nastavená hodnota
    protected static function is_exactLength($value, $len){
        if(strlen(trim($value)) != $len) return true;
    }
    //  Kontrola či je hodnota váčšia ako druhá hodnota - platí pre čísla
    protected static function is_lessThan($valueA, $valueB){
        if (is_numeric(trim($valueA)) && is_numeric(trim($valueB))) {
            if(trim($valueA) >= trim($valueB)) return true;
        }
    }
    //  Kontrola či je hodnota menšia ako druhá hodnota - platí pre čísla
    protected static function is_greaterThan($valueA, $valueB){
        if (is_numeric(trim($valueA)) && is_numeric(trim($valueB))) {
            if(trim($valueA) <= trim($valueB)) return true;
        }
    }

}