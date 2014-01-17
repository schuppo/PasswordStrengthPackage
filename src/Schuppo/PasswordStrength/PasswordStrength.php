<?php namespace Schuppo\PasswordStrength;

use \Illuminate\Validation\Validator;

class PasswordStrength extends Validator implements PasswordStrengthInterface {

    public function __construct($translator, $data, $rules, $messages = null)
    {
        parent::__construct($translator, $data, $rules, [
                "letters" => "The :attribute must include at least one letter.",
                "case_diff" => "The :attribute must include both upper and lower case letters.",
                "numbers" => "The :attribute must include at least one number."
        ]);
    }

    public function validateLetters($attribute, $value, $parameters)
    {
        return preg_match('/\pL/', $value);
    }

    public function validateNumbers($attribute, $value, $parameters)
    {
        return preg_match('/\pN/', $value);
    }

    public function validateCaseDiff($attribute, $value, $parameters)
    {
        return preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/', $value);
    }
}
