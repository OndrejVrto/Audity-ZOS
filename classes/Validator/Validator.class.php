<?PHP
/*
-------------------------------------------------------------------------
    PHP Form Validator (formvalidator.php)
            Version 1.1
    This program is free software published under the
    terms of the GNU Lesser General Public License.

    This program is distributed in the hope that it will
    be useful - WITHOUT ANY WARRANTY; without even the
    implied warranty of MERCHANTABILITY or FITNESS FOR A
    PARTICULAR PURPOSE.
        
    For updates, please visit:
    http://www.html-form-guide.com/php-form/php-form-validation.html
    
    Questions & comments please send to info@html-form-guide.com
-------------------------------------------------------------------------  
*/

/*
-------------------------------------------------------------------------
Pre potreby mojej aplikácie upravil, doplnil a preložil: Ondrej Vrťo
-------------------------------------------------------------------------  
*/

namespace Validator;

/**
 * Carries information about each of the form validations
 */
class ValidatorObj
{
    var $variable_name;
    var $validator_string;
    var $error_string;
}

/**
 * Base class for custom validation objects
 **/
class CustomValidator
{
    function DoValidate(&$formars, &$error_hash)
    {
        return true;
    }
}

/** Default error messages*/
define("E_VAL_REQUIRED_VALUE", "Zadajte hodnotu pre %s");
define("E_VAL_MAXLEN_EXCEEDED", "Bola prekročená maximálna dĺžka pre %s");
define("E_VAL_MINLEN_CHECK_FAILED", "Zadajte vstup s dĺžkou väčšou ako %d pre %s");
define("E_VAL_ALNUM_CHECK_FAILED", "Zadajte alfanumerický vstup pre %s");
define("E_VAL_ALNUM_S_CHECK_FAILED", "Zadajte alfanumerický vstup pre %s");
define("E_VAL_NUM_CHECK_FAILED", "Zadajte číselný vstup pre %s");
define("E_VAL_ALPHA_CHECK_FAILED", "Zadajte abecedný vstup pre %s");
define("E_VAL_ALPHA_S_CHECK_FAILED", "Zadajte abecedný vstup pre %s");
define("E_VAL_EMAIL_CHECK_FAILED", "Zadajte platnú e-mailovú adresu");
define("E_VAL_LESSTHAN_CHECK_FAILED", "Zadajte hodnotu menšiu ako %f pre %s");
define("E_VAL_GREATERTHAN_CHECK_FAILED", "Zadajte hodnotu menšiu ako %f pre %s");
define("E_VAL_REGEXP_CHECK_FAILED", "Zadajte platný vstup pre %s");
define("E_VAL_DONTSEL_CHECK_FAILED", "Pre %s bola vybratá nesprávna možnosť");
define("E_VAL_SHOULD_SEL_CHECK_FAILED", "Vybratá možnosť %s by mala byť rovnaká ako %s");
define("E_VAL_SELMIN_CHECK_FAILED", "Vyberte minimálne %d možnosti pre %s");
define("E_VAL_SELONE_CHECK_FAILED", "Vyberte možnosť pre %s");
define("E_VAL_EQELMNT_CHECK_FAILED", "Hodnota %s by mala byť rovnaká ako hodnota %s");
define("E_VAL_NEELMNT_CHECK_FAILED", "Hodnota %s by nemala byť rovnaká ako hodnota %s");



/**
 * FormValidator: The main class that does all the form validations
 **/
class Validator
{
    private $validator_array = [];
    private $error_hash = [];
    public $custom_validators = [];
    public $form_variables = [];

    public function __construct()
    {
        if (strcmp($_SERVER['REQUEST_METHOD'], 'POST') == 0) {
            $this->form_variables = $_POST;
        } else {
            $this->form_variables = $_GET;
        }
    }

    public function getVAL($MenoPola)
    {
        if (!array_key_exists($MenoPola, $this->form_variables)) {
            return null;
        } else {
            return vycistiText($this->form_variables[$MenoPola]);
        };
    }
    
