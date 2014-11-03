<?php namespace Schuppo\PasswordStrength;

class PasswordStrength {

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
	
	public function validateSymbols($attribute, $value, $parameters)
    {
        return preg_match('/[!@#$%^&*?()\-_=+{};:,<.>]/', $value);
    }
}