    public function getCLS($MenoPola)
    {
        if (array_key_exists($MenoPola, $this->error_hash)) {
            return ' is-invalid';
        } elseif (array_key_exists($MenoPola, $this->form_variables)) {
            foreach($this->validator_array as $obj) {
                if ($obj->variable_name == $MenoPola) {
                    return ' is-valid';
                    break;
                }
            }
        } else {
            return null;
        };
    }

    public function getMSG($MenoPola)
    {
        if (array_key_exists($MenoPola, $this->error_hash)) {
            return '<div class="mb-n2 d-block invalid-feedback">'.vycistiText($this->error_hash[$MenoPola]).'</div>';
        } else {
            return null;
        };
    }

    function AddCustomValidator(&$customv)
    {
        array_push($this->custom_validators, $customv);
    }

    function addValidation($variable, $validator, $error)
    {
        $validator_obj = new ValidatorObj();
        $validator_obj->variable_name = $variable;
        $validator_obj->validator_string = $validator;
        $validator_obj->error_string = $error;
        array_push($this->validator_array, $validator_obj);
    }

    function GetAllErrors()
    {
        return $this->error_hash;
    }

    function GetvalArr()
    {
        return $this->validator_array;
    }



    function ValidateForm()
    {
        $vysledok = true;

        $error_string = "";
        $error_to_display = "";

        if (count($this->custom_validators) > 0) {
            foreach ($this->custom_validators as $custom_val) {
                if (false == $custom_val->DoValidate($this->form_variables, $this->error_hash)) {
                    $vysledok = false;
                }
            }
        }
        
        foreach ($this->validator_array as $val_obj) {
            if (!$this->ValidateObject($val_obj, $this->form_variables, $error_string)) {
                $vysledok = false;
                $this->error_hash[$val_obj->variable_name] = $error_string;
            }
        }        
        return $vysledok;
    }


    function ValidateObject($validatorobj, $formvariables, &$error_string)
    {
        $vysledok = true;

        $splitted = explode("=", $validatorobj->validator_string);
        $command = $splitted[0];
        $command_value = '';

        if (isset($splitted[1]) && strlen($splitted[1]) > 0) {
            $command_value = $splitted[1];
        }

        $default_error_message = "";

        $input_value = "";

        if (isset($formvariables[$validatorobj->variable_name])) {
            $input_value = $formvariables[$validatorobj->variable_name];
        }

        $vysledok = $this->ValidateCommand(
            $command,
            $command_value,
            $input_value,
            $default_error_message,
            $validatorobj->variable_name,
            $formvariables
        );


        if (false == $vysledok) {
            if (
                isset($validatorobj->error_string) &&
                strlen($validatorobj->error_string) > 0
            ) {
                $error_string = $validatorobj->error_string;
            } else {
                $error_string = $default_error_message;
            }
        } //if
        return $vysledok;
    }












    function validate_req($input_value, &$default_error_message, $variable_name)
    {
        $vysledok = true;
        if (
            !isset($input_value) ||
            strlen($input_value) <= 0
        ) {
            $vysledok = false;
            $default_error_message = sprintf(E_VAL_REQUIRED_VALUE, $variable_name);
        }
        return $vysledok;
    }

    function validate_maxlen($input_value, $max_len, $variable_name, &$default_error_message)
    {
        $vysledok = true;
        if (isset($input_value)) {
            $input_length = strlen($input_value);
            if ($input_length > $max_len) {
                $vysledok = false;
                $default_error_message = sprintf(E_VAL_MAXLEN_EXCEEDED, $variable_name);
            }
        }
        return $vysledok;
    }

    function validate_minlen($input_value, $min_len, $variable_name, &$default_error_message)
    {
        $vysledok = true;
        if (isset($input_value)) {
            $input_length = strlen($input_value);
            if ($input_length < $min_len) {
                $vysledok = false;
                $default_error_message = sprintf(E_VAL_MINLEN_CHECK_FAILED, $min_len, $variable_name);
            }
        }
        return $vysledok;
    }

    function test_datatype($input_value, $reg_exp)
    {
        if (!preg_match($reg_exp, trim($input_value))) {
            return false;
        }
        return true;
    }

    function validate_email($email)
    {
        //return preg_match("^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$", $email);
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    function validate_for_numeric_input($input_value, &$validation_success)
    {

        $more_validations = true;
        $validation_success = true;
        if (strlen($input_value) > 0) {

            if (false == is_numeric($input_value)) {
                $validation_success = false;
                $more_validations = false;
            }
        } else {
            $more_validations = false;
        }
        return $more_validations;
    }

    function validate_lessthan(
        $command_value,
        $input_value,
        $variable_name,
        &$default_error_message
    ) {
        $vysledok = true;
        if (false == $this->validate_for_numeric_input(
            $input_value,
            $vysledok
        )) {
            return $vysledok;
        }
        if ($vysledok) {
            $lessthan = doubleval($command_value);
            $float_inputval = doubleval($input_value);
            if ($float_inputval >= $lessthan) {
                $default_error_message = sprintf(
                    E_VAL_LESSTHAN_CHECK_FAILED,
                    $lessthan,
                    $variable_name
                );
                $vysledok = false;
            } //if
        }
        return $vysledok;
    }

    function validate_greaterthan($command_value, $input_value, $variable_name, &$default_error_message)
    {
        $vysledok = true;
        if (false == $this->validate_for_numeric_input($input_value, $vysledok)) {
            return $vysledok;
        }
        if ($vysledok) {
            $greaterthan = doubleval($command_value);
            $float_inputval = doubleval($input_value);
            if ($float_inputval <= $greaterthan) {
                $default_error_message = sprintf(
                    E_VAL_GREATERTHAN_CHECK_FAILED,
                    $greaterthan,
                    $variable_name
                );
                $vysledok = false;
            } //if
        }
        return $vysledok;
    }

    function validate_select($input_value, $command_value, &$default_error_message, $variable_name)
    {
        $vysledok = false;
        if (is_array($input_value)) {
            foreach ($input_value as $value) {
                if ($value == $command_value) {
                    $vysledok = true;
                    break;
                }
            }
        } else {
            if ($command_value == $input_value) {
                $vysledok = true;
            }
        }
        if (false == $vysledok) {
            $default_error_message = sprintf(E_VAL_SHOULD_SEL_CHECK_FAILED, $command_value, $variable_name);
        }
        return $vysledok;
    }

    function validate_dontselect($input_value, $command_value, &$default_error_message, $variable_name)
    {
        $vysledok = true;
        if (is_array($input_value)) {
            foreach ($input_value as $value) {
                if ($value == $command_value) {
                    $vysledok = false;
                    $default_error_message = sprintf(E_VAL_DONTSEL_CHECK_FAILED, $variable_name);
                    break;
                }
            }
        } else {
            if ($command_value == $input_value) {
                $vysledok = false;
                $default_error_message = sprintf(E_VAL_DONTSEL_CHECK_FAILED, $variable_name);
            }
        }
        return $vysledok;
    }



    function ValidateCommand($command, $command_value, $input_value, &$default_error_message, $variable_name, $formvariables)
    {
        $vysledok = true;
        switch ($command) {
            case 'req': {
                    $vysledok = $this->validate_req($input_value, $default_error_message, $variable_name);
                    break;
                }

            case 'maxlen': {
                    $max_len = intval($command_value);
                    $vysledok = $this->validate_maxlen(
                        $input_value,
                        $max_len,
                        $variable_name,
                        $default_error_message
                    );
                    break;
                }

            case 'minlen': {
                    $min_len = intval($command_value);
                    $vysledok = $this->validate_minlen(
                        $input_value,
                        $min_len,
                        $variable_name,
                        $default_error_message
                    );
                    break;
                }

            case 'alnum': {
                    $vysledok = $this->test_datatype($input_value, "/^[A-Za-z0-9]+$/");
                    if (false == $vysledok) {
                        $default_error_message = sprintf(E_VAL_ALNUM_CHECK_FAILED, $variable_name);
                    }
                    break;
                }

            case 'alnum_s': {
                    $vysledok = $this->test_datatype($input_value, "/^[A-Za-z0-9 ]+$/");
                    if (false == $vysledok) {
                        $default_error_message = sprintf(E_VAL_ALNUM_S_CHECK_FAILED, $variable_name);
                    }
                    break;
                }

            case 'num':
            case 'numeric': {
                    $vysledok = $this->test_datatype($input_value, "/^[0-9]+$/");
                    if (false == $vysledok) {
                        $default_error_message = sprintf(E_VAL_NUM_CHECK_FAILED, $variable_name);
                    }
                    break;
                }

            case 'alpha': {
                    $vysledok = $this->test_datatype($input_value, "/^[A-Za-z]+$/");
                    if (false == $vysledok) {
                        $default_error_message = sprintf(E_VAL_ALPHA_CHECK_FAILED, $variable_name);
                    }
                    break;
                }
            case 'alpha_s': {
                    $vysledok = $this->test_datatype($input_value, "/^[A-Za-z ]+$/");
                    if (false == $vysledok) {
                        $default_error_message = sprintf(E_VAL_ALPHA_S_CHECK_FAILED, $variable_name);
                    }
                    break;
                }
            case 'email': {
                    if (isset($input_value) && strlen($input_value) > 0) {
                        $vysledok = $this->validate_email($input_value);
                        if (false == $vysledok) {
                            $default_error_message = E_VAL_EMAIL_CHECK_FAILED;
                        }
                    }
                    break;
                }
            case "lt":
            case "lessthan": {
                    $vysledok = $this->validate_lessthan(
                        $command_value,
                        $input_value,
                        $variable_name,
                        $default_error_message
                    );
                    break;
                }
            case "gt":
            case "greaterthan": {
                    $vysledok = $this->validate_greaterthan(
                        $command_value,
                        $input_value,
                        $variable_name,
                        $default_error_message
                    );
                    break;
                }

            case "regexp": {
                    if (isset($input_value) && strlen($input_value) > 0) {
                        if (!preg_match("$command_value", $input_value)) {
                            $vysledok = false;
                            $default_error_message = sprintf(E_VAL_REGEXP_CHECK_FAILED, $variable_name);
                        }
                    }
                    break;
                }
            case "dontselect":
            case "dontselectchk":
            case "dontselectradio": {
                    $vysledok = $this->validate_dontselect(
                        $input_value,
                        $command_value,
                        $default_error_message,
                        $variable_name
                    );
                    break;
                } //case

            case "shouldselchk":
            case "selectradio": {
                    $vysledok = $this->validate_select(
                        $input_value,
                        $command_value,
                        $default_error_message,
                        $variable_name
                    );
                    break;
                } //case
            case "selmin": {
                    $min_count = intval($command_value);

                    if (isset($input_value)) {
                        if ($min_count > 1) {
                            $vysledok = (count($input_value) >= $min_count) ? true : false;
                        } else {
                            $vysledok = true;
                        }
                    } else {
                        $vysledok = false;
                        $default_error_message = sprintf(E_VAL_SELMIN_CHECK_FAILED, $min_count, $variable_name);
                    }

                    break;
                } //case
            case "selone": {
                    if (
                        false == isset($input_value) ||
                        strlen($input_value) <= 0
                    ) {
                        $vysledok = false;
                        $default_error_message = sprintf(E_VAL_SELONE_CHECK_FAILED, $variable_name);
                    }
                    break;
                }
            case "eqelmnt": {

                    if (
                        isset($formvariables[$command_value]) &&
                        strcmp($input_value, $formvariables[$command_value]) == 0
                    ) {
                        $vysledok = true;
                    } else {
                        $vysledok = false;
                        $default_error_message = sprintf(E_VAL_EQELMNT_CHECK_FAILED, $variable_name, $command_value);
                    }
                    break;
                }
            case "neelmnt": {
                    if (
                        isset($formvariables[$command_value]) &&
                        strcmp($input_value, $formvariables[$command_value]) != 0
                    ) {
                        $vysledok = true;
                    } else {
                        $vysledok = false;
                        $default_error_message = sprintf(E_VAL_NEELMNT_CHECK_FAILED, $variable_name, $command_value);
                    }
                    break;
                }
        } //switch
        return $vysledok;
    } //validdate command


}
